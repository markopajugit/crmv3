<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;

class PersonPolicy
{
    /**
     * Determine if the user can view any persons.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view persons
    }

    /**
     * Determine if the user can view the person.
     */
    public function view(User $user, Person $person): bool
    {
        return true; // All authenticated users can view persons
    }

    /**
     * Determine if the user can create persons.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create persons
    }

    /**
     * Determine if the user can update the person.
     */
    public function update(User $user, Person $person): bool
    {
        return true; // All authenticated users can update persons
    }

    /**
     * Determine if the user can delete the person.
     */
    public function delete(User $user, Person $person): bool
    {
        return true; // All authenticated users can delete persons
    }
}

