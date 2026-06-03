# CimaFood

![CimaFood Logo](https://raw.githubusercontent.com/SamaelMedina28/cimafood/main/public/logo.png)

## 📖 Descripción del proyecto

**CimaFood** es una plataforma web de pedidos de alimentos desarrollada con **Laravel 12** que permite a los usuarios explorar menús, crear órdenes y gestionar entregas en tiempo real. El objetivo es ofrecer una experiencia de compra rápida, segura y amigable tanto para clientes como para negocios.

## ✨ Características principales

- **Catálogo de negocios y platos** con filtros por categoría, precio y valoración.
- **Carrito de compras** persistente y cálculo automático de impuestos y propinas.
- **Panel de administración** para gestionar menús, pedidos y usuarios.

## 🛠️ Stack tecnológico

| Área | Herramienta |
|------|-------------|
| Backend | **Laravel 12**, PHP 8.2, MySQL 8 |
| Frontend | **Livewire 3**, **Alpine.js 4** |
| Auth | **Laravel Jetstream** |
| UI | **Tailwind CSS 4** |

## Instalación rápida

```bash
# Clonar el repositorio
git clone https://github.com/SamaelMedina28/cimafood.git
cd cimafood

# Instalar dependencias
autorun=0 composer install
npm install && npm run build

# Copiar archivo de entorno
touch .env
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Migrar base de datos
php artisan migrate --seed

# Levantar servidor de desarrollo
php artisan serve
```

El proyecto quedará disponible en `http://127.0.0.1:8000`.

## 📦 Configuración de variables de entorno

| Variable | Descripción | Valor por defecto |
|----------|------------|-------------------|
| `APP_NAME` | Nombre de la aplicación | CimaFood |
| `APP_ENV` | Entorno (local, production) | local |
| `APP_KEY` | Clave de cifrado (generada) | `` |
| `DB_CONNECTION` | Driver de BD | mysql |
| `DB_HOST` | Host de MySQL | 127.0.0.1 |
| `DB_DATABASE` | Nombre de la base de datos | cimafood |
| `DB_USERNAME` | Usuario BD | root |
| `DB_PASSWORD` | Contraseña BD | `` |
| `STRIPE_KEY` | API key pública de Stripe | `` |
| `STRIPE_SECRET` | API secret de Stripe | `` |
| `PUSHER_APP_KEY` | Key de Pusher | `` |
| `PUSHER_APP_SECRET` | Secret de Pusher | `` |

## 📋 Uso básico

1. Regístrate o inicia sesión.
2. Navega por los restaurantes y agrega platos al carrito.
3. Completa la orden y elige método de pago.
4. Recibe notificaciones del estado del pedido.


## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Sigue estos pasos:

1. Fork el repositorio.
2. Crea una rama feature: `git checkout -b feature/nueva-funcionalidad`.
3. Implementa los cambios.
4. Abre un Pull Request describiendo los cambios.

Lee `CONTRIBUTING.md` para más detalles.

## 📄 Licencia

Este proyecto está bajo la licencia **MIT**. Consulta el archivo `LICENSE` para más información.

---

*Hecho con ❤️ usando Laravel.*
