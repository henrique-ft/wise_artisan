<?php

namespace App\Console\Commands\Compose\Controllers;

use Illuminate\Console\Command; 

class ControllersCompose extends Command
{
    use \App\Console\Commands\Compose\WiseArtisan;
    
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
    protected $description = 'Create a controller, a unit test, assets, and a view folder for it';

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
            
            // Get the class name
            $class_name = $this->getClassNameFromString($controller_name);
            
            // Re-make the controller full name 
            $controller_name = $this->getDirectoryStructureFromString($controller_name) . $this->putSuffixAndUcFirst($class_name, 'Controller');
            
            // Write information on console
            $this->line('-> Composing: '.$controller_name. '...');
            
            // ---------------- Create the controllers.
            
            $this->createController(
                $this->getDirectoryStructureFromString($controller_name),
                    $this->getClassNameFromString($controller_name),
                        $this->getNamespaceFromString($controller_name),
                            $this->getDirectoryStructureFromString($controller_name, true),
                                $this->option('resource')
            );
        
            //$this->option('resource')
            
            // --------------- Create tests for controllers.
            
            $this->call('make:test',[
                
                    'name' => 'Controllers/' . $controller_name . 'Test'
                ]);
            
            // --------------- Create Requests if resource option is set.
            
            
            if ($this->option('resource')) {
                
                $this->call('make:request',[
                
                    'name' => str_replace('Controller', '', $controller_name) . '/StoreRequest'
                ]);       
                
                $this->call('make:request',[
                
                    'name' => str_replace('Controller', '', $controller_name) . '/UpdateRequest'
                ]);   
            }
            
            // --------------- Create views for controllers. 
            
            // Create a path name for views
            $path = base_path('resources/views/'.$this->getDirectoryStructureFromString($controller_name, false, true). strtolower(str_replace('Controller', '', $this->getClassNameFromString($controller_name))));

            // If this path dont exist
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
            
            // --------------- Create assets for controllers. 

            // Create a path name for asset
            $path_js = base_path('resources/assets/js/'.$this->getDirectoryStructureFromString($controller_name, false, true));
            $path_sass = base_path('resources/assets/sass/'.$this->getDirectoryStructureFromString($controller_name, false, true));

            $this->createAssets($path_js, $path_sass, snake_case(str_replace('Controller', '', $class_name)));
        }
    }

}
