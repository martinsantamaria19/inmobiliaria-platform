@extends('layouts.admin')

@section('title', 'Propiedades')
@section('page-title', 'Todas las Propiedades')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-5">
    <form method="GET" class="flex gap-3 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar propiedad..." 
            class="flex-1 min-w-48 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="tipo" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Tipo</option>
            <option value="casa" {{ request('tipo') === 'casa' ? 'selected' : '' }}>Casa</option>
            <option value="apartamento" {{ request('tipo') === 'apartamento' ? 'selected' : '' }}>Apartamento</option>
            <option value="local" {{ request('tipo') === 'local' ? 'selected' : '' }}>Local</option>
            <option value="terreno" {{ request('tipo') === 'terreno' ? 'selected' : '' }}>Terreno</option>
            <option value="oficina" {{ request('tipo') === 'oficina' ? 'selected' : '' }}>Oficina</option>
        </select>
        <select name="estado" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Estado</option>
            <option value="disponible" {{ request('estado') === 'disponible' ? 'selected' : '' }}>Disponible</option>
            <option value="reservado" {{ request('estado') === 'reservado' ? 'selected' : '' }}>Reservado</option>
            <option value="vendido" {{ request('estado') === 'vendido' ? 'selected' : '' }}>Vendido</option>
            <option value="alquilado" {{ request('estado') === 'alquilado' ? 'selected' : '' }}>Alquilado</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">Filtrar</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propiedad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo / Op.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inmobiliaria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vistas</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
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
                            <p class="font-medium text-gray-900 text-sm line-clamp-1">{{ $property->titulo }}</p>
                            <p class="text-xs text-gray-500">{{ $property->barrio }}, {{ $property->ciudad }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="capitalize text-sm text-gray-700">{{ $property->tipo }}</span>
                    <span class="text-gray-400"> / </span>
                    <span class="capitalize text-sm text-gray-700">{{ $property->operacion }}</span>
                </td>
                <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ $property->precio_formateado }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $property->inmobiliaria->nombre }}</td>
                <td class="px-6 py-4">
                    @php
                    $estadoColors = ['disponible' => 'green', 'reservado' => 'yellow', 'vendido' => 'red', 'alquilado' => 'blue'];
                    $color = $estadoColors[$property->estado] ?? 'gray';
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 capitalize">
                        {{ $property->estado }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ number_format($property->views_count) }}</td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        <a href="{{ route('propiedades.show', $property->id) }}" target="_blank"
                            class="text-gray-400 hover:text-blue-600 transition-colors" title="Ver">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.propiedades.destacado', $property->id) }}">
                            @csrf
                            <button type="submit" title="{{ $property->destacado ? 'Quitar destacado' : 'Marcar destacado' }}"
                                class="{{ $property->destacado ? 'text-yellow-500 hover:text-yellow-300' : 'text-gray-300 hover:text-yellow-500' }} transition-colors">
                                <svg class="w-5 h-5" fill="{{ $property->destacado ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-500">No se encontraron propiedades</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $properties->links() }}
    </div>
</div>
@endsection
