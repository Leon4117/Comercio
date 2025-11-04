# Dashboard de Festivando - Panel de Proveedores

Este proyecto contiene una plantilla de dashboard para el panel de proveedores de Festivando, creada con Laravel y Tailwind CSS.

## 🎨 Características del Dashboard

- **Diseño Responsivo**: Adaptado para desktop y móvil
- **Sidebar de Navegación**: Con menú completo de opciones
- **Tarjetas de Estadísticas**: Métricas importantes del negocio
- **Tabla de Pedidos**: Lista dinámica con estados y acciones
- **Estilo Moderno**: Usando Tailwind CSS 4.0

## 📁 Archivos Creados

### Vistas
- `resources/views/layouts/app.blade.php` - Layout principal
- `resources/views/dashboard.blade.php` - Vista del dashboard

### Controlador
- `app/Http/Controllers/DashboardController.php` - Lógica del dashboard

### Rutas
- `routes/web.php` - Ruta `/dashboard` agregada

## 🚀 Cómo usar

1. **Iniciar el servidor de desarrollo:**
   ```bash
   php artisan serve
   ```

2. **Compilar los assets (si es necesario):**
   ```bash
   npm run dev
   ```

3. **Visitar el dashboard:**
   - Abrir navegador en: `http://localhost:8000/dashboard`

## 🎯 Funcionalidades Implementadas

### Sidebar
- ✅ Logo y branding "Festivando"
- ✅ Navegación principal con iconos
- ✅ Estado activo en "Administrar Pedidos"
- ✅ Sección de Chats (estructura)
- ✅ Botón de cerrar sesión

### Header
- ✅ Saludo personalizado "Hola, Dulce Fiesta"
- ✅ Avatar del usuario
- ✅ Icono de notificaciones

### Tarjetas de Estadísticas
- ✅ Pedidos Nuevos (4)
- ✅ Pendientes de Cotizar (2)
- ✅ Ingresos Confirmados ($12,500 MXN)
- ✅ Calificación (4.8 ⭐)

### Tabla de Pedidos
- ✅ Pestañas: Pendientes, Confirmados, Historial
- ✅ Columnas: Cliente, Servicio, Fecha, Estado, Acciones
- ✅ Estados con colores: NUEVO (azul), COTIZANDO (amarillo), URGENTE (rojo)
- ✅ Botones de acción dinámicos
- ✅ Paginación

## 🎨 Colores y Estilos

- **Sidebar**: Fondo slate-800 (gris oscuro)
- **Activo**: Azul (blue-600)
- **Estados**: 
  - Nuevo: Azul (blue-100/800)
  - Cotizando: Amarillo (yellow-100/800)
  - Urgente: Rojo (red-100/800)
- **Tarjetas**: Fondo blanco con bordes sutiles

## 📱 Responsive Design

- **Desktop**: Layout de 2 columnas (sidebar + contenido)
- **Mobile**: Sidebar colapsable (estructura preparada)
- **Tarjetas**: Grid responsivo (1-4 columnas según pantalla)

## 🔧 Personalización

### Cambiar datos del dashboard
Edita el archivo `app/Http/Controllers/DashboardController.php`:

```php
$stats = [
    'pedidos_nuevos' => 4,
    'pendientes_cotizar' => 2,
    'ingresos_confirmados' => 12500,
    'calificacion' => 4.8
];
```

### Agregar más pedidos
Modifica el array `$pedidos` en el mismo controlador.

### Personalizar colores
Los colores están definidos usando clases de Tailwind CSS en las vistas.

## 🎉 Resultado

El dashboard replica fielmente el diseño mostrado en la imagen original, incluyendo:
- Layout exacto con sidebar y contenido principal
- Colores y tipografía consistentes
- Iconos y elementos visuales
- Datos dinámicos desde el controlador
- Estructura preparada para futuras funcionalidades

¡Tu dashboard está listo para usar! 🚀
