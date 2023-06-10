<?php

namespace App\Console\Commands;

use App\Models\Todo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TodoReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for todos';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $todos = Todo::whereDate('due_date', now()->addDay())->get();

        foreach ($todos as $todo) {
            $user = $todo->user;

            Mail::send('emails.todo_reminder', ['todo' => $todo], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Reminder: Todo Due Tomorrow');
            });
        }

        $this->info('Todo reminders sent successfully.');
    }
}
