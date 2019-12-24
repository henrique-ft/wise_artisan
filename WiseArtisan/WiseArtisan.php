<?php

namespace App\Console\Commands\WiseArtisan;

trait WiseArtisan
{
    protected function putSuffixAndUcFirst($string, $suffix)
    {
        return (\Str::contains($suffix, $string))? $string : ucfirst($string) . $suffix;
    }
    
    protected function getClassNameFromString($string)
    {
        return trim(class_basename(str_replace("/", "\\", $string)));
    }
    
    protected function getNamespaceFromString($string)
    {
        // Get the class name remove the directory structure 
        $class_name = $this->getClassNameFromString($string);

        // Get the directory structure
        $namespace = rtrim(implode('\\',array_map(function($val){

            return \Str::title($val);

        },explode('/', \Str::before($string, $class_name)))),'\\');
        
        return ($namespace == '\\' || $namespace == '')? '' : '\\' . $namespace;
    }
    
    protected function getDirectoryStructureFromString($string, $dot = false, $lowercase = false)
    {
        // Get the class name remove the directory structure 
        $class_name = $this->getClassNameFromString($string);

        $separation = ($dot)? '.' : '/';

        // Get the directory structure
        return implode($separation, array_map(function($val) use ($dot, $lowercase){
            
            return ($dot == true || $lowercase == true)? strtolower($val) : \Str::title($val);

        },explode('/', \Str::before($string, $class_name))));
    }
    
    protected function createModel($name, $methods = [], $fillable = [])
    {

        // Recebe o stub do model
        $method_stub = file_get_contents(base_path('app/Console/Commands/WiseArtisan/Controllers/Stubs/method.stub'));
        $model_stub = file_get_contents(base_path('app/Console/Commands/WiseArtisan/Controllers/Stubs/controller.stub')); 
        
        $actions_filled = '';
        
        foreach ($methods as $method) {
            
            $aux = str_replace('{{method_name}}', $method, $method_stub);
            $aux = str_replace('{{method_params}}', '', $aux);
            $aux = str_replace('{{view_path}}', $view_path. strtolower(str_replace('Controller', '', $name)) .'.', $aux);
            
            $methods_filled .= $aux . "\n\n";
        }
        
        $content = str_replace('{{namespace}}', $namespace, $model_stub);
        $content = str_replace('{{controller_name}}', str_replace('Controller', '', $name), $content);
        $content = str_replace('{{view_path}}', $view_path. strtolower(str_replace('Controller', '', $name)) .'.', $content);
        $content = str_replace('{{path}}', str_replace('/', '\\', $path), $content);
        $content = str_replace('{{actions}}', $methods_filled, $content);
        
        // Create a path name
        $path = base_path('app/Http/Controllers/'.$path);

        // If this path dont exist
        if (!file_exists($path)) {

            // Create a path
            mkdir($path, 0777, true);
        }

        // If dont existe this file
        if (!file_exists($path.$name.".php")) {
            
            // Create a file
            fwrite(fopen($path.$name.".php", "w"), $content);

            // Write information on console
            $this->info('Controller '.ucfirst($name).' created successfully.');

        } else {
            // Write information on console
            $this->error('Controller  '.ucfirst($name).' already exists!');
        }

    }
    
    protected function createController($path, $name, $namespace, $view_path, $resource = false)
    {
        // Recebe o stub do controller
        
        if ($resource) {

            $controller_stub = file_get_contents(base_path('app/Console/Commands/WiseArtisan/Controllers/Stubs/resource-controller.stub'));
        
        } else {
         
            $controller_stub = file_get_contents(base_path('app/Console/Commands/WiseArtisan/Controllers/Stubs/controller.stub'));    
        }
        
        $content = str_replace('{{namespace}}', $namespace, $controller_stub);
        $content = str_replace('{{controller_name}}', str_replace('Controller', '', $name), $content);
        $content = str_replace('{{view_path}}', $view_path. strtolower(str_replace('Controller', '', $name)) .'.', $content);
        $content = str_replace('{{path}}', str_replace('/', '\\', $path), $content);
        
        if (!$resource) {

            $content = str_replace('{{actions}}', '', $content);
        }
        
        // Create a path name
        $path = base_path('app/Http/Controllers/'.$path);

        // If this path dont exist
        if (!file_exists($path)) {

            // Create a path
            mkdir($path, 0777, true);
        }

        // If dont existe this file
        if (!file_exists($path.$name.".php")) {
            
            // Create a file
            fwrite(fopen($path.$name.".php", "w"), $content);

            // Write information on console
            $this->info('Controller '.ucfirst($name).' created successfully.');

        } else {
            // Write information on console
            $this->error('Controller  '.ucfirst($name).' already exists!');
        }
    }
    
    // php artisan compose:controller Teste teste(SeilaRequest$request,int$teste):force seila() fazercoisa()
    protected function createControllerWithActions($path, $name, $namespace, $view_path, $actions = [])
    {
        // Recebe o stub do controller
        $action_stub = file_get_contents(base_path('app/Console/Commands/WiseArtisan/Controllers/Stubs/method.stub'));
        $controller_stub = file_get_contents(base_path('app/Console/Commands/WiseArtisan/Controllers/Stubs/controller.stub')); 
        
        $actions_filled = '';
        
        foreach ($actions as $action) {
            
            $aux = str_replace('{{method_name}}', $action, $action_stub);
            $aux = str_replace('{{method_params}}', '', $aux);
            $aux = str_replace('{{view_path}}', $view_path. strtolower(str_replace('Controller', '', $name)) .'.', $aux);
            
            $actions_filled .= $aux . "\n\n";
        }
        
        $content = str_replace('{{namespace}}', $namespace, $controller_stub);
        $content = str_replace('{{controller_name}}', str_replace('Controller', '', $name), $content);
        $content = str_replace('{{view_path}}', $view_path. strtolower(str_replace('Controller', '', $name)) .'.', $content);
        $content = str_replace('{{path}}', str_replace('/', '\\', $path), $content);
        $content = str_replace('{{actions}}', $actions_filled, $content);
        
        // Create a path name
        $path = base_path('app/Http/Controllers/'.$path);

        // If this path dont exist
        if (!file_exists($path)) {

            // Create a path
            mkdir($path, 0777, true);
        }

        // If dont existe this file
        if (!file_exists($path.$name.".php")) {
            
            // Create a file
            fwrite(fopen($path.$name.".php", "w"), $content);

            // Write information on console
            $this->info('Controller '.ucfirst($name).' created successfully.');

        } else {
            // Write information on console
            $this->error('Controller  '.ucfirst($name).' already exists!');
        }
    }

    protected function createViewFile($path, $name, $content)
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

    protected function createAssets($path_js, $path_sass, $name)
    {
        
        // If this path dont exist
        if (!file_exists($path_js)) {

            // Create a path
            mkdir($path_js, 0777, true);
        }

        // If dont existe this file
        if (!file_exists($path_js.$name.".js")) {
            
            // Create a file
            fwrite(fopen($path_js.$name.".js", "w"), '');

            // Write information on console
            $this->info('Assets: Javascript created successfully.');

        } else {
            // Write information on console
            $this->error('Assets: Javascript already exists!');
        }

        // If this path dont exist
        if (!file_exists($path_sass)) {

            // Create a path
            mkdir($path_sass, 0777, true);
        }

        // If dont existe this file
        if (!file_exists($path_sass.$name.".css")) {
            
            // Create a file
            fwrite(fopen($path_sass.$name.".css", "w"), '');

            // Write information on console
            $this->info('Assets: Sass created successfully.');

        } else {
            // Write information on console
            $this->error('Assets: Sass already exists!');
        }
    }
}
