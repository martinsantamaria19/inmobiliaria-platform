@extends('layouts.panel')

@section('title', 'Consultas')
@section('page-title', 'Mis Consultas')

@section('content')
<div class="space-y-4">
    @forelse($consultas as $consulta)
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 {{ !$consulta->isRead() ? 'border-l-4 border-l-orange-400' : '' }}">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-2">
                    <div class="w-9 h-9 bg-emerald-100 rounded-full flex items-center justify-center">
                        <span class="text-emerald-600 font-bold text-sm">{{ substr($consulta->nombre, 0, 1) }}</span>
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
                    <p class="text-xs text-gray-400 mt-2">{{ $consulta->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @if(!$consulta->isRead())
            <form method="POST" action="{{ route('panel.consultas.leida', $consulta->id) }}" class="ml-4">
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
        <p class="text-gray-500">No hay consultas aún</p>
    </div>
    @endforelse
    
    <div class="pt-2">{{ $consultas->links() }}</div>
</div>
@endsection
