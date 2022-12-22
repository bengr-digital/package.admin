<?php

namespace Bengr\Admin\Commands;

use Bengr\Admin\Facades\Admin;
use Bengr\Support\Commands\Concerns\CanValidateInput;
use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class MakeAdminUserCommand extends Command
{

    use CanValidateInput;

    protected $description = 'Creates a Bengr admin user.';

    protected $signature = 'bengr:user';

    protected function getUserData(): array
    {
        return [
            'first_name' => $this->validate(fn () => $this->ask('First Name'), 'first_name', ['required']),
            'last_name' => $this->validate(fn () => $this->ask('Last Name'), 'last_name', ['required']),
            'username' => $this->validate(fn () => $this->ask('Username'), 'username', ['required']),
            'email' => $this->validate(fn () => $this->ask('Email'), 'email', ['required', 'email', 'unique:' . $this->getUserModel()]),
            'password' => Hash::make($this->validate(fn () => $this->secret('Password'), 'password', ['required', 'min:8'])),
        ];
    }

    protected function sendSuccessMessage(Authenticatable $user): void
    {
        $this->info('Success! User was created!');
        $this->info('username: ' . $user->username);
        $this->info('email: ' . $user->email);
    }

    protected function createUser(): Authenticatable
    {
        return static::getUserModel()::create($this->getUserData());
    }

    protected function getAuthGuard(): Guard
    {
        return Admin::auth();
    }

    protected function getUserProvider(): UserProvider
    {
        return $this->getAuthGuard()->getProvider();
    }

    protected function getUserModel(): string
    {
        /** @var EloquentUserProvider $provider */
        $provider = $this->getUserProvider();

        return $provider->getModel();
    }

    public function handle(): int
    {
        $user = $this->createUser();

        $this->sendSuccessMessage($user);

        return static::SUCCESS;
    }
}
