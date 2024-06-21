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

        $user->priorityLevels()->createMany([
            [
                'name' => 'Low',
                'color' => '#10B981',
                'description' => 'Low priority level',
                'score' => 1,
                'is_default' => true,
            ],
            [
                'name' => 'Medium',
                'color' => '#F59E0B',
                'description' => 'Medium priority level',
                'score' => 2,
                'is_default' => true,
            ],
            [
                'name' => 'High',
                'color' => '#EF4444',
                'description' => 'High priority level',
                'score' => 3,
                'is_default' => true,
            ],
        ]);
    }
}
