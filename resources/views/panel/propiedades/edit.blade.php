@extends('layouts.panel')

@section('title', 'Editar Propiedad')
@section('page-title', 'Editar Propiedad')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('panel.propiedades.update', $property->id) }}" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Información básica</h3>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                <input type="text" name="titulo" value="{{ old('titulo', $property->titulo) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción *</label>
                <textarea name="descripcion" rows="4" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('descripcion', $property->descripcion) }}</textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                    <select name="tipo" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach(['casa', 'apartamento', 'local', 'terreno', 'oficina'] as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo', $property->tipo) === $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Operación *</label>
                    <select name="operacion" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="venta" {{ old('operacion', $property->operacion) === 'venta' ? 'selected' : '' }}>Venta</option>
                        <option value="alquiler" {{ old('operacion', $property->operacion) === 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                        <option value="ambas" {{ old('operacion', $property->operacion) === 'ambas' ? 'selected' : '' }}>Venta y Alquiler</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio venta</label>
                    <input type="number" name="precio_venta" value="{{ old('precio_venta', $property->precio_venta) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio alquiler/mes</label>
                    <input type="number" name="precio_alquiler" value="{{ old('precio_alquiler', $property->precio_alquiler) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Moneda *</label>
                    <select name="moneda" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="USD" {{ old('moneda', $property->moneda) === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="UYU" {{ old('moneda', $property->moneda) === 'UYU' ? 'selected' : '' }}>UYU</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                <select name="estado" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 max-w-xs">
                    @foreach(['disponible', 'reservado', 'vendido', 'alquilado'] as $estado)
                    <option value="{{ $estado }}" {{ old('estado', $property->estado) === $estado ? 'selected' : '' }}>{{ ucfirst($estado) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Características</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dormitorios *</label>
                    <input type="number" name="dormitorios" value="{{ old('dormitorios', $property->dormitorios) }}" min="0" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Baños *</label>
                    <input type="number" name="banos" value="{{ old('banos', $property->banos) }}" min="1" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sup. total (m²)</label>
                    <input type="number" name="superficie_total" value="{{ old('superficie_total', $property->superficie_total) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sup. construida (m²)</label>
                    <input type="number" name="superficie_construida" value="{{ old('superficie_construida', $property->superficie_construida) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="flex space-x-6">
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="garage" value="1" {{ old('garage', $property->garage) ? 'checked' : '' }} class="rounded text-emerald-600 w-4 h-4">
                    <span class="text-sm text-gray-700">🚗 Garage</span>
                </label>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" name="piscina" value="1" {{ old('piscina', $property->piscina) ? 'checked' : '' }} class="rounded text-emerald-600 w-4 h-4">
                    <span class="text-sm text-gray-700">🏊 Piscina</span>
                </label>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Ubicación</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $property->direccion) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Barrio</label>
                    <input type="text" name="barrio" value="{{ old('barrio', $property->barrio) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad *</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $property->ciudad) }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
        </div>
        
        <div class="flex space-x-3">
            <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-700 transition-colors">
                Guardar cambios
            </button>
            <a href="{{ route('panel.propiedades') }}" class="bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-semibold hover:bg-gray-200 transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
