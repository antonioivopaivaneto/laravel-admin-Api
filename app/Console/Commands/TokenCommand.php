<?php

namespace App\Console\Commands;

use App\Models\User;
use Auth;
use Illuminate\Console\Command;
use Laravel\Prompts\Output\ConsoleOutput;

class TokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:generate {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $user = User::find($id);

        Auth::setUser($user);

        $console = new ConsoleOutput();

        $console->writeln($user->createToken('admin')->accessToken);

    }
}
