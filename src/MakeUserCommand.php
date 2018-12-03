<?php

namespace Petervig\LaravelMakeUser;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user {--name=} {--email=} {--password=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert a user to the db';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->comment('User details:');
        $user = [
            'name' => $this->option('name') ? $this->option('name') : $this->ask('name'),
            'email' => $this->option('email') ? $this->option('email') : $this->ask('email'),
            'password' => $this->option('password') ? $this->option('password') : $this->secret('password'),
        ];
        if(!$this->option('password')) {
            $passConfirm = null;
            do{
                if($passConfirm !== null)
                    $this->comment('The password didn\'t match ');
                $passConfirm = $this->secret('password again');
            }while($passConfirm !== $user['password']);
        }

        $user['password'] = Hash::make($user['password']);

        config('auth.providers.users.model')::create($user);

        $this->comment('User '. $user['name'] . ', ' . $user['email'] .' has been created');
    }
}
