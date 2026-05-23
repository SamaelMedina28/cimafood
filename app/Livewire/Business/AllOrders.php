<?php

namespace App\Livewire\Business;

use App\Models\Business;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class AllOrders extends Component
{
    use WithPagination;

    public string $statusFilter = 'all';
    public string $businessFilter = 'all';
    public string $search = '';

    public ?Order $selectedOrder = null;
    public bool $showOrderDetail = false;

    protected $queryString = [
        'statusFilter'   => ['except' => 'all'],
        'businessFilter' => ['except' => 'all'],
        'search'         => ['except' => ''],
    ];

    public function updatedStatusFilter(): void   { $this->resetPage(); }
    public function updatedBusinessFilter(): void { $this->resetPage(); }
    public function updatedSearch(): void         { $this->resetPage(); }

    public function viewOrder(int $orderId): void
    {
        $this->selectedOrder = Order::with(['user', 'business', 'products'])->findOrFail($orderId);
        $this->showOrderDetail = true;
    }

    public function closeDetail(): void
    {
        $this->showOrderDetail = false;
        $this->selectedOrder  = null;
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $allowed = ['pending', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) return;

        $vendorBusinessIds = Auth::user()->businesses()->pluck('id')->toArray();
        $order = Order::whereIn('business_id', $vendorBusinessIds)->findOrFail($orderId);
        $order->update(['status' => $status]);

        if ($this->selectedOrder && $this->selectedOrder->id === $orderId) {
            $this->selectedOrder->refresh();
        }
    }

    public function render()
    {
        $user      = Auth::user();
        $businesses = $user->businesses()->orderBy('name')->get();
        $businessIds = $businesses->pluck('id')->toArray();

        $query = Order::with(['user', 'business', 'products'])
            ->whereIn('business_id', $businessIds);

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->businessFilter !== 'all') {
            $query->where('business_id', $this->businessFilter);
        }

        if ($this->search !== '') {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(12);

        $stats = [
            'total'     => Order::whereIn('business_id', $businessIds)->count(),
            'pending'   => Order::whereIn('business_id', $businessIds)->where('status', 'pending')->count(),
            'completed' => Order::whereIn('business_id', $businessIds)->where('status', 'completed')->count(),
            'cancelled' => Order::whereIn('business_id', $businessIds)->where('status', 'cancelled')->count(),
            'revenue'   => Order::whereIn('business_id', $businessIds)->where('status', 'completed')->sum('total_price'),
        ];

        return view('livewire.business.all-orders', compact('orders', 'stats', 'businesses'));
    }
}
