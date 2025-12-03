<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['vendor', 'admin']);
    }

    public function view(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->vendor?->id === $product->vendor_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['vendor', 'admin']);
    }

    public function update(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->vendor?->id === $product->vendor_id;
    }

    public function delete(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->vendor?->id === $product->vendor_id;
    }

    public function restore(User $user, Product $product): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->vendor?->id === $product->vendor_id;
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->hasRole('admin');
    }
}
