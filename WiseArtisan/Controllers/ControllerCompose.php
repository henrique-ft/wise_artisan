<?php

namespace App\Console\Commands\WiseArtisan\Controllers;

use Illuminate\Console\Command;

class ControllerCompose extends Command
{
    use \App\Console\Commands\WiseArtisan\WiseArtisan;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compose:controller {arguments*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller with methods, tests, a view folder for ir and plain blade files. Create requests if is a resource controller';

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
        // php artisan compose:controller Teste teste(SeilaRequest$request,int$teste):force seila() fazercoisa()
        
        $arguments = $this->argument('arguments');
        
        $controller_name = array_shift($arguments);
        
        $actions = $arguments;
            
        // Get the class name
        $class_name = $this->getClassNameFromString($controller_name);
        
        // Re-make the controller full name 
        $controller_name = $this->getDirectoryStructureFromString($controller_name) . $this->putSuffixAndUcFirst($class_name, 'Controller');
        
        // Write information on console
        $this->line('-> Composing: '.$controller_name. '...');
        
        // ---------------- Create the controllers.
        
        $this->createControllerWithActions(
            $this->getDirectoryStructureFromString($controller_name),
                $this->getClassNameFromString($controller_name),
                    $this->getNamespaceFromString($controller_name),
                        $this->getDirectoryStructureFromString($controller_name, true),
                            $actions
        );
    
        //$this->option('resource')
        
        // --------------- Create tests for controllers.
        
        $this->call('make:test',[
            
                'name' => 'Controllers/' . $controller_name . 'Test'
            ]);
        
        // --------------- Create Requests if resource option is set.
        

        foreach($actions as $action) {
            
            // Se tiver a palavra Request em algum lugar do parâmetro da action, cria automaticamente as requests 
            
            // $this->call('make:request',[
            
            //     'name' => str_replace('Controller', '', $controller_name) . '/StoreRequest'
            // ]);   
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
        
        foreach($actions as $action) {
            
            $this->createViewFile($path, $action,'');
        }
        
        // --------------- Create assets for controllers. 

        // Create a path name for asset
        $path_js = base_path('resources/assets/js/'.$this->getDirectoryStructureFromString($controller_name, false, true));
        $path_sass = base_path('resources/assets/sass/'.$this->getDirectoryStructureFromString($controller_name, false, true));

        $this->createAssets($path_js, $path_sass, \Str::snake(str_replace('Controller', '', $class_name)));
    }
}

