<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Orders extends Component
{
    use WithPagination;

    public Business $business;
    public string $statusFilter = 'all';
    public ?int $selectedOrderId = null;
    public bool $showOrderDetail = false;
    public ?Order $selectedOrder = null;

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
    ];

    public function mount(Business $business): void
    {
        $this->business = $business;
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function viewOrder(int $orderId): void
    {
        $this->selectedOrder = Order::with(['user', 'products'])->findOrFail($orderId);
        $this->showOrderDetail = true;
    }

    public function closeDetail(): void
    {
        $this->showOrderDetail = false;
        $this->selectedOrder = null;
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $allowed = ['pending', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) return;

        $order = Order::findOrFail($orderId);

        // Asegurar que el pedido pertenece a este negocio
        if ($order->business_id !== $this->business->id) return;

        $order->update(['status' => $status]);

        // Actualizar la orden seleccionada si está abierta
        if ($this->selectedOrder && $this->selectedOrder->id === $orderId) {
            $this->selectedOrder->refresh();
        }

        $this->dispatch('order-updated');
    }

    public function render()
    {
        $query = $this->business->orders()->with(['user', 'products']);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        $stats = [
            'total'     => $this->business->orders()->count(),
            'pending'   => $this->business->orders()->where('status', 'pending')->count(),
            'completed' => $this->business->orders()->where('status', 'completed')->count(),
            'cancelled' => $this->business->orders()->where('status', 'cancelled')->count(),
            'revenue'   => $this->business->orders()->where('status', 'completed')->sum('total_price'),
        ];

        return view('livewire.business.orders', compact('orders', 'stats'));
    }
}
