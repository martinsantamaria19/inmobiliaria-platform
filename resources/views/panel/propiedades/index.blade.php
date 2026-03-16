@extends('layouts.panel')

@section('title', 'Mis Propiedades')
@section('page-title', 'Mis Propiedades')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-4">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar..." 
            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <select name="estado" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Todos</option>
            <option value="disponible" {{ request('estado') === 'disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="reservado" {{ request('estado') === 'reservado' ? 'selected' : '' }}>Reservado</option>
            <option value="vendido" {{ request('estado') === 'vendido' ? 'selected' : '' }}>Vendido</option>
            <option value="alquilado" {{ request('estado') === 'alquilado' ? 'selected' : '' }}>Alquilado</option>
        </select>
        <button type="submit" class="bg-emerald-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-emerald-700 transition-colors">Filtrar</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Propiedad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vistas</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($properties as $property)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-9 rounded-lg overflow-hidden flex-shrink-0">
                            <img src="{{ $property->primary_image_url }}" alt="" class="w-full h-full object-cover"
                                 onerror="this.src='https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=100&h=80&fit=crop'">
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $property->titulo }}</p>
                            <p class="text-xs text-gray-500">{{ $property->barrio }}, {{ $property->ciudad }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700 capitalize">{{ $property->tipo }}</td>
                <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $property->precio_formateado }}</td>
                <td class="px-6 py-4">
                    @php $estadoColors = ['disponible' => 'green', 'reservado' => 'yellow', 'vendido' => 'red', 'alquilado' => 'blue']; @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $estadoColors[$property->estado] ?? 'gray' }}-100 text-{{ $estadoColors[$property->estado] ?? 'gray' }}-800 capitalize">
                        {{ $property->estado }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($property->views_count) }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="{{ route('propiedades.show', $property->id) }}" target="_blank" 
                            class="text-gray-400 hover:text-blue-600 transition-colors" title="Ver">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        <a href="{{ route('panel.propiedades.edit', $property->id) }}" 
                            class="text-gray-400 hover:text-emerald-600 transition-colors" title="Editar">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('panel.propiedades.destroy', $property->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-600 transition-colors" title="Eliminar"
                                onclick="return confirm('¿Eliminar esta propiedad?')">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <p class="text-gray-500 mb-3">No tenés propiedades aún</p>
                    <a href="{{ route('panel.propiedades.create') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors">Agregar primera propiedad</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $properties->links() }}
    </div>
</div>
@endsection
