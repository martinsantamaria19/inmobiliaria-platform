<div align="center">

# 🏡 Inmobiliaria Platform

**Plataforma SaaS multi-tenant para inmobiliarias**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![SQLite](https://img.shields.io/badge/SQLite-003B57?style=for-the-badge&logo=sqlite&logoColor=white)](https://www.sqlite.org)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

*Cada inmobiliaria, su propio panel. Un ecosistema, un superadmin.*

[Ver Demo](#-screenshots) · [Instalación](#-instalación) · [Contribuir](#-contribuir)

</div>

---

## 📋 Índice

- [Descripción](#-descripción)
- [Features](#-features)
- [Tech Stack](#-tech-stack)
- [Arquitectura Multi-Tenant](#-arquitectura-multi-tenant)
- [Roles y Permisos](#-roles-y-permisos)
- [Instalación](#-instalación)
- [Uso](#-uso)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Screenshots](#-screenshots)
- [License](#-license)

---

## 📖 Descripción

**Inmobiliaria Platform** es una aplicación SaaS multi-tenant construida con Laravel que permite a múltiples inmobiliarias gestionar sus propiedades y consultas desde paneles independientes y aislados. Un superadmin supervisa todo el ecosistema desde un panel centralizado.

Diseñado para el mercado **uruguayo**, con flujos de trabajo adaptados a la realidad local del sector inmobiliario.

---

## ✨ Features

### 🌐 Portal Público
- Listado de propiedades con **filtros avanzados**: tipo, operación (venta/alquiler), precio, dormitorios, barrio
- Detalle de propiedad con **galería de imágenes** completa
- **Formulario de consulta** directo desde el detalle de la propiedad
- Interfaz responsive optimizada para móviles

### 🏢 Panel por Inmobiliaria
- ✅ Gestión completa de propiedades (crear, editar, activar/desactivar)
- ✅ Upload de imágenes para propiedades
- ✅ Bandeja de consultas recibidas con historial
- ✅ Gestión del perfil de la inmobiliaria
- ✅ Aislamiento total de datos — cada inmobiliaria ve **solo sus datos**

### 👑 Panel Superadmin
- ✅ Gestión de todas las inmobiliarias del ecosistema
- ✅ Vista global de todas las propiedades
- ✅ Reportes y estadísticas del sistema
- ✅ Activar/desactivar inmobiliarias

### 🔐 Seguridad
- Autenticación con roles diferenciados
- Multi-tenancy a nivel de aplicación
- Middleware de autorización por rol

---

## 🛠 Tech Stack

| Categoría | Tecnología |
|-----------|-----------|
| **Backend** | Laravel 12, PHP 8.2+ |
| **Frontend** | Blade Templates, Tailwind CSS 3, Vite |
| **Base de Datos** | SQLite (portable, ideal para desarrollo) |
| **Auth** | Laravel Breeze |
| **Build** | Vite + Node.js |
| **Testing** | PHPUnit |
| **Dev Tools** | Laravel Pail, Pint, Sail |

---

## 🏗 Arquitectura Multi-Tenant

```
┌─────────────────────────────────────────────────────────────┐
│                        PORTAL PÚBLICO                        │
│            Búsqueda y filtro de propiedades                  │
└─────────────────────────────────────────────────────────────┘
                              │
              ┌───────────────┼───────────────┐
              ▼               ▼               ▼
    ┌─────────────────┐    ┌──────────┐    ┌──────────┐
    │  Panel Superadmin│    │ Panel    │    │ Panel    │
    │  (God mode)      │    │ Inmob. A │    │ Inmob. B │
    │                  │    │          │    │          │
    │  - Todas las     │    │ Solo sus │    │ Solo sus │
    │    inmobiliarias │    │  datos   │    │  datos   │
    │  - Todas las     │    │          │    │          │
    │    propiedades   │    └──────────┘    └──────────┘
    │  - Reportes      │
    └─────────────────┘
```

El aislamiento de datos se maneja a nivel de aplicación mediante **scoping automático** por `inmobiliaria_id` en todos los modelos y queries.

---

## 👥 Roles y Permisos

| Acción | Público | Inmobiliaria | Superadmin |
|--------|:-------:|:------------:|:----------:|
| Ver listado de propiedades | ✅ | ✅ | ✅ |
| Ver detalle de propiedad | ✅ | ✅ | ✅ |
| Enviar consulta | ✅ | ✅ | ✅ |
| Crear/editar propiedades | ❌ | ✅ (propias) | ✅ (todas) |
| Ver consultas recibidas | ❌ | ✅ (propias) | ✅ (todas) |
| Subir imágenes | ❌ | ✅ | ✅ |
| Gestionar perfil inmobiliaria | ❌ | ✅ (propia) | ✅ (todas) |
| Gestionar inmobiliarias | ❌ | ❌ | ✅ |
| Ver reportes globales | ❌ | ❌ | ✅ |
| Activar/desactivar inmobiliarias | ❌ | ❌ | ✅ |

---

## 🚀 Instalación

### Prerrequisitos

- PHP 8.2+
- Composer
- Node.js 18+ y npm
- Git

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/martinsantamaria19/inmobiliaria-platform.git
cd inmobiliaria-platform

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias Node
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Crear la base de datos y correr migraciones
touch database/database.sqlite
php artisan migrate --seed

# 6. Compilar assets
npm run build

# 7. Levantar servidor de desarrollo
php artisan serve
```

### Desarrollo con hot-reload

```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Acceder en: [http://localhost:8000](http://localhost:8000)

### Credenciales por defecto (seeder)

```
Superadmin:
  Email:    admin@admin.com
  Password: password

Inmobiliaria demo:
  Email:    demo@inmobiliaria.com
  Password: password
```

---

## 📖 Uso

### Acceso al Portal Público

Navegar a `/` para ver el listado de propiedades con los filtros disponibles.

### Acceso al Panel de Inmobiliaria

```
/login → panel de gestión de la inmobiliaria
```

Desde el panel podés:
- Agregar y editar propiedades con fotos
- Ver y gestionar las consultas recibidas
- Actualizar el perfil de tu inmobiliaria

### Acceso al Panel Superadmin

```
/login → (con cuenta superadmin)
```

Acceso completo a todas las inmobiliarias, propiedades y reportes del sistema.

---

## 🗂 Estructura del Proyecto

```
inmobiliaria-platform/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PublicController.php      # Portal público
│   │   │   ├── PanelController.php       # Panel inmobiliaria
│   │   │   ├── SuperAdminController.php  # Panel superadmin
│   │   │   └── ProfileController.php    # Perfil de usuario
│   │   └── Middleware/
│   └── Models/
│       ├── User.php                      # Usuario con roles
│       ├── Inmobiliaria.php             # Tenant principal
│       ├── Property.php                 # Propiedades
│       ├── PropertyImage.php            # Imágenes de propiedades
│       ├── PropertyFeature.php          # Características
│       └── Inquiry.php                  # Consultas recibidas
│
├── database/
│   ├── migrations/                      # Esquema de base de datos
│   ├── seeders/                         # Datos de prueba
│   └── database.sqlite                  # Base de datos SQLite
│
├── resources/
│   ├── views/
│   │   ├── public/                      # Vistas del portal público
│   │   ├── panel/                       # Vistas del panel inmobiliaria
│   │   └── superadmin/                  # Vistas del superadmin
│   └── css/ js/                         # Assets fuente
│
├── routes/
│   └── web.php                          # Definición de rutas
│
└── tailwind.config.js                   # Configuración de Tailwind
```

---

## 📸 Screenshots

### Portal Público
> Listado de propiedades con filtros por tipo, operación, precio, dormitorios y barrio.

```
┌──────────────────────────────────────────────────────────────┐
│  🔍 Filtros: [Tipo ▾] [Operación ▾] [Precio ▾] [Dormit. ▾]  │
├────────────┬────────────┬────────────┬────────────────────────┤
│ 🏠          │ 🏠          │ 🏠          │                        │
│ Casa        │ Apto       │ Local      │                        │
│ Pocitos     │ Centro     │ Ciudad     │                        │
│ USD 150,000 │ USD 85,000 │ USD 220,000│                        │
│ 3 dorm.     │ 2 dorm.    │ Comercial  │                        │
└────────────┴────────────┴────────────┴────────────────────────┘
```

### Panel Inmobiliaria
> Dashboard para gestionar propiedades y consultas.

### Panel Superadmin
> Vista global del ecosistema de inmobiliarias.

---

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Si querés mejorar la plataforma:

1. Fork del repositorio
2. Creá tu branch: `git checkout -b feature/nueva-funcionalidad`
3. Commit tus cambios: `git commit -m 'feat: agrego nueva funcionalidad'`
4. Push al branch: `git push origin feature/nueva-funcionalidad`
5. Abrí un Pull Request

### Convención de commits

```
feat:     Nueva funcionalidad
fix:      Corrección de bug
docs:     Documentación
style:    Formato (sin cambios de lógica)
refactor: Refactorización
test:     Tests
chore:    Tareas de mantenimiento
```

---

## 🗺 Roadmap

- [ ] Mapa interactivo de propiedades (Leaflet / Google Maps)
- [ ] Notificaciones por email al recibir consultas
- [ ] Sistema de favoritos para usuarios
- [ ] Exportación de reportes a PDF/Excel
- [ ] API REST para integración con portales externos
- [ ] Panel de métricas avanzado con gráficos
- [ ] Dominio personalizado por inmobiliaria
- [ ] Integración con portales (Gallito, MercadoLibre)

---

## 📄 License

Este proyecto está licenciado bajo la [MIT License](LICENSE).

---

<div align="center">

Hecho con ❤️ en Uruguay 🇺🇾

**[MVD Studio](https://github.com/martinsantamaria19)** · Desarrollo de software, marketing digital y más.

</div>
