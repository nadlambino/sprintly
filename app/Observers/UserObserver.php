<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->status()->createMany([
            [
                'name' => 'Todo',
                'color' => '#4B5563',
                'description' => 'To Do status',
                'order' => 0,
            ],
            [
                'name' => 'In Progress',
                'color' => '#F59E0B',
                'description' => 'In Progress status',
                'order' => 1,
            ],
            [
                'name' => 'Done',
                'color' => '#10B981',
                'description' => 'Done status',
                'order' => 2,
            ],
        ]);
    }
}
