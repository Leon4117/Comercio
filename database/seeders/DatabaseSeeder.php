<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear categorías primero
        $categories = [
            [
                'name' => 'Decoración',
                'price' => 150.00,
                'description' => 'Servicios de decoración para eventos',
                'image' => 'decoracion.jpg',
                'urgent_available' => true,
            ],
            [
                'name' => 'Catering',
                'price' => 250.00,
                'description' => 'Servicios de comida y bebida',
                'image' => 'catering.jpg',
                'urgent_available' => true,
            ],
            [
                'name' => 'Fotografía',
                'price' => 300.00,
                'description' => 'Servicios de fotografía profesional',
                'image' => 'fotografia.jpg',
                'urgent_available' => false,
            ],
            [
                'name' => 'Música',
                'price' => 200.00,
                'description' => 'DJ y servicios musicales',
                'image' => 'musica.jpg',
                'urgent_available' => true,
            ],
            [
                'name' => 'Pastelería',
                'price' => 100.00,
                'description' => 'Pasteles y postres personalizados',
                'image' => 'pasteleria.jpg',
                'urgent_available' => true,
            ],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }

        // Crear usuarios de ejemplo
        $admin = User::factory()->create([
            'name' => 'Administrador Principal',
            'email' => 'admin@festivando.com',
            'password' => bcrypt('admin123'),
            'phone' => '+52 55 1234 5678',
            'role' => 'admin',
            'approved' => true,
            'email_verified_at' => now(),
        ]);

        // Crear usuarios proveedores de ejemplo
        $supplier1 = User::factory()->create([
            'name' => 'Dulce Fiesta',
            'email' => 'dulcefiesta@festivando.com',
            'password' => bcrypt('password123'),
            'phone' => '+52 55 9876 5432',
            'role' => 'supplier',
            'approved' => true,
            'email_verified_at' => now(),
        ]);

        $supplier2 = User::factory()->create([
            'name' => 'Decoraciones Mágicas',
            'email' => 'decoraciones@festivando.com',
            'password' => bcrypt('password123'),
            'phone' => '+52 55 5555 1234',
            'role' => 'supplier',
            'approved' => true,
            'email_verified_at' => now(),
        ]);

        $supplier3 = User::factory()->create([
            'name' => 'Foto Perfect',
            'email' => 'fotoperfect@festivando.com',
            'password' => bcrypt('password123'),
            'phone' => '+52 55 7777 8888',
            'role' => 'supplier',
            'approved' => false,
            'email_verified_at' => now(),
        ]);

        // Crear usuarios clientes de ejemplo
        $client1 = User::factory()->create([
            'name' => 'María González',
            'email' => 'maria@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+52 55 1111 2222',
            'role' => 'client',
            'approved' => true,
            'email_verified_at' => now(),
        ]);

        $client2 = User::factory()->create([
            'name' => 'Carlos Rodríguez',
            'email' => 'carlos@example.com',
            'password' => bcrypt('password123'),
            'phone' => '+52 55 3333 4444',
            'role' => 'client',
            'approved' => true,
            'email_verified_at' => now(),
        ]);

        // Crear registros de proveedores en la tabla suppliers
        \App\Models\Supplier::create([
            'user_id' => $supplier1->id,
            'category_id' => 1, // Asumiendo que existe la categoría 1
            'location' => 'Ciudad de México, CDMX',
            'price_range' => '$2,000 - $5,000',
            'description' => 'Especialistas en postres y dulces para eventos especiales. Creamos experiencias dulces únicas.',
            'documents' => [],
            'identification_photo' => 'supplier-identifications/sample1.jpg',
            'status' => 'approved',
        ]);

        \App\Models\Supplier::create([
            'user_id' => $supplier2->id,
            'category_id' => 2, // Asumiendo que existe la categoría 2
            'location' => 'Guadalajara, Jalisco',
            'price_range' => '$5,000 - $10,000',
            'description' => 'Decoraciones temáticas y ambientación para todo tipo de eventos. Hacemos realidad tus sueños.',
            'documents' => [],
            'identification_photo' => 'supplier-identifications/sample2.jpg',
            'status' => 'approved',
        ]);

        \App\Models\Supplier::create([
            'user_id' => $supplier3->id,
            'category_id' => 3, // Asumiendo que existe la categoría 3
            'location' => 'Monterrey, Nuevo León',
            'price_range' => '$10,000 - $25,000',
            'description' => 'Fotografía profesional para bodas, quinceañeras y eventos corporativos.',
            'documents' => [],
            'identification_photo' => 'supplier-identifications/sample3.jpg',
            'status' => 'pending',
        ]);

        // Crear un proveedor rechazado para probar la funcionalidad
        $rejectedSupplier = User::factory()->create([
            'name' => 'Servicios Rechazados S.A.',
            'email' => 'rechazado@festivando.com',
            'password' => bcrypt('password123'),
            'phone' => '+52 55 9999 0000',
            'role' => 'supplier',
            'approved' => false,
            'email_verified_at' => now(),
        ]);

        \App\Models\Supplier::create([
            'user_id' => $rejectedSupplier->id,
            'category_id' => 1,
            'location' => 'Puebla, Puebla',
            'price_range' => '$1,000 - $3,000',
            'description' => 'Servicios de catering con documentación incompleta.',
            'documents' => [],
            'identification_photo' => 'supplier-identifications/sample4.jpg',
            'status' => 'rejected',
            'rejection_reason' => 'Los documentos proporcionados no son legibles y la identificación oficial está vencida. Por favor, proporciona documentos más claros y una identificación vigente.',
        ]);

        // Crear pedidos de ejemplo
        \App\Models\Order::create([
            'supplier_id' => 1, // Dulce Fiesta
            'user_id' => $client1->id,
            'event_date' => now()->addDays(15),
            'quote_price' => 2500.00,
            'status' => 'confirmed',
            'quote_price_final' => 2500.00,
            'urgent' => false,
            'chat_link' => 'https://wa.me/525555551234',
        ]);

        \App\Models\Order::create([
            'supplier_id' => 1, // Dulce Fiesta
            'user_id' => $client2->id,
            'event_date' => now()->addDays(8),
            'quote_price' => 1800.00,
            'status' => 'confirmed',
            'quote_price_final' => 2100.00,
            'urgent' => true,
            'urgent_price' => 300.00,
            'chat_link' => 'https://wa.me/525555551234',
        ]);

        \App\Models\Order::create([
            'supplier_id' => 2, // Decoraciones Mágicas
            'user_id' => $client1->id,
            'event_date' => now()->addDays(20),
            'quote_price' => 4500.00,
            'status' => 'confirmed',
            'quote_price_final' => 4500.00,
            'urgent' => false,
            'chat_link' => 'https://wa.me/525555551234',
        ]);

        \App\Models\Order::create([
            'supplier_id' => 1, // Dulce Fiesta
            'user_id' => $client2->id,
            'event_date' => now()->subDays(5),
            'quote_price' => 3200.00,
            'status' => 'completed',
            'quote_price_final' => 3200.00,
            'urgent' => false,
            'chat_link' => 'https://wa.me/525555551234',
        ]);

        \App\Models\Order::create([
            'supplier_id' => 1, // Dulce Fiesta
            'user_id' => $client1->id,
            'event_date' => now()->addDays(3),
            'quote_price' => 1500.00,
            'status' => 'quoting',
            'urgent' => false,
            'chat_link' => 'https://wa.me/525555551234',
        ]);

        // Crear servicios de ejemplo
        \App\Models\Service::create([
            'supplier_id' => 1, // Dulce Fiesta
            'name' => 'Pastel de Boda Clásico (3 pisos)',
            'base_price' => 2500.00,
            'description' => 'Hermoso pastel de 3 pisos para boda, incluye decoración con flores naturales, sabores a elegir (vainilla, chocolate, fresa), servicio de montaje en el lugar del evento. Capacidad para 80-100 personas.',
            'main_image' => null,
            'portfolio_images' => [],
            'urgent_available' => true,
            'urgent_price_extra' => 500.00,
            'active' => true,
        ]);

        \App\Models\Service::create([
            'supplier_id' => 1, // Dulce Fiesta
            'name' => 'Mesa de Postres Premium',
            'base_price' => 1800.00,
            'description' => 'Mesa de postres variados incluyendo cupcakes, macarons, cake pops, mini tartas y dulces gourmet. Decoración temática incluida, capacidad para 50 personas. Montaje y desmontaje incluido.',
            'main_image' => null,
            'portfolio_images' => [],
            'urgent_available' => true,
            'urgent_price_extra' => 300.00,
            'active' => true,
        ]);

        \App\Models\Service::create([
            'supplier_id' => 2, // Decoraciones Mágicas
            'name' => 'Decoración Completa de Quinceañera',
            'base_price' => 4500.00,
            'description' => 'Decoración completa para fiesta de 15 años incluyendo: arco principal, centros de mesa, iluminación LED, telas y flores. Montaje 4 horas antes del evento, desmontaje incluido. Capacidad para salón de 150 personas.',
            'main_image' => null,
            'portfolio_images' => [],
            'urgent_available' => false,
            'urgent_price_extra' => null,
            'active' => true,
        ]);

        \App\Models\Service::create([
            'supplier_id' => 2, // Decoraciones Mágicas
            'name' => 'Backdrop Fotográfico Personalizado',
            'base_price' => 800.00,
            'description' => 'Backdrop personalizado para sesión de fotos con temática a elegir. Incluye estructura, decoración, props básicos y iluminación. Ideal para bodas, cumpleaños o eventos corporativos.',
            'main_image' => null,
            'portfolio_images' => [],
            'urgent_available' => true,
            'urgent_price_extra' => 200.00,
            'active' => true,
        ]);

        // Crear eventos de ejemplo para clientes
        \App\Models\Event::create([
            'user_id' => $client1->id,
            'name' => 'Cumpleaños de Diego (5 años)',
            'description' => 'Fiesta infantil con temática de superhéroes para celebrar el cumpleaños de Diego.',
            'event_date' => now()->addDays(30),
            'location' => 'San Luis Potosí, S.L.P.',
            'budget' => 5000.00,
            'guests_count' => 25,
            'status' => 'planning',
        ]);

        \App\Models\Event::create([
            'user_id' => $client1->id,
            'name' => 'Boda de Mis Padres (25 Aniv.)',
            'description' => 'Celebración de bodas de plata con familia y amigos cercanos.',
            'event_date' => now()->addDays(60),
            'location' => 'San Luis Potosí, S.L.P.',
            'budget' => 15000.00,
            'guests_count' => 80,
            'status' => 'planning',
        ]);

        \App\Models\Event::create([
            'user_id' => $client2->id,
            'name' => 'Graduación Prepa 2026',
            'description' => 'Fiesta de graduación de preparatoria.',
            'event_date' => now()->addDays(120),
            'location' => 'San Luis Potosí, S.L.P.',
            'budget' => 8000.00,
            'guests_count' => 50,
            'status' => 'planning',
        ]);

        // Crear servicios solicitados para eventos
        \App\Models\EventService::create([
            'event_id' => 1, // Cumpleaños de Diego
            'service_id' => 1, // Pastel de Boda Clásico (adaptado)
            'supplier_id' => 1, // Dulce Fiesta
            'quoted_price' => 1200.00,
            'status' => 'confirmed',
            'urgent' => false,
            'notes' => 'Pastel temático de superhéroes, 3 pisos para 25 personas',
            'chat_link' => 'https://wa.me/525555551234',
        ]);

        \App\Models\EventService::create([
            'event_id' => 1, // Cumpleaños de Diego
            'service_id' => 4, // Backdrop Fotográfico
            'supplier_id' => 2, // Decoraciones Mágicas
            'quoted_price' => 900.00,
            'status' => 'quoted',
            'urgent' => false,
            'notes' => 'Backdrop con temática de superhéroes para fotos',
            'chat_link' => 'https://wa.me/525555554321',
        ]);

        \App\Models\EventService::create([
            'event_id' => 2, // Boda de Padres
            'service_id' => 2, // Mesa de Postres Premium
            'supplier_id' => 1, // Dulce Fiesta
            'quoted_price' => 2200.00,
            'status' => 'delivered',
            'urgent' => false,
            'notes' => 'Mesa de postres elegante para celebración de aniversario',
            'chat_link' => 'https://wa.me/525555551234',
        ]);

    }
}
