<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    <x-admin.stat label="Pendientes" :value="$stats['pending']" :highlight="$stats['pending'] > 0" hint="Por atender" />
    <x-admin.stat label="Completados" :value="$stats['completed']" />
    <x-admin.stat label="Cancelados" :value="$stats['cancelled']" />
    <x-admin.stat label="Ingresos" :value="'$' . number_format($stats['revenue'], 2)" hint="Pedidos completados" />
</div>
