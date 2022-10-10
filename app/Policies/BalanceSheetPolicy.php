<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\BalanceSheet;
use App\User;

class BalanceSheetPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, BalanceSheet $balanceSheet)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, BalanceSheet $balanceSheet)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, BalanceSheet $balanceSheet)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, BalanceSheet $balanceSheet)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\BalanceSheet  $balanceSheet
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, BalanceSheet $balanceSheet)
    {
        //
    }
}
