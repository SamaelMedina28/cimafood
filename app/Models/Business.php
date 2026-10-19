<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'phone',
        'logo',
        'banner',
        'status',
        'rating',
        'open_time',
        'close_time',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function totalReviews()
    {
        return $this->reviews()->count();
    }

    // Solo quien ya recibió un pedido del negocio puede reseñarlo, y una sola vez
    public function canBeReviewedBy(User $user): bool
    {
        return $this->orders()->where('user_id', $user->id)->where('status', 'completed')->exists()
            && !$this->reviews()->where('user_id', $user->id)->exists();
    }

    public function refreshRating(): void
    {
        $this->update(['rating' => round($this->reviews()->avg('rating') ?? 0, 2)]);
    }

    public function getLogoUrlAttribute()
    {
        return self::imageUrl($this->logo);
    }

    public function getBannerUrlAttribute()
    {
        return self::imageUrl($this->banner);
    }

    // Considera horarios que cruzan la medianoche (ej. 18:00 - 02:00)
    public function isOpen(): bool
    {
        $now = now()->format('H:i');
        $open = substr($this->open_time, 0, 5);
        $close = substr($this->close_time, 0, 5);

        if ($open <= $close) {
            return $now >= $open && $now <= $close;
        }

        return $now >= $open || $now <= $close;
    }

    // Borra una imagen subida al disco público; las URLs externas (seeders) se ignoran
    public static function deleteImage(?string $path): void
    {
        if ($path && !str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }

    public static function imageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return str_starts_with($path, 'http') ? $path : asset('storage/' . $path);
    }
}
