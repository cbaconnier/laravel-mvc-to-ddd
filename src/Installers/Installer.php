<?php

namespace Cbaconnier\LaravelMvcToDdd\Installers;

abstract class Installer
{

    abstract public function enabled(): bool;

    abstract public function install(): void;

    /** Replace a given string within a given file. */
    protected function replaceInFile(string $search, string $replace, string $path): void
    {
        try {
            file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
        } catch (\Exception $e) {
            print("Error while writing in file : \"{$path}\"" );
            die;
        }
    }

    /** Replace an array of strings within a given file. */
    protected function replaceAllInFile(array $values, string $path): void
    {
        foreach ($values as $search => $replace) {
            $this->replaceInFile($search, $replace, $path);
        }
    }

}