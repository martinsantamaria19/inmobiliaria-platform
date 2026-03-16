@extends('layouts.public')

@section('title', 'InmoPlus — Propiedades en Uruguay')

@section('content')

<!-- Hero Section -->
<section class="hero-bg text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-10">
        <div class="w-96 h-96 bg-white rounded-full absolute -top-20 -right-20"></div>
        <div class="w-64 h-64 bg-white rounded-full absolute bottom-10 left-20"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-10">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight">
                Encontrá tu propiedad ideal<br>
                <span class="text-blue-300">en Uruguay</span>
            </h1>
            <p class="text-xl text-blue-100 mb-8">Las mejores propiedades en venta y alquiler, directamente de las inmobiliarias más confiables del país.</p>
        </div>
        
        <!-- Search Bar -->
        <div class="bg-white rounded-2xl p-4 shadow-2xl max-w-4xl mx-auto">
            <form action="{{ route('propiedades') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                    <div class="md:col-span-2">
                        <input type="text" name="search" placeholder="Barrio, ciudad o descripción..." 
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700">
                    </div>
                    <div>
                        <select name="tipo" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700">
                            <option value="">Tipo</option>
                            <option value="casa">Casa</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="local">Local</option>
                            <option value="terreno">Terreno</option>
                            <option value="oficina">Oficina</option>
                        </select>
                    </div>
                    <div>
                        <select name="operacion" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-700">
                            <option value="">Operación</option>
                            <option value="venta">Venta</option>
                            <option value="alquiler">Alquiler</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-xl font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span>Buscar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="bg-blue-700 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-3 gap-8 text-center text-white">
            <div>
                <p class="text-3xl font-bold">{{ number_format($totalPropiedades) }}+</p>
                <p class="text-blue-200 text-sm mt-1">Propiedades</p>
            </div>
            <div>
                <p class="text-3xl font-bold">{{ $totalInmobiliarias }}+</p>
                <p class="text-blue-200 text-sm mt-1">Inmobiliarias</p>
            </div>
            <div>
                <p class="text-3xl font-bold">{{ $totalCiudades }}+</p>
                <p class="text-blue-200 text-sm mt-1">Ciudades</p>
            </div>
        </div>
    </div>
</section>

<!-- Tipos de propiedad -->
<section class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Explorá por tipo</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @php
            $tipos = [
                ['tipo' => 'casa', 'label' => 'Casas', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'color' => 'blue'],
                ['tipo' => 'apartamento', 'label' => 'Apartamentos', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'emerald'],
                ['tipo' => 'local', 'label' => 'Locales', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z', 'color' => 'orange'],
                ['tipo' => 'terreno', 'label' => 'Terrenos', 'icon' => 'M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7', 'color' => 'yellow'],
                ['tipo' => 'oficina', 'label' => 'Oficinas', 'icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'purple'],
            ];
            @endphp
            @foreach($tipos as $t)
            <a href="{{ route('propiedades') }}?tipo={{ $t['tipo'] }}" 
                class="bg-{{ $t['color'] }}-50 border border-{{ $t['color'] }}-100 rounded-xl p-5 text-center hover:bg-{{ $t['color'] }}-100 transition-colors group">
                <div class="w-12 h-12 bg-{{ $t['color'] }}-100 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-{{ $t['color'] }}-200 transition-colors">
                    <svg class="w-6 h-6 text-{{ $t['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $t['icon'] }}"/>
                    </svg>
                </div>
                <p class="font-semibold text-gray-800 text-sm">{{ $t['label'] }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Propiedades Destacadas -->
<section class="py-14 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Propiedades destacadas</h2>
                <p class="text-gray-500 mt-1">Las mejores oportunidades del mercado</p>
            </div>
            <a href="{{ route('propiedades') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-1">
                <span>Ver todas</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        
        @if($destacadas->isEmpty())
        <div class="text-center py-16 text-gray-500">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <p class="text-lg font-medium">No hay propiedades destacadas aún</p>
            <a href="{{ route('propiedades') }}" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Ver todas las propiedades</a>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($destacadas as $property)
            <a href="{{ route('propiedades.show', $property->id) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm card-hover border border-gray-100 block">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $property->primary_image_url }}" alt="{{ $property->titulo }}" 
                         class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                         onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'">
                    <div class="absolute top-3 left-3">
                        <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full">⭐ Destacada</span>
                    </div>
                    <div class="absolute top-3 right-3">
                        <span class="bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full capitalize">{{ $property->operacion }}</span>
                    </div>
                </div>
                <div class="p-5">
                    <p class="text-blue-600 font-bold text-xl mb-1">{{ $property->precio_formateado }}</p>
                    <h3 class="font-semibold text-gray-900 mb-2 line-clamp-1">{{ $property->titulo }}</h3>
                    <p class="text-gray-500 text-sm mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $property->barrio ? $property->barrio . ', ' : '' }}{{ $property->ciudad }}
                    </p>
                    <div class="flex items-center space-x-4 text-gray-500 text-sm border-t pt-3">
                        @if($property->dormitorios > 0)
                        <span class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>{{ $property->dormitorios }} dorm.</span>
                        </span>
                        @endif
                        <span class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                            <span>{{ $property->banos }} baño{{ $property->banos > 1 ? 's' : '' }}</span>
                        </span>
                        @if($property->superficie_total)
                        <span class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                            <span>{{ number_format($property->superficie_total) }} m²</span>
                        </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ $property->inmobiliaria->nombre }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

<!-- CTA -->
<section class="py-16 bg-blue-700 text-white text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-4">¿Sos inmobiliaria?</h2>
        <p class="text-blue-100 text-lg mb-8">Publicá tus propiedades en InmoPlus y llegá a miles de compradores e inquilinos en Uruguay.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('register') }}" class="bg-white text-blue-700 px-8 py-3 rounded-xl font-semibold hover:bg-blue-50 transition-colors">Registrarse gratis</a>
            <a href="{{ route('propiedades') }}" class="border-2 border-white text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-600 transition-colors">Ver propiedades</a>
        </div>
    </div>
</section>

@endsection
