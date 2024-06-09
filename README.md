# Task Management App

## Stack
* PHP 8.3
* Laravel 11
* Sanctum
* Vue 3
* Inertia
* Tanstack Vue
* TailwindCSS
* MySQL

## How to set up local server

## System Requirements
* PHP 8.3.7
* Node v22.0.1
* MySQL

## Steps
* Clone the <a href="https://github.com/nadlambino/task-manager.git">repository</a>
* Run `composer install` and `npm install`
* Create a database named `task_manager` or any other name, just update what is on the `.env` file.
* Run `php artisan migrate:fresh --seed` to create the tables, seed a user and the statuses.
* If you're running this app on Laravel Valet, Laravel Herd, or Laragon, you can access the site at <a href="http://task-manager.test">http://task-manager.test</a>
* Else, run `php artisan serve --port=80`. Make sure that it is running on port `80` for sanctum to work and for the images to easily be accessible since they're uploaded locally.
* Run `npm run dev`
* You can now visit the app at <a href="http://localhost">http://localhost</a>

## Cron Job (Auto Deletion of Trashed Tasks)
To automatically delete tasks that were already trashed for 30 days
* Customize the configs in `.env` file. Look for the `DELETE_TRASH_DAYS_OLD` and `RUN_DELETE_EVERY` keys
* `DELETE_TRASH_DAYS_OLD` is the number of days a task is in the trash for it to be automatically deleted. 
Change to `0` if you want to test deletion of tasks that are recently trashed.
Default value is `30`
* `RUN_DELETE_EVERY` is use to determine how often the cron job should run. 
Accepted values are `daily` or `minute`. 
Default is `daily`.
Use `minute` if you want to test it immediately.
* Run `php artisan schedule:work`
* You can also manually test it using `php artisan app:delete-trashed-tasks`

## Note
If something is not working, please run `php artisan optimize`
