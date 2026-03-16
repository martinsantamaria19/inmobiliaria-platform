<?php

namespace App\Http\Controllers;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanelController extends Controller
{
    private function getInmobiliaria()
    {
        return Auth::user()->inmobiliaria;
    }

    public function dashboard()
    {
        $inmo = $this->getInmobiliaria();
        
        $stats = [
            'propiedades_activas' => $inmo->properties()->where('estado', 'disponible')->count(),
            'consultas_nuevas' => Inquiry::whereHas('property', fn($q) => $q->where('inmobiliaria_id', $inmo->id))->whereNull('read_at')->count(),
            'vistas_totales' => $inmo->properties()->sum('views_count'),
            'propiedades_destacadas' => $inmo->properties()->where('destacado', true)->count(),
        ];

        $propiedadesRecientes = $inmo->properties()->with('images')->latest()->take(5)->get();
        
        $consultasRecientes = Inquiry::whereHas('property', fn($q) => $q->where('inmobiliaria_id', $inmo->id))
            ->with('property')
            ->latest()
            ->take(5)
            ->get();

        return view('panel.dashboard', compact('stats', 'propiedadesRecientes', 'consultasRecientes', 'inmo'));
    }

    public function propiedades(Request $request)
    {
        $inmo = $this->getInmobiliaria();
        $query = $inmo->properties()->with('images');
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $properties = $query->latest()->paginate(15)->withQueryString();
        return view('panel.propiedades.index', compact('properties', 'inmo'));
    }

    public function createProperty()
    {
        return view('panel.propiedades.create');
    }

    public function storeProperty(Request $request)
    {
        $inmo = $this->getInmobiliaria();
        
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:casa,apartamento,local,terreno,oficina',
            'operacion' => 'required|in:venta,alquiler,ambas',
            'precio_venta' => 'nullable|numeric|min:0',
            'precio_alquiler' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:USD,UYU',
            'estado' => 'required|in:disponible,reservado,vendido,alquilado',
            'dormitorios' => 'required|integer|min:0',
            'banos' => 'required|integer|min:1',
            'superficie_total' => 'nullable|numeric|min:0',
            'superficie_construida' => 'nullable|numeric|min:0',
            'direccion' => 'nullable|string|max:255',
            'barrio' => 'nullable|string|max:100',
            'ciudad' => 'required|string|max:100',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'features' => 'nullable|array',
        ]);

        $property = $inmo->properties()->create([
            'titulo' => $validated['titulo'],
            'descripcion' => $validated['descripcion'],
            'tipo' => $validated['tipo'],
            'operacion' => $validated['operacion'],
            'precio_venta' => $validated['precio_venta'] ?? null,
            'precio_alquiler' => $validated['precio_alquiler'] ?? null,
            'moneda' => $validated['moneda'],
            'estado' => $validated['estado'],
            'dormitorios' => $validated['dormitorios'],
            'banos' => $validated['banos'],
            'superficie_total' => $validated['superficie_total'] ?? null,
            'superficie_construida' => $validated['superficie_construida'] ?? null,
            'garage' => $request->boolean('garage'),
            'piscina' => $request->boolean('piscina'),
            'direccion' => $validated['direccion'] ?? null,
            'barrio' => $validated['barrio'] ?? null,
            'ciudad' => $validated['ciudad'],
        ]);

        // Guardar imágenes
        if ($request->filled('image_urls')) {
            $urls = array_filter(explode("\n", $request->image_urls));
            foreach ($urls as $i => $url) {
                $url = trim($url);
                if (filter_var($url, FILTER_VALIDATE_URL)) {
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'url' => $url,
                        'is_primary' => $i === 0,
                        'orden' => $i,
                    ]);
                }
            }
        }

        // Guardar amenities
        if ($request->filled('features_text')) {
            $features = array_filter(explode("\n", $request->features_text));
            foreach ($features as $feature) {
                $feature = trim($feature);
                if ($feature) {
                    PropertyFeature::create(['property_id' => $property->id, 'feature' => $feature]);
                }
            }
        }

        return redirect()->route('panel.propiedades')->with('success', 'Propiedad creada exitosamente.');
    }

    public function editProperty($id)
    {
        $inmo = $this->getInmobiliaria();
        $property = $inmo->properties()->with(['images', 'features'])->findOrFail($id);
        return view('panel.propiedades.edit', compact('property'));
    }

    public function updateProperty(Request $request, $id)
    {
        $inmo = $this->getInmobiliaria();
        $property = $inmo->properties()->findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'tipo' => 'required|in:casa,apartamento,local,terreno,oficina',
            'operacion' => 'required|in:venta,alquiler,ambas',
            'precio_venta' => 'nullable|numeric|min:0',
            'precio_alquiler' => 'nullable|numeric|min:0',
            'moneda' => 'required|in:USD,UYU',
            'estado' => 'required|in:disponible,reservado,vendido,alquilado',
            'dormitorios' => 'required|integer|min:0',
            'banos' => 'required|integer|min:1',
            'superficie_total' => 'nullable|numeric|min:0',
            'superficie_construida' => 'nullable|numeric|min:0',
            'direccion' => 'nullable|string|max:255',
            'barrio' => 'nullable|string|max:100',
            'ciudad' => 'required|string|max:100',
        ]);

        $property->update(array_merge($validated, [
            'garage' => $request->boolean('garage'),
            'piscina' => $request->boolean('piscina'),
        ]));

        return redirect()->route('panel.propiedades')->with('success', 'Propiedad actualizada exitosamente.');
    }

    public function destroyProperty($id)
    {
        $inmo = $this->getInmobiliaria();
        $property = $inmo->properties()->findOrFail($id);
        $property->delete();
        return redirect()->route('panel.propiedades')->with('success', 'Propiedad eliminada.');
    }

    public function consultas()
    {
        $inmo = $this->getInmobiliaria();
        $consultas = Inquiry::whereHas('property', fn($q) => $q->where('inmobiliaria_id', $inmo->id))
            ->with('property')
            ->latest()
            ->paginate(20);
        return view('panel.consultas', compact('consultas', 'inmo'));
    }

    public function marcarLeida($id)
    {
        $inmo = $this->getInmobiliaria();
        $inquiry = Inquiry::whereHas('property', fn($q) => $q->where('inmobiliaria_id', $inmo->id))
            ->findOrFail($id);
        $inquiry->update(['read_at' => now()]);
        return redirect()->back()->with('success', 'Consulta marcada como leída.');
    }

    public function perfil()
    {
        $inmo = $this->getInmobiliaria();
        return view('panel.perfil', compact('inmo'));
    }

    public function updatePerfil(Request $request)
    {
        $inmo = $this->getInmobiliaria();
        
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'telefono' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'logo' => 'nullable|url',
            'direccion' => 'nullable|string|max:255',
            'ciudad' => 'nullable|string|max:100',
            'sitio_web' => 'nullable|url',
        ]);

        $inmo->update($validated);
        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }
}
