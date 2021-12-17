<?php

namespace Cbaconnier\LaravelMvcToDdd\Installers;

use Illuminate\Filesystem\Filesystem;

class PestInstaller extends Installer
{

    public function enabled(): bool
    {
        return file_exists(base_path('tests/Pest.php'));
    }

    public function install(): void
    {
        $this->configureFiles();
    }


    protected function configureFiles()
    {
        /* Actions */

        $this->replaceAllInFile([
            "->in('Feature')" => "->in('App', 'Domain', 'Support')",

        ], base_path('tests/Pest.php'));

    }


}