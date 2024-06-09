<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schedule;

class DeleteTrashedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-trashed-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all soft-deleted tasks';

    /**
     * Execute the console command.
     */
    public function handle(Task $task)
    {
        $daysAgo = Carbon::now()->subDays((int) config('app.delete_trash_days_old', 30));

        // Run delete on each model to fire the `delete` event
        // Which is being listened by the `uploadable` package to delete the files
        // associated with the task model.
        $task->query()
            ->onlyTrashed()
            ->where('deleted_at', '<=', $daysAgo)
            ->get()
            ->each(fn ($task) => $task->forceDelete());
    }

    /**
     * Schedule the command.
     *
     * @return void
     */
    public static function schedule(): void
    {
        $frequency = match (config('app.run_delete_every', 'daily')) {
            'daily' => 'daily',
            'minute' => 'everyMinute',
            default => 'daily'
        };

        Schedule::command('app:delete-trashed-tasks')->$frequency();
    }
}
