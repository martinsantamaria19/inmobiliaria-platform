@extends('layouts.public')

@section('title', 'Propiedades — InmoPlus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ viewMode: 'grid' }">
    
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Propiedades disponibles</h1>
        <p class="text-gray-500 mt-1">{{ $properties->total() }} propiedades encontradas</p>
    </div>
    
    <div class="flex flex-col lg:flex-row gap-6">
        
        <!-- Filtros sidebar -->
        <div class="w-full lg:w-72 flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-20">
                <h2 class="font-semibold text-gray-900 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filtros</span>
                </h2>
                <form method="GET" action="{{ route('propiedades') }}" id="filtros-form">
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Título, barrio..." 
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                        <select name="tipo" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todos</option>
                            <option value="casa" {{ request('tipo') === 'casa' ? 'selected' : '' }}>Casa</option>
                            <option value="apartamento" {{ request('tipo') === 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                            <option value="local" {{ request('tipo') === 'local' ? 'selected' : '' }}>Local</option>
                            <option value="terreno" {{ request('tipo') === 'terreno' ? 'selected' : '' }}>Terreno</option>
                            <option value="oficina" {{ request('tipo') === 'oficina' ? 'selected' : '' }}>Oficina</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Operación</label>
                        <select name="operacion" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todas</option>
                            <option value="venta" {{ request('operacion') === 'venta' ? 'selected' : '' }}>Venta</option>
                            <option value="alquiler" {{ request('operacion') === 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                        <select name="ciudad" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Todas</option>
                            @foreach($ciudades as $ciudad)
                            <option value="{{ $ciudad }}" {{ request('ciudad') === $ciudad ? 'selected' : '' }}>{{ $ciudad }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barrio</label>
                        <input type="text" name="barrio" value="{{ request('barrio') }}" placeholder="Ej: Pocitos" 
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div class="mb-4 grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio mín.</label>
                            <input type="number" name="precio_min" value="{{ request('precio_min') }}" placeholder="0" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio máx.</label>
                            <input type="number" name="precio_max" value="{{ request('precio_max') }}" placeholder="Sin límite" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    
                    <div class="mb-4 grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dorm. mín.</label>
                            <select name="dormitorios" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="1" {{ request('dormitorios') == '1' ? 'selected' : '' }}>1+</option>
                                <option value="2" {{ request('dormitorios') == '2' ? 'selected' : '' }}>2+</option>
                                <option value="3" {{ request('dormitorios') == '3' ? 'selected' : '' }}>3+</option>
                                <option value="4" {{ request('dormitorios') == '4' ? 'selected' : '' }}>4+</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Baños mín.</label>
                            <select name="banos" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="1" {{ request('banos') == '1' ? 'selected' : '' }}>1+</option>
                                <option value="2" {{ request('banos') == '2' ? 'selected' : '' }}>2+</option>
                                <option value="3" {{ request('banos') == '3' ? 'selected' : '' }}>3+</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-4 space-y-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="garage" value="1" {{ request('garage') ? 'checked' : '' }} class="rounded text-blue-600">
                            <span class="text-sm text-gray-700">Con garage</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="piscina" value="1" {{ request('piscina') ? 'checked' : '' }} class="rounded text-blue-600">
                            <span class="text-sm text-gray-700">Con piscina</span>
                        </label>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ordenar por</label>
                        <select name="orden" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="destacados" {{ request('orden', 'destacados') === 'destacados' ? 'selected' : '' }}>Destacados primero</option>
                            <option value="nuevo" {{ request('orden') === 'nuevo' ? 'selected' : '' }}>Más nuevos</option>
                            <option value="precio_asc" {{ request('orden') === 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                            <option value="precio_desc" {{ request('orden') === 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white py-2.5 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors mb-2">
                        Aplicar filtros
                    </button>
                    <a href="{{ route('propiedades') }}" class="block w-full text-center text-gray-500 hover:text-gray-700 text-sm py-2">Limpiar filtros</a>
                </form>
            </div>
        </div>
        
        <!-- Grid de propiedades -->
        <div class="flex-1">
            <!-- Controls -->
            <div class="flex justify-between items-center mb-4">
                <p class="text-sm text-gray-500">Mostrando {{ $properties->firstItem() ?? 0 }}–{{ $properties->lastItem() ?? 0 }} de {{ $properties->total() }}</p>
                <div class="flex items-center space-x-2 bg-white border border-gray-200 rounded-lg p-1">
                    <button @click="viewMode = 'grid'" :class="viewMode === 'grid' ? 'bg-blue-100 text-blue-600' : 'text-gray-400'" class="p-1.5 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    </button>
                    <button @click="viewMode = 'list'" :class="viewMode === 'list' ? 'bg-blue-100 text-blue-600' : 'text-gray-400'" class="p-1.5 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                    </button>
                </div>
            </div>
            
            @if($properties->isEmpty())
            <div class="bg-white rounded-2xl p-16 text-center border border-gray-100">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-xl font-semibold text-gray-700 mb-2">No encontramos propiedades</p>
                <p class="text-gray-500 mb-4">Probá ajustando los filtros de búsqueda</p>
                <a href="{{ route('propiedades') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">Ver todas</a>
            </div>
            @else
            
            <!-- Grid view -->
            <div x-show="viewMode === 'grid'" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach($properties as $property)
                <a href="{{ route('propiedades.show', $property->id) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover block">
                    <div class="relative h-44 overflow-hidden">
                        <img src="{{ $property->primary_image_url }}" alt="{{ $property->titulo }}" 
                             class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'">
                        @if($property->destacado)
                        <div class="absolute top-2 left-2">
                            <span class="bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-0.5 rounded-full">⭐ Destacada</span>
                        </div>
                        @endif
                        <div class="absolute top-2 right-2">
                            <span class="bg-blue-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full capitalize">{{ $property->operacion }}</span>
                        </div>
                    </div>
                    <div class="p-4">
                        <p class="text-blue-600 font-bold text-lg">{{ $property->precio_formateado }}</p>
                        <h3 class="font-semibold text-gray-900 text-sm mt-1 mb-2 line-clamp-1">{{ $property->titulo }}</h3>
                        <p class="text-gray-500 text-xs mb-3 flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $property->barrio ? $property->barrio . ', ' : '' }}{{ $property->ciudad }}
                        </p>
                        <div class="flex items-center space-x-3 text-gray-500 text-xs border-t pt-3">
                            @if($property->dormitorios > 0)<span>🛏 {{ $property->dormitorios }}</span>@endif
                            <span>🚿 {{ $property->banos }}</span>
                            @if($property->superficie_total)<span>📐 {{ number_format($property->superficie_total) }}m²</span>@endif
                            @if($property->garage)<span>🚗</span>@endif
                            @if($property->piscina)<span>🏊</span>@endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- List view -->
            <div x-show="viewMode === 'list'" x-cloak class="space-y-4">
                @foreach($properties as $property)
                <a href="{{ route('propiedades.show', $property->id) }}" class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 card-hover flex block">
                    <div class="w-48 h-36 flex-shrink-0 overflow-hidden">
                        <img src="{{ $property->primary_image_url }}" alt="{{ $property->titulo }}" 
                             class="w-full h-full object-cover"
                             onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'">
                    </div>
                    <div class="p-4 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">{{ $property->titulo }}</h3>
                                <p class="text-gray-500 text-sm flex items-center mb-2">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $property->barrio ? $property->barrio . ', ' : '' }}{{ $property->ciudad }}
                                </p>
                            </div>
                            <p class="text-blue-600 font-bold text-lg ml-4">{{ $property->precio_formateado }}</p>
                        </div>
                        <div class="flex items-center space-x-3 text-gray-500 text-sm">
                            @if($property->dormitorios > 0)<span>🛏 {{ $property->dormitorios }} dorm.</span>@endif
                            <span>🚿 {{ $property->banos }} baño{{ $property->banos > 1 ? 's' : '' }}</span>
                            @if($property->superficie_total)<span>📐 {{ number_format($property->superficie_total) }} m²</span>@endif
                        </div>
                        <p class="text-xs text-gray-400 mt-2">{{ $property->inmobiliaria->nombre }} • {{ ucfirst($property->tipo) }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            
            <!-- Paginación -->
            <div class="mt-8">
                {{ $properties->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.card-hover { transition: transform 0.2s, box-shadow 0.2s; }
.card-hover:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
</style>
@endsection
