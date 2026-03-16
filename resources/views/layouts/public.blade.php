<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'InmoPlus - Propiedades en Uruguay')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .hero-bg { background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 50%, #1a5276 100%); }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
    </style>
</head>
<body class="bg-gray-50 font-sans">

<!-- Navbar -->
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home') }}" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-blue-700">InmoPlus</span>
            </a>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Inicio</a>
                <a href="{{ route('propiedades') }}" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Propiedades</a>
                <a href="{{ route('propiedades') }}?operacion=venta" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Venta</a>
                <a href="{{ route('propiedades') }}?operacion=alquiler" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Alquiler</a>
            </div>
            
            <div class="flex items-center space-x-3">
                @auth
                    @if(auth()->user()->isSuperadmin())
                        <a href="{{ route('admin.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">Dashboard Admin</a>
                    @else
                        <a href="{{ route('panel.dashboard') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">Mi Panel</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-red-600 text-sm font-medium transition-colors">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 text-sm font-medium transition-colors">Iniciar sesión</a>
                    <a href="{{ route('register') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">Registrarse</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<!-- Alerts -->
@if(session('success'))
<div class="bg-green-50 border-l-4 border-green-500 p-4 mx-4 mt-4 rounded-r-lg" x-data="{ show: true }" x-show="show">
    <div class="flex justify-between items-center">
        <p class="text-green-700 font-medium">{{ session('success') }}</p>
        <button @click="show = false" class="text-green-500 hover:text-green-700 ml-4">✕</button>
    </div>
</div>
@endif

@if(session('error'))
<div class="bg-red-50 border-l-4 border-red-500 p-4 mx-4 mt-4 rounded-r-lg">
    <p class="text-red-700 font-medium">{{ session('error') }}</p>
</div>
@endif

@yield('content')

<!-- Footer -->
<footer class="bg-gray-900 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold">InmoPlus</span>
                </div>
                <p class="text-gray-400 text-sm">La plataforma de propiedades más completa de Uruguay. Encontrá tu hogar ideal.</p>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-gray-200">Propiedades</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('propiedades') }}?tipo=casa" class="hover:text-white transition-colors">Casas</a></li>
                    <li><a href="{{ route('propiedades') }}?tipo=apartamento" class="hover:text-white transition-colors">Apartamentos</a></li>
                    <li><a href="{{ route('propiedades') }}?tipo=local" class="hover:text-white transition-colors">Locales comerciales</a></li>
                    <li><a href="{{ route('propiedades') }}?tipo=terreno" class="hover:text-white transition-colors">Terrenos</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-gray-200">Zonas populares</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('propiedades') }}?barrio=Pocitos" class="hover:text-white transition-colors">Pocitos</a></li>
                    <li><a href="{{ route('propiedades') }}?barrio=Carrasco" class="hover:text-white transition-colors">Carrasco</a></li>
                    <li><a href="{{ route('propiedades') }}?barrio=Punta+Carretas" class="hover:text-white transition-colors">Punta Carretas</a></li>
                    <li><a href="{{ route('propiedades') }}?barrio=Cordón" class="hover:text-white transition-colors">Cordón</a></li>
                </ul>
            </div>
            <div>
                <h3 class="font-semibold mb-4 text-gray-200">Inmobiliarias</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Publicar propiedad</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Registrar inmobiliaria</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} InmoPlus — Uruguay. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>

</body>
</html>
