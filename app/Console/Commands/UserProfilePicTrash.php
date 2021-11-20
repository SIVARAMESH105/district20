<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Users;
use Exception;
use Log;
use DB;

// Note : php artisan UserProfilePicTrash

class UserProfilePicTrash extends Command 
{
    /**
    * The console command name.
    *
    * @var string
    */
    protected $name = 'UserProfilePicTrash';

    protected $signature = 'UserProfilePicTrash';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Used to trash user profile pic.';

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
    public function handle() {
      Log::info('---UserProfilePicTrash Started');
      $directory = 'public/users_pic'; 
      $files = scandir($directory);
      foreach ($files as &$value) {          
        if(!Users::where('profile_pic', 'users_pic/'.$value)->first()){
          $file = $directory.'/'.$value;
          if(is_file($file))
            unlink($file);          
          Log::info($value.'----Deleted');
        }          
      }
      Log::info('---UserProfilePicTrash Done');  
    }    
}