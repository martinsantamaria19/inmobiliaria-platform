@extends('layouts.panel')

@section('title', 'Mi Perfil')
@section('page-title', 'Perfil de la Inmobiliaria')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('panel.perfil.update') }}" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la inmobiliaria *</label>
                <input type="text" name="nombre" value="{{ old('nombre', $inmo->nombre) }}" required
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('nombre')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none">{{ old('descripcion', $inmo->descripcion) }}</textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $inmo->telefono) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email comercial</label>
                    <input type="email" name="email" value="{{ old('email', $inmo->email) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $inmo->direccion) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $inmo->ciudad) }}"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sitio web</label>
                <input type="url" name="sitio_web" value="{{ old('sitio_web', $inmo->sitio_web) }}" placeholder="https://..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Logo (URL)</label>
                <input type="url" name="logo" value="{{ old('logo', $inmo->logo) }}" placeholder="https://..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>
        
        <button type="submit" class="bg-emerald-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-emerald-700 transition-colors">
            Guardar cambios
        </button>
    </form>
</div>
@endsection
