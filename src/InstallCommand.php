<?php

namespace Cbaconnier\LaravelMvcToDdd;

use Cbaconnier\LaravelMvcToDdd\Installers\FortifyInstaller;
use Cbaconnier\LaravelMvcToDdd\Installers\JetstreamInstaller;
use Cbaconnier\LaravelMvcToDdd\Installers\LaravelInstaller;
use Illuminate\Console\Command;
use Illuminate\Support\Composer;

class InstallCommand extends Command
{
    protected $signature = 'ddd:install';

    protected $description = 'Automatically change the default Laravel MVC architecture to DDD architecture';

    public function handle(
        LaravelInstaller $laravelInstaller,
        FortifyInstaller $fortifyInstaller,
        JetstreamInstaller $jetstreamInstaller
    ) {
        $this->info('Installing for Laravel...');
        $laravelInstaller->install();



        if ($fortifyInstaller->enabled()) {
            $this->info('Installing for Fortify...');
            $fortifyInstaller->install();
        }

        if ($jetstreamInstaller->enabled()) {
            $this->info('Installing for Jetstream...');
            $jetstreamInstaller->install();
        }

        $this->info('Running composer dump autoload...');
        $this->updateComposer();

        $this->info('All done!');
    }


    protected function updateComposer()
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        $composer['autoload']['psr-4'] = [
            "App\\"                 => "src/App",
            "Domain\\"              => "src/Domain",
            "Support\\"             => "src/Support",
            "Database\\Factories\\" => "database/factories/",
            "Database\\Seeders\\"   => "database/seeders/",
        ];

        file_put_contents(
            base_path('composer.json'),
            json_encode($composer, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );

        $composerInstance = app(Composer::class);
        $composerInstance->dumpAutoloads();

    }

}