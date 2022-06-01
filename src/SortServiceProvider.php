<?php

namespace KimSpeer\Sort;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use KimSpeer\Sort\Commands\SortCommand;
use KimSpeer\Sort\Components\BladeComponent;
use KimSpeer\Sort\Components\LivewireComponent;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SortServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('sortlist')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasMigration('create_sortlist_table')
            ->hasCommand(SortCommand::class);
    }

    public function boot()
    {
        $this->bootResources();
        $this->bootBladeComponents();
        $this->bootLivewireComponents();
        $this->bootDirectives();
    }

    private function bootResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'sortlist');
    }

    private function bootBladeComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (BladeCompiler $blade) {
            $prefix = config('sortlist.prefix', '');
            $assets = config('sortlist.assets', []);

            /** @var BladeComponent $component */
            foreach (config('sortlist.components', []) as $alias => $component) {
                $blade->component($component, $alias, $prefix);

                $this->registerAssets($component, $assets);
            }
        });
    }

    private function bootLivewireComponents(): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        $prefix = config('sortlist.prefix', '');
        $assets = config('sortlist.assets', []);

        /** @var LivewireComponent $component */
        foreach (config('sortlist.livewire', []) as $alias => $component) {
            $alias = $prefix ? "$prefix-$alias" : $alias;

            Livewire::component($alias, $component);

            $this->registerAssets($component, $assets);
        }
    }

    private function registerAssets($component, array $assets): void
    {
        foreach ($component::assets() as $asset) {
            $files = (array) ($assets[$asset] ?? []);

            collect($files)->filter(function (string $file) {
                return Str::endsWith($file, '.css');
            })->each(function (string $style) {
                Sort::addStyle($style);
            });

            collect($files)->filter(function (string $file) {
                return Str::endsWith($file, '.js');
            })->each(function (string $script) {
                Sort::addScript($script);
            });
        }
    }

    private function bootDirectives(): void
    {
        Blade::directive('SortStyles', function (string $expression) {
            return "<?php echo KimSpeer\\Sort\\Sort::outputStyles($expression); ?>";
        });

        Blade::directive('SortScripts', function (string $expression) {
            return "<?php echo KimSpeer\\Sort\\Sort::outputScripts($expression); ?>";
        });
    }
}
