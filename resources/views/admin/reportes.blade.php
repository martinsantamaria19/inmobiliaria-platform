@extends('layouts.admin')

@section('title', 'Reportes')
@section('page-title', 'Reportes y Estadísticas')

@section('content')

<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_propiedades']) }}</p>
        <p class="text-gray-500 text-sm mt-1">Total propiedades</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_inmobiliarias'] }}</p>
        <p class="text-gray-500 text-sm mt-1">Inmobiliarias</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_consultas']) }}</p>
        <p class="text-gray-500 text-sm mt-1">Consultas totales</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 text-center">
        <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_vistas']) }}</p>
        <p class="text-gray-500 text-sm mt-1">Vistas totales</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4">Propiedades más visitadas</h3>
        <div class="space-y-3">
            @foreach($topPropiedades as $i => $prop)
            <div class="flex items-center space-x-3">
                <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ $prop->titulo }}</p>
                    <p class="text-xs text-gray-500">{{ $prop->inmobiliaria->nombre }}</p>
                </div>
                <span class="text-sm font-semibold text-blue-600">{{ number_format($prop->views_count) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
        <h3 class="font-semibold text-gray-900 mb-4">Inmobiliarias con más propiedades</h3>
        <div class="space-y-3">
            @foreach($topInmobiliarias as $i => $inmo)
            <div class="flex items-center space-x-3">
                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $inmo->nombre }}</p>
                    <p class="text-xs text-gray-500">{{ $inmo->ciudad }}</p>
                </div>
                <span class="text-sm font-semibold text-emerald-600">{{ $inmo->properties_count }} props.</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
