<?php

namespace App\Console\Commands;

use App\Models\ApiToken;
use Illuminate\Console\Command;

class GetToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch an api token from the database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $token = ApiToken::firstOrFail();

        $this->info("Authorization: Bearer $token->token");
    }
}
