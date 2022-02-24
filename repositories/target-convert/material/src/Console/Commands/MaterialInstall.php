<?php

namespace TargetConvert\Material\Console\Commands;

use Illuminate\Console\Command;

class MaterialInstall extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'material:install
        {--relative : Create the symbolic link using relative paths}
        {--force : Recreate existing symbolic links and Directories}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the material package';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->initDatabase();

        $this->initMaterialDirectory();
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $this->call('migrate');
        $this->call('db:seed', ['--class' => \TargetConvert\Material\Database\Seeders\MaterialSeeder::class]);
    }

    /**
     * Initialize the admAin directory.
     *
     * @return void
     */
    protected function initMaterialDirectory()
    {
        $this->directory = storage_path('app/materials');
        if($this->option('force'))
            $this->deleteDir('/');
        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        $this->makeDir('/');
        $this->makeDir('/tmp_materials');
        $this->makeDir('/feeds');
        $this->makeDir('/audios');
        $this->makeDir('/images');
        $this->makeDir('/videos');
        $this->makeDir('/downloadable');
        $this->makeDir('/secret');
        $this->makeDir('/streamable');
        $this->makeDir('/thumnail');
        $this->line('<info>Material directory was created:</info> '.str_replace(base_path(), '', $this->directory));

        if($this->option('force'))
            $this->deleteDir('/materials', storage_path('app/public'));
        $this->makeDir('/', storage_path('app/public/materials'));
        $links = [
            storage_path('app/public/materials/streamable') => storage_path('app/materials/streamable'),
            storage_path('app/public/materials/downloadable') => storage_path('app/materials/downloadable'),
            storage_path('app/public/materials/thumnail') => storage_path('app/materials/thumnail'),
        ];
        foreach ($links as $link => $target) {
            if (file_exists($link) && ! $this->isRemovableSymlink($link, $this->option('force'))) {
                $this->error("The [$link] link already exists.");
                continue;
            }

            if (is_link($link)) {
                $this->laravel->make('files')->delete($link);
            }

            $relative = $this->option('relative');
            if ($relative) {
                $this->laravel->make('files')->relativeLink($target, $link);
            } else {
                $this->laravel->make('files')->link($target, $link);
            }

            $this->info("The [$link] link has been connected to [$target].");
        }
        $this->call('storage:link');
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '', $root = null)
    {
        if(is_null($root))
            $root = $this->directory;
        $this->laravel['files']->makeDirectory("{$root}/$path", 0755, true, true);
    }

    protected function deleteDir($path = '', $root = null)
    {
        if(is_null($root))
            $root = $this->directory;
        $this->laravel['files']->deleteDirectory("{$root}/$path");
    }
}
