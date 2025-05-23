<?php

namespace App\Policies;

use App\Models\PricingPlan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PricingPlanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-products') || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PricingPlan $pricingPlan): bool
    {
        return $user->hasPermissionTo('view-products') || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-products') || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PricingPlan $pricingPlan): bool
    {
        return $user->hasPermissionTo('edit-products') || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PricingPlan $pricingPlan): bool
    {
        return $user->hasPermissionTo('delete-products') || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, PricingPlan $pricingPlan): bool
    {
        return $user->hasPermissionTo('manage-products') || $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, PricingPlan $pricingPlan): bool
    {
        return $user->hasPermissionTo('manage-products') || $user->isAdmin();
    }
}
