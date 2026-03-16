<?php

namespace Database\Seeders;

use App\Models\Inmobiliaria;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\PropertyFeature;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@inmoplus.uy',
            'password' => Hash::make('Admin123!'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        // Inmobiliarias uruguayas
        $inmobiliariasData = [
            ['nombre' => 'Rivera Propiedades', 'ciudad' => 'Montevideo', 'telefono' => '2900-1234', 'email' => 'info@riverapropiedades.com.uy', 'desc' => 'Más de 20 años en el mercado inmobiliario uruguayo. Especialistas en propiedades residenciales y comerciales en Montevideo.'],
            ['nombre' => 'Del Sur Inmobiliaria', 'ciudad' => 'Montevideo', 'telefono' => '2908-5678', 'email' => 'contacto@delsur.com.uy', 'desc' => 'Tu inmobiliaria de confianza en el sur de Montevideo. Venta y alquiler de propiedades en los mejores barrios.'],
            ['nombre' => 'Capital & Asociados', 'ciudad' => 'Montevideo', 'telefono' => '2915-9012', 'email' => 'ventas@capitalasociados.com.uy', 'desc' => 'Expertos en inversiones inmobiliarias y propiedades premium. Asesoramiento personalizado para compradores e inversores.'],
            ['nombre' => 'Montevideo Homes', 'ciudad' => 'Montevideo', 'telefono' => '2922-3456', 'email' => 'hello@montevideohomes.com.uy', 'desc' => 'La inmobiliaria moderna de Montevideo. Propiedades seleccionadas en los barrios más exclusivos de la capital.'],
            ['nombre' => 'Costa Este Propiedades', 'ciudad' => 'Punta del Este', 'telefono' => '4248-7890', 'email' => 'info@costaeste.com.uy', 'desc' => 'Especialistas en propiedades en la costa uruguaya. Casas, apartamentos y terrenos en Punta del Este y alrededores.'],
            ['nombre' => 'Herrera Bienes Raíces', 'ciudad' => 'Montevideo', 'telefono' => '2930-1234', 'email' => 'info@herrerabr.com.uy', 'desc' => 'Familia Herrera, tres generaciones dedicadas al negocio inmobiliario en Uruguay. Confianza y trayectoria garantizadas.'],
            ['nombre' => 'Parque Inmobiliaria', 'ciudad' => 'Montevideo', 'telefono' => '2937-5678', 'email' => 'parque@inmobiliaria.com.uy', 'desc' => 'Especialistas en la zona norte de Montevideo. Excelentes propiedades en Parque Batlle, Buceo y zonas aledañas.'],
            ['nombre' => 'Premium Properties UY', 'ciudad' => 'Montevideo', 'telefono' => '2944-9012', 'email' => 'premium@propertiesuy.com', 'desc' => 'El mercado de lujo inmobiliario en Uruguay. Propiedades exclusivas para clientes que buscan lo mejor.'],
            ['nombre' => 'InmocentrO', 'ciudad' => 'Montevideo', 'telefono' => '2951-3456', 'email' => 'centro@inmocentro.com.uy', 'desc' => 'Tu inmobiliaria en el centro de Montevideo. Oficinas, locales comerciales y apartamentos en la ciudad vieja y centro.'],
            ['nombre' => 'Sur & Norte Propiedades', 'ciudad' => 'Montevideo', 'telefono' => '2958-7890', 'email' => 'info@surynorte.com.uy', 'desc' => 'Cubrimos todo Montevideo y el interior del país. La inmobiliaria con mayor cobertura geográfica del Uruguay.'],
        ];

        $unsplashPhotos = [
            '1564013799515', '1502672260266', '1570129477492', '1560448075-1280da25db26',
            '1568605114967-8130f3a36994', '1558618666-fcd25c85cd64', '1580587771525-78b9dba3b914',
            '1613490493576-9e9d2c5e4c0b', '1523217582562-09d05f1be7d4', '1512917774080-9991f1c4c750',
            '1600596542815-ffad4c1539a9', '1600585154340-be6161a56a0c', '1545324418-cc1a3fa10c00',
            '1630699144867-37acec5f3a0e', '1583608205776-bfd35f0d9f83', '1493809842364-78817add7ffb',
            '1605276374104-dee2a0ed3cd6', '1484154218962-a197022b5858', '1499793983690-e29da59ef1c2',
            '1518780664697-55e3ad937233',
        ];

        $barrios = [
            'Pocitos', 'Punta Carretas', 'Carrasco', 'Cordón', 'Centro',
            'Malvín', 'Buceo', 'Parque Batlle', 'Tres Cruces', 'Prado',
            'Palermo', 'Aguada', 'Ciudad Vieja', 'Parque Rodó', 'Flor de Maroñas',
        ];

        $tipos = ['casa', 'apartamento', 'local', 'terreno', 'oficina'];
        $operaciones = ['venta', 'alquiler', 'ambas'];
        $features = [
            ['Portero eléctrico', 'Ascensor', 'Seguridad 24hs'],
            ['Parrillero', 'Jardín', 'Quincho'],
            ['Cocina equipada', 'Laundry', 'Depósito'],
            ['Salón de fiestas', 'Gimnasio', 'Piscina climatizada'],
            ['Calefacción central', 'Aire acondicionado', 'Doble vidrio'],
            ['Vista al mar', 'Vista a parque', 'Terraza'],
        ];

        $descripcionesBase = [
            'Hermosa propiedad ubicada en uno de los barrios más cotizados de Montevideo. Excelentes terminaciones, luz natural abundante y distribución inteligente de sus espacios. Ideal para familias que buscan comodidad y calidad de vida en un entorno privilegiado.',
            'Increíble oportunidad en el mercado inmobiliario uruguayo. Esta propiedad combina diseño moderno con funcionalidad, ubicada a pasos de todos los servicios esenciales. Imperdible para quienes buscan inversión segura o vivienda propia.',
            'Propiedad de excelente construcción con materiales de primera calidad. Amplios espacios, terminaciones de lujo y ubicación inmejorable. A metros de centros comerciales, escuelas y parques.',
            'Magnífica propiedad con todo lo que buscás. Distribución moderna, amplias habitaciones, cocina totalmente equipada y espacios exteriores cuidados. No pierdas esta oportunidad única en el mercado.',
            'Propiedad en perfecto estado de mantenimiento, lista para habitar. Excelente iluminación natural, ventilación cruzada y vistas despejadas. Barrio tranquilo, ideal para familias con niños.',
        ];

        foreach ($inmobiliariasData as $idx => $inmoData) {
            $user = User::create([
                'name' => $inmoData['nombre'],
                'email' => $inmoData['email'],
                'password' => Hash::make('Inmo123!'),
                'role' => 'inmobiliaria',
                'is_active' => true,
            ]);

            $inmo = Inmobiliaria::create([
                'user_id' => $user->id,
                'nombre' => $inmoData['nombre'],
                'descripcion' => $inmoData['desc'],
                'telefono' => $inmoData['telefono'],
                'email' => $inmoData['email'],
                'ciudad' => $inmoData['ciudad'],
                'direccion' => 'Av. ' . ['18 de Julio', 'Brasil', 'Italia', 'Rivera', 'Gral. Flores'][array_rand(['18 de Julio', 'Brasil', 'Italia', 'Rivera', 'Gral. Flores'])] . ' ' . rand(100, 3000),
                'is_approved' => true,
            ]);

            // 10 propiedades por inmobiliaria
            for ($i = 0; $i < 10; $i++) {
                $tipo = $tipos[($idx * 10 + $i) % count($tipos)];
                $operacion = $operaciones[($i) % count($operaciones)];
                $barrio = $barrios[($idx + $i) % count($barrios)];
                
                $dormitorios = $tipo === 'terreno' ? 0 : ($tipo === 'local' || $tipo === 'oficina' ? 0 : rand(1, 4));
                $banos = $tipo === 'terreno' ? 0 : rand(1, 3);
                $superficieTotal = rand(50, 500);
                
                $precioVenta = null;
                $precioAlquiler = null;
                $moneda = rand(0, 1) === 0 ? 'USD' : 'UYU';
                
                if ($operacion === 'venta' || $operacion === 'ambas') {
                    if ($tipo === 'terreno') {
                        $precioVenta = $moneda === 'USD' ? rand(30, 200) * 1000 : rand(1500, 8000) * 1000;
                    } elseif ($tipo === 'local' || $tipo === 'oficina') {
                        $precioVenta = $moneda === 'USD' ? rand(80, 500) * 1000 : rand(3000, 20000) * 1000;
                    } elseif ($tipo === 'apartamento') {
                        $precioVenta = $moneda === 'USD' ? rand(60, 400) * 1000 : rand(2500, 15000) * 1000;
                    } else { // casa
                        $precioVenta = $moneda === 'USD' ? rand(100, 800) * 1000 : rand(4000, 30000) * 1000;
                    }
                }
                
                if ($operacion === 'alquiler' || $operacion === 'ambas') {
                    if ($tipo === 'local' || $tipo === 'oficina') {
                        $precioAlquiler = $moneda === 'USD' ? rand(500, 3000) : rand(20000, 100000);
                    } else {
                        $precioAlquiler = $moneda === 'USD' ? rand(400, 2000) : rand(15000, 80000);
                    }
                }

                $titulos = [
                    "casa" => ["Espectacular casa en {$barrio}", "Casa familiar con jardín en {$barrio}", "Casa moderna en {$barrio}", "Amplia casa con garage en {$barrio}"],
                    "apartamento" => ["Apartamento luminoso en {$barrio}", "Moderno apto en {$barrio}", "Apartamento con balcón en {$barrio}", "Apto. reciclado en {$barrio}"],
                    "local" => ["Local comercial en {$barrio}", "Excelente local en planta baja en {$barrio}", "Local ideal para negocio en {$barrio}"],
                    "terreno" => ["Terreno con excelente ubicación en {$barrio}", "Lote ideal para construir en {$barrio}", "Fracción de terreno en {$barrio}"],
                    "oficina" => ["Oficina ejecutiva en {$barrio}", "Suite corporativa en {$barrio}", "Oficina moderna en {$barrio}"],
                ];
                
                $tituloOptions = $titulos[$tipo];
                $titulo = $tituloOptions[array_rand($tituloOptions)];
                
                $property = Property::create([
                    'inmobiliaria_id' => $inmo->id,
                    'titulo' => $titulo,
                    'descripcion' => $descripcionesBase[($idx + $i) % count($descripcionesBase)] . ' En el barrio ' . $barrio . ', a pasos de ' . ['la rambla', 'el parque', 'el shopping', 'la plaza principal', 'avenidas principales'][array_rand(['la rambla', 'el parque', 'el shopping', 'la plaza principal', 'avenidas principales'])] . '.',
                    'tipo' => $tipo,
                    'operacion' => $operacion,
                    'precio_venta' => $precioVenta,
                    'precio_alquiler' => $precioAlquiler,
                    'moneda' => $moneda,
                    'estado' => 'disponible',
                    'dormitorios' => $dormitorios,
                    'banos' => $banos,
                    'superficie_total' => $superficieTotal,
                    'superficie_construida' => $tipo !== 'terreno' ? rand((int)($superficieTotal * 0.6), $superficieTotal) : null,
                    'garage' => rand(0, 1) === 1,
                    'piscina' => rand(0, 4) === 1,
                    'direccion' => 'Calle ' . ['Benito Blanco', 'Ellauri', 'Larrañaga', 'Bulevar Artigas', 'Rivera', '26 de Marzo'][array_rand(['Benito Blanco', 'Ellauri', 'Larrañaga', 'Bulevar Artigas', 'Rivera', '26 de Marzo'])] . ' ' . rand(100, 4000),
                    'barrio' => $barrio,
                    'ciudad' => $inmoData['ciudad'] === 'Punta del Este' ? 'Punta del Este' : 'Montevideo',
                    'latitud' => -34.9 + (rand(-500, 500) / 10000),
                    'longitud' => -56.15 + (rand(-500, 500) / 10000),
                    'destacado' => rand(0, 4) === 0,
                    'views_count' => rand(0, 500),
                ]);

                // Agregar imágenes (2-4 por propiedad)
                $numImages = rand(2, 4);
                for ($j = 0; $j < $numImages; $j++) {
                    $photoId = $unsplashPhotos[($idx * 10 + $i + $j) % count($unsplashPhotos)];
                    PropertyImage::create([
                        'property_id' => $property->id,
                        'url' => "https://images.unsplash.com/photo-{$photoId}?w=800&h=600&fit=crop",
                        'is_primary' => $j === 0,
                        'orden' => $j,
                    ]);
                }

                // Amenities
                $featSet = $features[($idx + $i) % count($features)];
                foreach ($featSet as $feat) {
                    PropertyFeature::create([
                        'property_id' => $property->id,
                        'feature' => $feat,
                    ]);
                }

                // Algunas consultas aleatorias
                if (rand(0, 2) === 0) {
                    $nombres = ['Juan García', 'María González', 'Carlos Rodríguez', 'Ana Martínez', 'Pedro López', 'Laura Fernández'];
                    $nombre = $nombres[array_rand($nombres)];
                    Inquiry::create([
                        'property_id' => $property->id,
                        'nombre' => $nombre,
                        'email' => strtolower(str_replace(' ', '.', $nombre)) . '@gmail.com',
                        'telefono' => '09' . rand(1000000, 9999999),
                        'mensaje' => 'Hola, me interesa la propiedad. ¿Está disponible para visitar? ¿Podría darme más información sobre el precio y condiciones?',
                        'read_at' => rand(0, 1) === 0 ? now()->subDays(rand(1, 10)) : null,
                        'created_at' => now()->subDays(rand(0, 30)),
                    ]);
                }
            }
        }
    }
}
