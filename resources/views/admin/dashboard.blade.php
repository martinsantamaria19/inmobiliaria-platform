@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard General')

@section('content')

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_propiedades']) }}</p>
        <p class="text-gray-500 text-sm mt-1">Total Propiedades</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['inmobiliarias_activas'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Inmobiliarias Activas</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['consultas_hoy'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Consultas Hoy</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['vistas_hoy']) }}</p>
        <p class="text-gray-500 text-sm mt-1">Vistas Totales</p>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
    
    <!-- Consultas últimos 30 días - Line Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4">Consultas — últimos 30 días</h3>
        <canvas id="chartConsultas" height="100"></canvas>
    </div>
    
    <!-- Propiedades por operación - Donut Chart -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4">Por operación</h3>
        <canvas id="chartOperacion" height="180"></canvas>
    </div>
</div>

<!-- Propiedades por tipo - Bar Chart -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4">Propiedades por tipo</h3>
        <canvas id="chartTipos" height="150"></canvas>
    </div>
    
    <!-- Consultas recientes sin leer -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Consultas sin leer</h3>
            <a href="{{ route('admin.consultas') }}?leida=0" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver todas</a>
        </div>
        @if($consultasRecientes->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">No hay consultas sin leer</p>
        @else
        <div class="space-y-3">
            @foreach($consultasRecientes->take(5) as $consulta)
            <div class="flex items-start space-x-3 p-3 bg-orange-50 rounded-xl">
                <div class="w-8 h-8 bg-orange-200 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-orange-700 text-sm font-bold">{{ substr($consulta->nombre, 0, 1) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900">{{ $consulta->nombre }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $consulta->property->titulo }}</p>
                    <p class="text-xs text-gray-400">{{ $consulta->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

<!-- Tables Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    
    <!-- Últimas inmobiliarias -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Últimas inmobiliarias</h3>
            <a href="{{ route('admin.inmobiliarias') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver todas</a>
        </div>
        <div class="space-y-3">
            @foreach($ultimasInmobiliarias as $inmo)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-sm">{{ substr($inmo->nombre, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $inmo->nombre }}</p>
                        <p class="text-xs text-gray-500">{{ $inmo->ciudad }}</p>
                    </div>
                </div>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $inmo->is_approved ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $inmo->is_approved ? 'Aprobada' : 'Pendiente' }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Últimas propiedades -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Últimas propiedades</h3>
            <a href="{{ route('admin.propiedades') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Ver todas</a>
        </div>
        <div class="space-y-3">
            @foreach($ultimasPropiedades as $prop)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-8 rounded-lg overflow-hidden flex-shrink-0">
                        <img src="{{ $prop->primary_image_url }}" alt="" class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=100&h=80&fit=crop'">
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $prop->titulo }}</p>
                        <p class="text-xs text-gray-500">{{ $prop->inmobiliaria->nombre }}</p>
                    </div>
                </div>
                <p class="text-sm font-semibold text-blue-600 ml-2 text-right">{{ $prop->precio_formateado }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
// Consultas por día - Line Chart
const consultasDias = @json($consultasPorDia);
const labels = [];
const data = [];
for (let i = 29; i >= 0; i--) {
    const d = new Date();
    d.setDate(d.getDate() - i);
    const fecha = d.toISOString().split('T')[0];
    labels.push(d.toLocaleDateString('es-UY', { day: '2-digit', month: 'short' }));
    const found = consultasDias.find(c => c.fecha === fecha);
    data.push(found ? found.total : 0);
}
new Chart(document.getElementById('chartConsultas'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Consultas',
            data,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.08)',
            fill: true,
            tension: 0.4,
            pointRadius: 3,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

// Por tipo - Bar Chart
const tiposData = @json($propiedadesPorTipo);
new Chart(document.getElementById('chartTipos'), {
    type: 'bar',
    data: {
        labels: tiposData.map(t => t.tipo.charAt(0).toUpperCase() + t.tipo.slice(1)),
        datasets: [{
            label: 'Propiedades',
            data: tiposData.map(t => t.total),
            backgroundColor: ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6'],
            borderRadius: 6,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

// Por operación - Donut Chart
const opData = @json($propiedadesPorOperacion);
new Chart(document.getElementById('chartOperacion'), {
    type: 'doughnut',
    data: {
        labels: opData.map(o => o.operacion.charAt(0).toUpperCase() + o.operacion.slice(1)),
        datasets: [{
            data: opData.map(o => o.total),
            backgroundColor: ['#3b82f6','#10b981','#f59e0b'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

@endsection
