<?php

namespace App\Console\Commands\WiseArtisan\Models;

use Illuminate\Console\Command;

class ComposeModel extends Command
{
    use \App\Console\Commands\WiseArtisan\WiseArtisan;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'compose:model {model_name} {--all} {--controller} {--migration} {--resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a model, a factory and a test';

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
        // Write information on console
        $this->line('-> Composing: '.$this->argument('model_name'). '...');

        $this->call('make:model',[
            
            'name' => 'Models/'.$this->argument('model_name'),
            '--controller' => $this->option('controller'),
            '--migration' => $this->option('migration'),
            '--resource' => $this->option('resource'),
        ]);

        // Create a Factory for it
        $this->call('make:factory',[
            
            'name' => $this->getClassNameFromString($this->argument('model_name')). 'Factory',
            '--model' => 'Models/'.$this->argument('model_name'),
        ]);
        
        $this->call('make:test',[
            
            'name' => 'Models/'.$this->argument('model_name'),
            '--unit' => true 
        ]);
        
    }
}
