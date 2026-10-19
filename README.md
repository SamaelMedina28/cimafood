# CimaFood

Plataforma de pedidos de comida para los negocios del campus de la UABC, hecha con **Laravel 12**.
Los estudiantes piden de uno o varios negocios en un mismo carrito, y cada negocio administra su menú y sus pedidos desde un panel propio.

## Qué hace

**Para clientes (`/store`)**

- Ver los negocios del campus, su horario (abierto o cerrado en este momento) y su calificación.
- Buscar productos por nombre y explorar por categoría.
- Un carrito con productos de varios negocios: al pagar se crea un pedido por negocio, todos en una sola operación.
- Guardar platillos en favoritos.
- Ver el historial de pedidos y cancelar los que siguen pendientes.
- Reseñar un negocio después de recibir un pedido suyo (una reseña por negocio).

**Para vendedores (`/dashboard`)**

- Resumen de ventas por hora, día o mes, ticket promedio, productos más vendidos y pedidos por atender.
- Alta, edición y baja de negocios (logo, portada, horario, visibilidad en la tienda).
- Alta, edición y baja de productos con foto, precio, stock y disponibilidad.
- Gestión de pedidos: marcar como completado o cancelar. Al cancelar, el stock regresa al negocio.

**Reglas que aplica el sistema**

- No se puede pedir más de lo que hay en stock, ni productos no disponibles, ni de negocios ocultos o cerrados.
- Un vendedor solo puede ver y modificar sus propios negocios, productos y pedidos.
- Un pedido cancelado ya no puede cambiar de estado, así que el stock se devuelve una sola vez.
- Los ingresos del resumen no cuentan pedidos cancelados.

## Stack

| Área | Herramienta |
|------|-------------|
| Backend | Laravel 12, PHP 8.2+ |
| Base de datos | SQLite por defecto (funciona con MySQL cambiando `DB_CONNECTION`) |
| Frontend | Livewire 3, Alpine.js |
| Autenticación | Laravel Jetstream (Fortify) |
| Estilos | Tailwind CSS 3 |

## Instalación

```bash
git clone https://github.com/SamaelMedina28/cimafood.git
cd cimafood

composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate

# Crea la base SQLite, las tablas y los datos de ejemplo
touch database/database.sqlite
php artisan migrate --seed

# Enlace para servir las imágenes subidas
php artisan storage:link

php artisan serve
```

La aplicación queda en `http://127.0.0.1:8000`.

### Usuarios de ejemplo

Todos usan la contraseña `password`.

| Rol | Correo |
|-----|--------|
| Cliente (con pedidos, reseñas y favoritos) | `ana@cliente.com` |
| Vendedor (Burritos El Mexicano, Tacos y Burritos Don Carlos) | `test@example.com` |
| Vendedora (hamburguesas) | `maria@hamburguesas.com` |
| Vendedor (sushi) | `roberto@sushi.com` |

## Pruebas

```bash
php artisan test
```

Cubren el checkout (stock, pedidos por negocio, todo o nada), los cambios de estado de los pedidos, los permisos entre vendedores, las reseñas y la subida de imágenes de productos.

## Licencia

MIT, según `composer.json`.
