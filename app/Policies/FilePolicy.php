<?php

namespace App\Policies;

use App\Models\File;
use App\Models\User;

class FilePolicy
{
    /**
     * Determine if the user can view any files.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view files
    }

    /**
     * Determine if the user can view the file.
     */
    public function view(User $user, File $file): bool
    {
        return true; // All authenticated users can view files
    }

    /**
     * Determine if the user can create files.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can upload files
    }

    /**
     * Determine if the user can update the file.
     */
    public function update(User $user, File $file): bool
    {
        return true; // All authenticated users can update files
    }

    /**
     * Determine if the user can delete the file.
     */
    public function delete(User $user, File $file): bool
    {
        return true; // All authenticated users can delete files
    }
}

