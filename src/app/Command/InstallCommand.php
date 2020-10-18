<?php

namespace Emamie\Atom\Command;

use Illuminate\Console\Command;
use Encore\Admin\Auth\Database\Administrator;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'atom:install {action?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the atom package';

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
        switch ($this->argument('action')) {
            case 'publish':
                $this->initAdminDirectory();
                $this->vendorPublish();
                break;
            case 'database':
                $this->initDatabase();
                break;
            default:
                $this->initAdminDirectory();
                $this->vendorPublish();
                $this->initDatabase();
                break;
        }
    }

    public function vendorPublish()
    {
        $this->call('vendor:publish', ['--tag' => 'laravel-admin-assets','--force' => true]);
        ## $this->call('vendor:publish', ['--tag' => 'laravel-admin-config','--force' => true]);
        ## $this->call('vendor:publish', ['--tag' => 'laravel-admin-lang','--force' => true]);
        # migration copy to atom
        ## $this->call('vendor:publish', ['--tag' => 'laravel-admin-migrations','--force' => true]);

        $this->call('vendor:publish', ['--tag' => 'laravel-admin-media-player','--force' => true]);

        $this->call('vendor:publish', ['--tag' => 'migrations','--force' => true]);

        $this->call('vendor:publish', ['--tag' => 'configurations','--force' => true]);

        $this->call('vendor:publish', ['--tag' => 'atom','--force' => true]);
    }

    /**
     * Create tables and seed it.
     *
     * @return void
     */
    public function initDatabase()
    {
        $this->call('migrate',['--force' => true]);

        if (Administrator::count() == 0) {
            $this->call('db:seed', ['--class' => \Encore\Admin\Auth\Database\AdminTablesSeeder::class,'--force' => true]);
            $this->line("db seed success ");
        }
    }

    /**
     * Initialize the admAin directory.
     *
     * @return void
     */
    protected function initAdminDirectory()
    {
        $this->directory = config('admin.directory');

        if (is_dir($this->directory)) {
            $this->line("<error>{$this->directory} directory already exists !</error> ");

            return;
        }

        // @TODO add init app page of atom like : vendor/encore/laravel-admin/src/Console/InstallCommand.php

//        $this->makeDir('/');
//        $this->line('<info>Admin directory was created:</info> '.str_replace(base_path(), '', $this->directory));
//
//        $this->makeDir('Controllers');

        // $this->createHomeController();
    }

    /**
     * Create HomeController.
     *
     * @return void
     */
    public function createExampleController()
    {
        $exampleController = $this->directory.'/Controllers/ExampleController.php';
        $contents = $this->getStub('ExampleController');

        $this->laravel['files']->put(
            $exampleController,
            str_replace('DummyNamespace', config('admin.route.namespace'), $contents)
        );
        $this->line('<info>ExampleController file was created:</info> '.str_replace(base_path(), '', $exampleController));
    }

    /**
     * Get stub contents.
     *
     * @param $name
     *
     * @return string
     */
    protected function getStub($name)
    {
        return $this->laravel['files']->get(__DIR__."/stubs/$name.stub");
    }

    /**
     * Make new directory.
     *
     * @param string $path
     */
    protected function makeDir($path = '')
    {
        $this->laravel['files']->makeDirectory("{$this->directory}/$path", 0755, true, true);
    }
}
