<?php

namespace App\Console\Commands\Compose;

use Illuminate\Console\Command;

class ControllerCompose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wise:g:controller {controllers_names*} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller, a unit test and a view folder for it';

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
        
        foreach ($this->argument('controllers_names') as $controller_name) {
        
            $controller_name = (str_contains('Controller', $controller_name))? $controller_name : title_case($controller_name) . 'Controller';
            
            $this->call('make:controller',[
                
                    'name' => $controller_name,
                    '--resource' => $this->option('resource')
                ]);
            
            $this->call('make:test',[
                
                    'name' => 'Controllers/' . '' . $controller_name . 'Test',
                    '--unit' => true
                ]);
        }
    }
}
