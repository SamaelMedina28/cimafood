<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\User;

class BusinessPolicy
{
    public function create(User $user): bool
    {
        return $user->is_vendor;
    }

    // Ver el detalle de administración, editar y eliminar: solo el dueño
    public function manage(User $user, Business $business): bool
    {
        return $user->is_vendor && $business->user_id === $user->id;
    }
}
