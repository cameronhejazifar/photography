<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Console\Command;
use Illuminate\Validation\ValidationException;
use Validator;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a user';

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
     * @return int
     * @throws \Throwable
     */
    public function handle()
    {
        try {
            $data = Validator::validate([
                'email' => $this->ask('Enter Email'),
                'password' => $this->ask('Enter Password'),
                'name' => $this->ask('Enter Full Name'),
            ], [
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
                'name' => 'required|string|between:2,255',
            ]);
        } catch (ValidationException $e) {
            $this->error($e->getMessage());
            foreach ($e->validator->getMessageBag()->all() as $errorMessage) {
                $this->error("  - {$errorMessage}");
            }
            return 1;
        }

        $user = new User;
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->name = $data['name'];
        $user->email_verified_at = Carbon::now();
        $user->saveOrFail();
        $this->info("User created: ID = {$user->id}");

        return 0;
    }
}
