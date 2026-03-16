@extends('layouts.public')

@section('title', $property->titulo . ' — InmoPlus')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Breadcrumb -->
    <nav class="mb-6 text-sm text-gray-500 flex items-center space-x-2">
        <a href="{{ route('home') }}" class="hover:text-blue-600">Inicio</a>
        <span>›</span>
        <a href="{{ route('propiedades') }}" class="hover:text-blue-600">Propiedades</a>
        <span>›</span>
        <span class="text-gray-800 font-medium line-clamp-1">{{ $property->titulo }}</span>
    </nav>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Columna principal -->
        <div class="lg:col-span-2">
            
            <!-- Galería de imágenes -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 mb-6" 
                 x-data="{ activeImg: '{{ $property->primary_image_url }}' }">
                <div class="relative h-80 md:h-96">
                    <img :src="activeImg" alt="{{ $property->titulo }}" class="w-full h-full object-cover"
                         onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'">
                    @if($property->destacado)
                    <div class="absolute top-4 left-4">
                        <span class="bg-yellow-400 text-yellow-900 font-bold px-3 py-1 rounded-full text-sm">⭐ Destacada</span>
                    </div>
                    @endif
                    <div class="absolute top-4 right-4">
                        <span class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold capitalize">{{ $property->operacion }}</span>
                    </div>
                </div>
                @if($property->images->count() > 1)
                <div class="flex space-x-2 p-3 overflow-x-auto">
                    @foreach($property->images as $img)
                    <button @click="activeImg = '{{ $img->url }}'" class="flex-shrink-0 w-20 h-16 rounded-lg overflow-hidden border-2 border-transparent hover:border-blue-400 transition-colors">
                        <img src="{{ $img->url }}" alt="Foto" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
            
            <!-- Detalles -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="flex items-center space-x-2 mb-2">
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-full capitalize">{{ $property->tipo }}</span>
                            <span class="bg-{{ $property->estado === 'disponible' ? 'green' : 'red' }}-100 text-{{ $property->estado === 'disponible' ? 'green' : 'red' }}-700 text-xs px-2 py-1 rounded-full capitalize">{{ $property->estado }}</span>
                        </div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $property->titulo }}</h1>
                        <p class="text-gray-500 mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            {{ $property->direccion ? $property->direccion . ' — ' : '' }}{{ $property->barrio ? $property->barrio . ', ' : '' }}{{ $property->ciudad }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if($property->precio_venta)
                        <p class="text-gray-500 text-sm">Venta</p>
                        <p class="text-blue-600 font-bold text-2xl">{{ $property->moneda === 'USD' ? 'USD ' : '$' }}{{ number_format($property->precio_venta, 0, ',', '.') }}</p>
                        @endif
                        @if($property->precio_alquiler)
                        <p class="text-gray-500 text-sm">Alquiler</p>
                        <p class="text-emerald-600 font-bold text-xl">{{ $property->moneda === 'USD' ? 'USD ' : '$' }}{{ number_format($property->precio_alquiler, 0, ',', '.') }}/mes</p>
                        @endif
                    </div>
                </div>
                
                <!-- Stats rápidos -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-4 border-y border-gray-100 my-4">
                    @if($property->dormitorios > 0)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ $property->dormitorios }}</p>
                        <p class="text-gray-500 text-sm">Dormitorios</p>
                    </div>
                    @endif
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ $property->banos }}</p>
                        <p class="text-gray-500 text-sm">Baños</p>
                    </div>
                    @if($property->superficie_total)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($property->superficie_total) }}</p>
                        <p class="text-gray-500 text-sm">m² totales</p>
                    </div>
                    @endif
                    @if($property->superficie_construida)
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($property->superficie_construida) }}</p>
                        <p class="text-gray-500 text-sm">m² construidos</p>
                    </div>
                    @endif
                </div>
                
                <!-- Descripción -->
                <div class="mb-4">
                    <h2 class="font-semibold text-gray-900 mb-2">Descripción</h2>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $property->descripcion }}</p>
                </div>
                
                <!-- Características -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4">
                    @if($property->garage)
                    <div class="flex items-center space-x-2 text-gray-600 text-sm">
                        <span class="text-lg">🚗</span><span>Garage</span>
                    </div>
                    @endif
                    @if($property->piscina)
                    <div class="flex items-center space-x-2 text-gray-600 text-sm">
                        <span class="text-lg">🏊</span><span>Piscina</span>
                    </div>
                    @endif
                    @foreach($property->features as $feature)
                    <div class="flex items-center space-x-2 text-gray-600 text-sm">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ $feature->feature }}</span>
                    </div>
                    @endforeach
                </div>
                
                <p class="text-xs text-gray-400 mt-4 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    {{ number_format($property->views_count) }} vistas
                </p>
            </div>
            
            <!-- Mapa placeholder -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 mb-6">
                <h2 class="font-semibold text-gray-900 mb-4">Ubicación</h2>
                <div class="bg-gray-100 rounded-xl h-48 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <p class="font-medium">{{ $property->barrio ? $property->barrio . ', ' : '' }}{{ $property->ciudad }}</p>
                        @if($property->direccion)<p class="text-sm">{{ $property->direccion }}</p>@endif
                    </div>
                </div>
            </div>
            
            <!-- Propiedades similares -->
            @if($similares->count() > 0)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h2 class="font-semibold text-gray-900 mb-4">Propiedades similares</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($similares as $similar)
                    <a href="{{ route('propiedades.show', $similar->id) }}" class="block rounded-xl overflow-hidden border border-gray-100 hover:border-blue-300 transition-colors">
                        <div class="h-32 overflow-hidden">
                            <img src="{{ $similar->primary_image_url }}" alt="{{ $similar->titulo }}" class="w-full h-full object-cover"
                                 onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop'">
                        </div>
                        <div class="p-3">
                            <p class="text-blue-600 font-bold text-sm">{{ $similar->precio_formateado }}</p>
                            <p class="text-gray-800 text-sm font-medium line-clamp-1 mt-0.5">{{ $similar->titulo }}</p>
                            <p class="text-gray-500 text-xs">{{ $similar->barrio }}, {{ $similar->ciudad }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar derecho -->
        <div class="space-y-5">
            
            <!-- Info inmobiliaria -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-4">Publicado por</h3>
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        @if($property->inmobiliaria->logo)
                        <img src="{{ $property->inmobiliaria->logo }}" alt="{{ $property->inmobiliaria->nombre }}" class="w-full h-full object-cover rounded-xl">
                        @else
                        <span class="text-blue-600 font-bold text-xl">{{ substr($property->inmobiliaria->nombre, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $property->inmobiliaria->nombre }}</p>
                        @if($property->inmobiliaria->ciudad)
                        <p class="text-gray-500 text-sm">{{ $property->inmobiliaria->ciudad }}</p>
                        @endif
                    </div>
                </div>
                @if($property->inmobiliaria->telefono)
                <a href="tel:{{ $property->inmobiliaria->telefono }}" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors text-sm mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    <span>{{ $property->inmobiliaria->telefono }}</span>
                </a>
                @endif
            </div>
            
            <!-- Formulario de consulta -->
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-900 mb-4">Consultar sobre esta propiedad</h3>
                
                @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <p class="text-green-700 text-sm font-medium">{{ session('success') }}</p>
                </div>
                @endif
                
                <form action="{{ route('propiedades.consulta', $property->id) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <input type="text" name="nombre" placeholder="Tu nombre *" required
                            value="{{ old('nombre') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nombre') border-red-400 @enderror">
                        @error('nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Tu email *" required
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <input type="tel" name="telefono" placeholder="Tu teléfono"
                            value="{{ old('telefono') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <textarea name="mensaje" placeholder="Tu mensaje *" required rows="4"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none @error('mensaje') border-red-400 @enderror">{{ old('mensaje', 'Hola, me interesa la propiedad "' . $property->titulo . '". ¿Podría darme más información?') }}</textarea>
                        @error('mensaje')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition-colors">
                        Enviar consulta
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
