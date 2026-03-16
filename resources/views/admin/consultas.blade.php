@extends('layouts.admin')

@section('title', 'Consultas')
@section('page-title', 'Consultas Recibidas')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-5">
    <form method="GET" class="flex gap-3">
        <select name="leida" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todas</option>
            <option value="0" {{ request('leida') === '0' ? 'selected' : '' }}>Sin leer</option>
            <option value="1" {{ request('leida') === '1' ? 'selected' : '' }}>Leídas</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">Filtrar</button>
    </form>
</div>

<div class="space-y-4">
    @forelse($consultas as $consulta)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 {{ !$consulta->isRead() ? 'border-l-4 border-l-orange-400' : '' }}">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-sm">{{ substr($consulta->nombre, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $consulta->nombre }}</p>
                        <div class="flex items-center space-x-3 text-sm text-gray-500">
                            <span>{{ $consulta->email }}</span>
                            @if($consulta->telefono)<span>• {{ $consulta->telefono }}</span>@endif
                        </div>
                    </div>
                    @if(!$consulta->isRead())
                    <span class="bg-orange-100 text-orange-700 text-xs px-2 py-0.5 rounded-full font-medium">Nueva</span>
                    @endif
                </div>
                <div class="ml-12">
                    <p class="text-sm font-medium text-gray-700 mb-1">
                        Propiedad: <a href="{{ route('propiedades.show', $consulta->property_id) }}" class="text-blue-600 hover:underline" target="_blank">{{ $consulta->property->titulo }}</a>
                    </p>
                    <p class="text-gray-600 text-sm bg-gray-50 rounded-xl p-3 mt-2">{{ $consulta->mensaje }}</p>
                    <p class="text-xs text-gray-400 mt-2">{{ $consulta->created_at->format('d/m/Y H:i') }} ({{ $consulta->created_at->diffForHumans() }})</p>
                </div>
            </div>
            @if(!$consulta->isRead())
            <form method="POST" action="{{ route('admin.consultas.leida', $consulta->id) }}" class="ml-4">
                @csrf
                <button type="submit" class="bg-gray-100 text-gray-600 hover:bg-green-100 hover:text-green-700 px-3 py-2 rounded-xl text-xs font-medium transition-colors">
                    Marcar leída
                </button>
            </form>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
        <p class="text-gray-500">No hay consultas</p>
    </div>
    @endforelse
    
    <div class="pt-2">
        {{ $consultas->links() }}
    </div>
</div>
@endsection
