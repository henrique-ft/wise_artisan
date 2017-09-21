<?php

namespace App\Console\Commands\Compose;

use Illuminate\Console\Command;

class ControllersCompose extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compose:controllers {controllers_names*} {--resource}';

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

            // Get the controller class name removinc the directory structure 
            $class_name = trim(class_basename(str_replace("/", "\ ", $controller_name)));

            // Get the directory structure
            $directory_structure = implode('/',array_map(function($name){

                return title_case($name);

            },explode('/',str_before($controller_name, $class_name))));

            // Put a Controller sufix in the word, and put title case 
            $class_name = (str_contains('Controller', $class_name))? $class_name : ucfirst($class_name) . 'Controller';

            // Re-make the controller full name 
            $controller_name = $directory_structure . $class_name;

            // Write information on console
            $this->line('-> Composing: '.$controller_name. '...');

            // Create the controllers.
            $this->call('make:controller',[
                
                    'name' => $controller_name,
                    '--resource' => $this->option('resource')
                ]);
            
            // Create tests for controllers.
            $this->call('make:test',[
                
                    'name' => 'Controllers/' . '' . $controller_name . 'Test',
                    '--unit' => true
                ]);

            // Create a path name
            $path = base_path('resources/views/'.str_replace('Controller', '', $controller_name));

            // If dont existe this path
            if (!file_exists($path)) {

                // Create a path
                mkdir($path, 0777, true);
            }

            // Create index view
            $this->createViewFile($path,'index','');

            // Create resources views
            if($this->option('resource')) {

                $this->createViewFile($path,'create','');
                $this->createViewFile($path,'show','');
                $this->createViewFile($path,'edit','');
            }

            $this->createAssets(snake_case(str_replace('Controller', '', $class_name)));
        }
    }

    private function createViewFile($path, $name, $content)
    {

        // If dont existe this file
        if (!file_exists($path.'/'.$name.".blade.php")) {
            
            // Create a file
            fwrite(fopen($path.'/'.$name.".blade.php", "w"), $content);

            // Write information on console
            $this->info('View: '.ucfirst($name).' created successfully.');

        } else {
            // Write information on console
            $this->error('View: '.ucfirst($name).' already exists!');
        }

    }

    private function createAssets($name)
    {
        // If dont existe this file
        if (!file_exists(base_path('resources/assets/js/').$name.".js")) {
            
            // Create a file
            fwrite(fopen(base_path('resources/assets/js/').$name.".js", "w"), '');

            // Write information on console
            $this->info('Assets: Javascript created successfully.');

        } else {
            // Write information on console
            $this->error('Assets: Javascript already exists!');
        }

        // If dont existe this file
        if (!file_exists(base_path('resources/assets/sass/').$name.".scss")) {
            
            // Create a file
            fwrite(fopen(base_path('resources/assets/sass/').$name.".scss", "w"), '');

            // Write information on console
            $this->info('Assets: Sass created successfully.');

        } else {
            // Write information on console
            $this->error('Assets: Sass already exists!');
        }

    }
}
