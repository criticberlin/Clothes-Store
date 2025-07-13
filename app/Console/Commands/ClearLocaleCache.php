<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class ClearLocaleCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-locale-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear locale-related cookies and session data to fix encoding issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Clear locale session data
        Session::forget('locale');
        
        // We cannot directly clear cookies from CLI but we can inform the user
        $this->info('Session locale data cleared.');
        $this->info('To fully fix the issue, please also clear your browser cookies for this site.');
        $this->info('Alternatively, you can navigate to /clear-locale in the browser to clear the cookies.');
        
        return Command::SUCCESS;
    }
}
