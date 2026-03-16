<?php

namespace App\Http\Controllers;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_propiedades' => Property::count(),
            'inmobiliarias_activas' => Inmobiliaria::where('is_approved', true)->count(),
            'consultas_hoy' => Inquiry::whereDate('created_at', today())->count(),
            'vistas_hoy' => Property::sum('views_count'),
        ];

        // Propiedades por tipo
        $propiedadesPorTipo = Property::select('tipo', DB::raw('count(*) as total'))
            ->groupBy('tipo')
            ->get();

        // Propiedades por operación
        $propiedadesPorOperacion = Property::select('operacion', DB::raw('count(*) as total'))
            ->groupBy('operacion')
            ->get();

        // Consultas últimos 30 días
        $consultasPorDia = Inquiry::select(
            DB::raw('DATE(created_at) as fecha'),
            DB::raw('count(*) as total')
        )
        ->where('created_at', '>=', now()->subDays(29))
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();

        $ultimasInmobiliarias = Inmobiliaria::with('user')
            ->latest()
            ->take(5)
            ->get();

        $ultimasPropiedades = Property::with(['inmobiliaria', 'images'])
            ->latest()
            ->take(5)
            ->get();

        $consultasRecientes = Inquiry::with('property')
            ->whereNull('read_at')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 
            'propiedadesPorTipo', 
            'propiedadesPorOperacion', 
            'consultasPorDia',
            'ultimasInmobiliarias',
            'ultimasPropiedades',
            'consultasRecientes'
        ));
    }

    public function inmobiliarias(Request $request)
    {
        $query = Inmobiliaria::with(['user', 'properties']);
        
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('estado')) {
            $query->where('is_approved', $request->estado === 'aprobada');
        }
        
        $inmobiliarias = $query->latest()->paginate(15)->withQueryString();
        return view('admin.inmobiliarias', compact('inmobiliarias'));
    }

    public function aprobarInmobiliaria($id)
    {
        $inmo = Inmobiliaria::findOrFail($id);
        $inmo->update(['is_approved' => true]);
        return redirect()->back()->with('success', "Inmobiliaria '{$inmo->nombre}' aprobada correctamente.");
    }

    public function rechazarInmobiliaria($id)
    {
        $inmo = Inmobiliaria::findOrFail($id);
        $inmo->update(['is_approved' => false]);
        return redirect()->back()->with('success', "Inmobiliaria '{$inmo->nombre}' suspendida.");
    }

    public function propiedades(Request $request)
    {
        $query = Property::with(['inmobiliaria', 'images']);
        
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $properties = $query->latest()->paginate(20)->withQueryString();
        return view('admin.propiedades', compact('properties'));
    }

    public function toggleDestacado($id)
    {
        $property = Property::findOrFail($id);
        $property->update(['destacado' => !$property->destacado]);
        $msg = $property->destacado ? 'marcada como destacada' : 'removida de destacadas';
        return redirect()->back()->with('success', "Propiedad {$msg}.");
    }

    public function consultas(Request $request)
    {
        $query = Inquiry::with(['property', 'property.inmobiliaria']);
        
        if ($request->filled('leida')) {
            if ($request->leida === '0') {
                $query->whereNull('read_at');
            } else {
                $query->whereNotNull('read_at');
            }
        }
        
        $consultas = $query->latest()->paginate(20)->withQueryString();
        return view('admin.consultas', compact('consultas'));
    }

    public function marcarLeidaConsulta($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->update(['read_at' => now()]);
        return redirect()->back()->with('success', 'Consulta marcada como leída.');
    }

    public function reportes()
    {
        $stats = [
            'total_propiedades' => Property::count(),
            'total_inmobiliarias' => Inmobiliaria::count(),
            'total_consultas' => Inquiry::count(),
            'total_vistas' => Property::sum('views_count'),
        ];

        $topPropiedades = Property::with('inmobiliaria')
            ->orderByDesc('views_count')
            ->take(10)
            ->get();

        $topInmobiliarias = Inmobiliaria::withCount('properties')
            ->orderByDesc('properties_count')
            ->take(10)
            ->get();

        return view('admin.reportes', compact('stats', 'topPropiedades', 'topInmobiliarias'));
    }
}
