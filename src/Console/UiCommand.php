<?php

declare(strict_types=1);

namespace Canvas\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'canvas:ui { --force : Overwrite existing views by default }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install a Blade-powered frontend for Canvas';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Export Blade views...
        $this->exportViews();

        // Export application routes...
        $this->exportRoutes();

        // Export application controller...
        $this->exportController();

        $this->info('Canvas UI scaffolding installed successfully.');
    }

    /**
     * Export the application views.
     *
     * @return void
     */
    protected function exportViews()
    {
        $directory = resource_path('views/canvas-ui');

        (new Filesystem)->ensureDirectoryExists($directory);

        if (file_exists($directory) && ! $this->option('force')) {
            if (! $this->confirm("The [$directory] directory already exists. Do you want to replace it?")) {
                return;
            }
        }

        (new Filesystem)->copyDirectory(
            dirname(__DIR__, 2).'/stubs/ui/views',
            $directory
        );
    }

    /**
     * Export the application routes.
     *
     * @return void
     */
    protected function exportRoutes()
    {
        file_put_contents(
            base_path('routes/web.php'),
            file_get_contents(dirname(__DIR__, 2).'/stubs/ui/routes/web.stub'),
            FILE_APPEND
        );
    }

    /**
     * Export the application controller.
     *
     * @return void
     */
    protected function exportController()
    {
        (new Filesystem)->copy(
            dirname(__DIR__, 2).'/stubs/ui/Http/Controllers/CanvasUiController.stub',
            app_path('Http/Controllers/CanvasUiController.php')
        );
    }
}
