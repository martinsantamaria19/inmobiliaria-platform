@extends('layouts.panel')

@section('title', 'Nueva Propiedad')
@section('page-title', 'Nueva Propiedad')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('panel.propiedades.store') }}" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Información básica</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="titulo" value="{{ old('titulo') }}" required
                    placeholder="Ej: Apartamento luminoso en Pocitos con vista al mar"
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 @error('titulo') border-red-400 @enderror">
                @error('titulo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                <textarea name="descripcion" rows="4" required
                    placeholder="Descripción detallada de la propiedad..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none @error('descripcion') border-red-400 @enderror">{{ old('descripcion') }}</textarea>
                @error('descripcion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <select name="tipo" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Seleccioná...</option>
                        <option value="casa" {{ old('tipo') === 'casa' ? 'selected' : '' }}>Casa</option>
                        <option value="apartamento" {{ old('tipo') === 'apartamento' ? 'selected' : '' }}>Apartamento</option>
                        <option value="local" {{ old('tipo') === 'local' ? 'selected' : '' }}>Local</option>
                        <option value="terreno" {{ old('tipo') === 'terreno' ? 'selected' : '' }}>Terreno</option>
                        <option value="oficina" {{ old('tipo') === 'oficina' ? 'selected' : '' }}>Oficina</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operación *</label>
                    <select name="operacion" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Seleccioná...</option>
                        <option value="venta" {{ old('operacion') === 'venta' ? 'selected' : '' }}>Venta</option>
                        <option value="alquiler" {{ old('operacion') === 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                        <option value="ambas" {{ old('operacion') === 'ambas' ? 'selected' : '' }}>Venta y Alquiler</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio venta</label>
                    <input type="number" name="precio_venta" value="{{ old('precio_venta') }}" placeholder="0"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio alquiler/mes</label>
                    <input type="number" name="precio_alquiler" value="{{ old('precio_alquiler') }}" placeholder="0"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Moneda *</label>
                    <select name="moneda" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="USD" {{ old('moneda', 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="UYU" {{ old('moneda') === 'UYU' ? 'selected' : '' }}>UYU</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                    <select name="estado" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="disponible" {{ old('estado', 'disponible') === 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="reservado" {{ old('estado') === 'reservado' ? 'selected' : '' }}>Reservado</option>
                        <option value="vendido" {{ old('estado') === 'vendido' ? 'selected' : '' }}>Vendido</option>
                        <option value="alquilado" {{ old('estado') === 'alquilado' ? 'selected' : '' }}>Alquilado</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Características</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dormitorios *</label>
                    <input type="number" name="dormitorios" value="{{ old('dormitorios', 0) }}" min="0" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Baños *</label>
                    <input type="number" name="banos" value="{{ old('banos', 1) }}" min="1" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sup. total (m²)</label>
                    <input type="number" name="superficie_total" value="{{ old('superficie_total') }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sup. construida (m²)</label>
                    <input type="number" name="superficie_construida" value="{{ old('superficie_construida') }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="flex space-x-6">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="garage" value="1" {{ old('garage') ? 'checked' : '' }} class="rounded text-emerald-600 w-4 h-4">
                    <span class="text-sm text-gray-700">🚗 Garage</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="piscina" value="1" {{ old('piscina') ? 'checked' : '' }} class="rounded text-emerald-600 w-4 h-4">
                    <span class="text-sm text-gray-700">🏊 Piscina</span>
                </label>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Ubicación</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Av. 18 de Julio 1234"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barrio</label>
                    <input type="text" name="barrio" value="{{ old('barrio') }}" placeholder="Pocitos"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', 'Montevideo') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-1">Imágenes</h3>
            <p class="text-gray-500 text-sm mb-3">Una URL por línea. La primera será la imagen principal.</p>
            <textarea name="image_urls" rows="4" placeholder="https://images.unsplash.com/photo-...
https://images.unsplash.com/photo-..."
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none font-mono">{{ old('image_urls') }}</textarea>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-1">Amenities / Características extra</h3>
            <p class="text-gray-500 text-sm mb-3">Una característica por línea.</p>
            <textarea name="features_text" rows="4" placeholder="Portero eléctrico
Ascensor
Seguridad 24hs
Parrillero"
                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('features_text') }}</textarea>
        </div>
        
        <div class="flex space-x-3">
            <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-700 transition-colors">
                Publicar propiedad
            </button>
            <a href="{{ route('panel.propiedades') }}" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
