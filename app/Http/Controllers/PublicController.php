<?php

namespace App\Http\Controllers;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $destacadas = Property::with(['images', 'inmobiliaria'])
            ->where('destacado', true)
            ->where('estado', 'disponible')
            ->latest()
            ->take(6)
            ->get();

        $totalPropiedades = Property::count();
        $totalInmobiliarias = Inmobiliaria::where('is_approved', true)->count();
        $totalCiudades = Property::distinct('ciudad')->count('ciudad');

        return view('public.index', compact('destacadas', 'totalPropiedades', 'totalInmobiliarias', 'totalCiudades'));
    }

    public function propiedades(Request $request)
    {
        $query = Property::with(['images', 'inmobiliaria'])
            ->where('estado', 'disponible');

        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('operacion')) {
            $query->where('operacion', $request->operacion);
        }
        if ($request->filled('ciudad')) {
            $query->where('ciudad', 'like', '%' . $request->ciudad . '%');
        }
        if ($request->filled('barrio')) {
            $query->where('barrio', 'like', '%' . $request->barrio . '%');
        }
        if ($request->filled('precio_min')) {
            $query->where(function($q) use ($request) {
                $q->where('precio_venta', '>=', $request->precio_min)
                  ->orWhere('precio_alquiler', '>=', $request->precio_min);
            });
        }
        if ($request->filled('precio_max')) {
            $query->where(function($q) use ($request) {
                $q->where('precio_venta', '<=', $request->precio_max)
                  ->orWhere('precio_alquiler', '<=', $request->precio_max);
            });
        }
        if ($request->filled('dormitorios')) {
            $query->where('dormitorios', '>=', $request->dormitorios);
        }
        if ($request->filled('banos')) {
            $query->where('banos', '>=', $request->banos);
        }
        if ($request->filled('garage')) {
            $query->where('garage', true);
        }
        if ($request->filled('piscina')) {
            $query->where('piscina', true);
        }

        $orderBy = $request->get('orden', 'destacados');
        switch ($orderBy) {
            case 'precio_asc':
                $query->orderByRaw('COALESCE(precio_venta, precio_alquiler) ASC');
                break;
            case 'precio_desc':
                $query->orderByRaw('COALESCE(precio_venta, precio_alquiler) DESC');
                break;
            case 'nuevo':
                $query->latest();
                break;
            default:
                $query->orderByDesc('destacado')->latest();
                break;
        }

        $properties = $query->paginate(12)->withQueryString();
        $ciudades = Property::distinct()->pluck('ciudad')->filter()->sort()->values();
        $barrios = Property::distinct()->pluck('barrio')->filter()->sort()->values();

        return view('public.propiedades', compact('properties', 'ciudades', 'barrios'));
    }

    public function show($id)
    {
        $property = Property::with(['images', 'features', 'inmobiliaria', 'inquiries'])->findOrFail($id);
        
        // Incrementar contador de vistas
        $property->increment('views_count');

        $similares = Property::with(['images'])
            ->where('id', '!=', $id)
            ->where('tipo', $property->tipo)
            ->where('estado', 'disponible')
            ->take(3)
            ->get();

        return view('public.show', compact('property', 'similares'));
    }

    public function sendInquiry(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:50',
            'mensaje' => 'required|string|max:2000',
        ]);

        Inquiry::create([
            'property_id' => $id,
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'mensaje' => $validated['mensaje'],
        ]);

        return redirect()->back()->with('success', '¡Tu consulta fue enviada con éxito! La inmobiliaria se pondrá en contacto con vos pronto.');
    }
}
