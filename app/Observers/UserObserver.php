<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->statuses()->createMany([
            [
                'name' => 'Todo',
                'color' => '#4B5563',
                'description' => 'To Do status',
                'order' => 1,
                'is_default' => true,
            ],
            [
                'name' => 'In Progress',
                'color' => '#F59E0B',
                'description' => 'In Progress status',
                'order' => 2,
                'is_default' => true,
            ],
            [
                'name' => 'Done',
                'color' => '#10B981',
                'description' => 'Done status',
                'order' => 3,
                'is_default' => true,
            ],
        ]);
    }
}
