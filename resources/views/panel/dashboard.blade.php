@extends('layouts.panel')

@section('title', 'Mi Panel')
@section('page-title', 'Mi Dashboard')

@section('content')

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['propiedades_activas'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Propiedades activas</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['consultas_nuevas'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Consultas sin leer</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['vistas_totales']) }}</p>
        <p class="text-gray-500 text-sm mt-1">Vistas totales</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['propiedades_destacadas'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Destacadas</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- Propiedades recientes -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Mis propiedades recientes</h3>
            <a href="{{ route('panel.propiedades') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">Ver todas</a>
        </div>
        @if($propiedadesRecientes->isEmpty())
        <div class="text-center py-8">
            <p class="text-gray-500 text-sm mb-3">No tenés propiedades aún</p>
            <a href="{{ route('panel.propiedades.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors">Agregar propiedad</a>
        </div>
        @else
        <div class="space-y-3">
            @foreach($propiedadesRecientes as $prop)
            <div class="flex items-center space-x-3 py-2 border-b border-gray-50 last:border-0">
                <div class="w-12 h-9 rounded-lg overflow-hidden flex-shrink-0">
                    <img src="{{ $prop->primary_image_url }}" alt="" class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=100&h=80&fit=crop'">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $prop->titulo }}</p>
                    <p class="text-xs text-gray-500">{{ $prop->barrio }}, {{ $prop->ciudad }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-semibold text-blue-600">{{ $prop->precio_formateado }}</p>
                    @php $estadoColors = ['disponible' => 'green', 'reservado' => 'yellow', 'vendido' => 'red', 'alquilado' => 'blue']; @endphp
                    <span class="text-xs px-1.5 py-0.5 rounded-full bg-{{ $estadoColors[$prop->estado] ?? 'gray' }}-100 text-{{ $estadoColors[$prop->estado] ?? 'gray' }}-700 capitalize">{{ $prop->estado }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
    
    <!-- Consultas recientes -->
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Consultas recientes</h3>
            <a href="{{ route('panel.consultas') }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">Ver todas</a>
        </div>
        @if($consultasRecientes->isEmpty())
        <p class="text-gray-500 text-sm text-center py-8">No hay consultas aún</p>
        @else
        <div class="space-y-3">
            @foreach($consultasRecientes as $consulta)
            <div class="p-3 {{ !$consulta->isRead() ? 'bg-orange-50 border border-orange-100' : 'bg-gray-50' }} rounded-xl">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $consulta->nombre }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $consulta->property->titulo }}</p>
                        <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $consulta->mensaje }}</p>
                    </div>
                    @if(!$consulta->isRead())
                    <span class="bg-orange-400 w-2 h-2 rounded-full mt-1 flex-shrink-0 ml-2"></span>
                    @endif
                </div>
                <p class="text-xs text-gray-400 mt-1">{{ $consulta->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
