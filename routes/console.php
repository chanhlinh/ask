<?php

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('ask:create-admin {email : Admin email} {--name=Administrator}', function (Command $command) {
    $email = strtolower((string) $command->argument('email'));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Provide a valid email address.'); return Command::FAILURE;
    }
    $password = $this->secret('Choose a password (minimum 12 characters)');
    if (strlen((string) $password) < 12) { $this->error('Password must be at least 12 characters.'); return Command::FAILURE; }
    $confirm = $this->secret('Confirm password');
    if ($password !== $confirm) { $this->error('Passwords do not match.'); return Command::FAILURE; }
    User::updateOrCreate(['email' => $email], ['name' => (string) $command->option('name'), 'password' => Hash::make($password), 'is_admin' => true]);
    $this->info('Admin created or updated.'); return Command::SUCCESS;
})->purpose('Create the first Ask administrator securely');
