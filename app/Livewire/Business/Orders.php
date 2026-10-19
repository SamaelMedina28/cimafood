<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Validation\ValidationException;
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
        $this->authorize('manage', $business);
        $this->business = $business;
    }

    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    public function viewOrder(int $orderId): void
    {
        $this->selectedOrder = $this->business->orders()->with(['user', 'products', 'business'])->findOrFail($orderId);
        $this->showOrderDetail = true;
    }

    public function closeDetail(): void
    {
        $this->showOrderDetail = false;
        $this->selectedOrder = null;
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $order = $this->business->orders()->findOrFail($orderId);
        $this->authorize('manage', $order);
        $this->resetErrorBag('status');

        try {
            app(OrderService::class)->changeStatus($order, $status);
        } catch (ValidationException $e) {
            $this->addError('status', $e->getMessage());
            return;
        }

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
