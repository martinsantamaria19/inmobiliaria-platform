@extends('layouts.admin')

@section('title', 'Inmobiliarias')
@section('page-title', 'Gestión de Inmobiliarias')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-5">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar inmobiliaria..." 
            class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <select name="estado" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos</option>
            <option value="aprobada" {{ request('estado') === 'aprobada' ? 'selected' : '' }}>Aprobadas</option>
            <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">Filtrar</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-100">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Inmobiliaria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contacto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ciudad</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propiedades</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($inmobiliarias as $inmo)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-blue-600 font-bold">{{ substr($inmo->nombre, 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $inmo->nombre }}</p>
                            <p class="text-xs text-gray-500">{{ $inmo->user->name }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm text-gray-700">{{ $inmo->email ?? $inmo->user->email }}</p>
                    <p class="text-xs text-gray-500">{{ $inmo->telefono }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $inmo->ciudad ?? '—' }}</td>
                <td class="px-6 py-4 text-sm text-gray-700">{{ $inmo->properties->count() }}</td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inmo->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $inmo->is_approved ? '✓ Aprobada' : '⏳ Pendiente' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end space-x-2">
                        @if(!$inmo->is_approved)
                        <form method="POST" action="{{ route('admin.inmobiliarias.aprobar', $inmo->id) }}">
                            @csrf
                            <button type="submit" class="bg-green-100 text-green-700 hover:bg-green-200 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors">Aprobar</button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.inmobiliarias.rechazar', $inmo->id) }}">
                            @csrf
                            <button type="submit" class="bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" 
                                onclick="return confirm('¿Seguro que querés suspender esta inmobiliaria?')">Suspender</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-500">No se encontraron inmobiliarias</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $inmobiliarias->links() }}
    </div>
</div>
@endsection
