<?php

namespace Cbaconnier\LaravelMvcToDdd\Installers;

use Illuminate\Filesystem\Filesystem;

class LaravelInstaller extends Installer
{

    public function enabled(): bool
    {
        return true;
    }

    public function install(): void
    {
        $this->createDirectories();
        $this->configureFiles();
        $this->createTestDirectories();
        $this->configureTestFiles();
        $this->configurePhpUnitXmlFile();
    }

    protected function createDirectories()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('src'));

        (new Filesystem)->ensureDirectoryExists(base_path('src/App'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/App/Console'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/App/Controllers'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/App/Console/Commands'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/App/Exceptions'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/App/Providers'));

        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/User'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/User/Models'));

        (new Filesystem)->ensureDirectoryExists(base_path('src/Support'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Support/Middleware'));

        (new Filesystem)->ensureDirectoryExists(base_path('database/factories/Domain/User/Models'));
    }

    protected function configureFiles()
    {
        /* Kernel */
        (new Filesystem)->move(base_path('app/Console/Kernel.php'), base_path('src/App/ConsoleKernel.php'));
        $this->replaceAllInFile([
            'namespace App\Console;' => 'namespace App;',
            'class Kernel'           => 'class ConsoleKernel',
            ' as ConsoleKernel'      => '',
            'extends ConsoleKernel'  => 'extends Kernel',
            '/Commands'              => '/Console/Commands',
        ], base_path('src/App/ConsoleKernel.php'));

        (new Filesystem)->move(base_path('app/Http/Kernel.php'), base_path('src/App/HttpKernel.php'));
        $this->replaceAllInFile([
            'namespace App\Http;'  => 'namespace App;',
            'class Kernel'         => 'class HttpKernel',
            '\App\Http\Middleware' => '\Support\Middleware',
            ' as HttpKernel'       => '',
            'extends HttpKernel'   => 'extends Kernel',
        ], base_path('src/App/HttpKernel.php'));

        /* Exceptions */
        (new Filesystem)->move(base_path('app/Exceptions/Handler.php'), base_path('src/App/Exceptions/Handler.php'));

        /* Http */
        (new Filesystem)->move(base_path('app/Http/Controllers/Controller.php'), base_path('src/App/Controllers/Controller.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Controllers;' => 'namespace App\Controllers;',
        ], base_path('src/App/Controllers/Controller.php'));

        /* Middleware */
        (new Filesystem)->move(base_path('app/Http/Middleware/Authenticate.php'), base_path('src/Support/Middleware/Authenticate.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/Authenticate.php'));

        (new Filesystem)->move(base_path('app/Http/Middleware/EncryptCookies.php'), base_path('src/Support/Middleware/EncryptCookies.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/EncryptCookies.php'));

        (new Filesystem)->move(base_path('app/Http/Middleware/PreventRequestsDuringMaintenance.php'), base_path('src/Support/Middleware/PreventRequestsDuringMaintenance.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/PreventRequestsDuringMaintenance.php'));

        (new Filesystem)->move(base_path('app/Http/Middleware/RedirectIfAuthenticated.php'), base_path('src/Support/Middleware/RedirectIfAuthenticated.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/RedirectIfAuthenticated.php'));

        (new Filesystem)->move(base_path('app/Http/Middleware/TrimStrings.php'), base_path('src/Support/Middleware/TrimStrings.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/TrimStrings.php'));

        (new Filesystem)->move(base_path('app/Http/Middleware/TrustHosts.php'), base_path('src/Support/Middleware/TrustHosts.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/TrustHosts.php'));

        (new Filesystem)->move(base_path('app/Http/Middleware/TrustProxies.php'), base_path('src/Support/Middleware/TrustProxies.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/TrustProxies.php'));

        (new Filesystem)->move(base_path('app/Http/Middleware/VerifyCsrfToken.php'), base_path('src/Support/Middleware/VerifyCsrfToken.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/VerifyCsrfToken.php'));

        /* Models */
        (new Filesystem)->move(base_path('app/Models/User.php'), base_path('src/Domain/User/Models/User.php'));
        $this->replaceAllInFile([
            'namespace App\Models;' => 'namespace Domain\User\Models;',
        ], base_path('src/Domain/User/Models/User.php'));

        (new Filesystem)->move(base_path('database/factories/UserFactory.php'), base_path('database/factories/Domain/User/Models/UserFactory.php'));
        $this->replaceAllInFile([
            'namespace Database\Factories' => 'namespace Database\Factories\Domain\User\Models',
            'App\Models\User'              => 'Domain\User\Models\User',
        ], base_path('database/factories/Domain/User/Models/UserFactory.php'));

        /* Providers */
        (new Filesystem)->move(base_path('app/Providers/AppServiceProvider.php'), base_path('src/App/Providers/AppServiceProvider.php'));
        (new Filesystem)->move(base_path('app/Providers/AuthServiceProvider.php'), base_path('src/App/Providers/AuthServiceProvider.php'));
        (new Filesystem)->move(base_path('app/Providers/BroadcastServiceProvider.php'), base_path('src/App/Providers/BroadcastServiceProvider.php'));
        (new Filesystem)->move(base_path('app/Providers/EventServiceProvider.php'), base_path('src/App/Providers/EventServiceProvider.php'));
        (new Filesystem)->move(base_path('app/Providers/RouteServiceProvider.php'), base_path('src/App/Providers/RouteServiceProvider.php'));

        /* Application */
        copy(__DIR__ . '/../../stubs/Application.php', base_path('src/App/Application.php'));

        /* Bootstrap */
        $this->replaceAllInFile([
            '$app = new Illuminate\Foundation\Application(
    $_ENV[\'APP_BASE_PATH\'] ?? dirname(__DIR__)
);'                                     => 'use App\Application;

$app = new Illuminate\Foundation\Application(
    $_ENV[\'APP_BASE_PATH\'] ?? dirname(__DIR__)
);
$app->useAppPath($app->basePath(\'src/App\'));',
            'App\Http\Kernel::class'    => 'App\HttpKernel::class',
            'App\Console\Kernel::class' => 'App\ConsoleKernel::class',
        ], base_path('bootstrap/app.php'));

        /* Config */
        $this->replaceAllInFile([
            'App\Models\User::class' => 'Domain\User\Models\User::class',
        ], base_path('config/auth.php'));

        /* Route */
        $this->replaceAllInFile([
            'App.Models.User.{id}' => 'Domain.User.Models.User.{id}',
        ], base_path('routes/channels.php'));
    }

    protected function createTestDirectories()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('tests/App'));
        (new Filesystem)->ensureDirectoryExists(base_path('tests/Domain'));
        (new Filesystem)->ensureDirectoryExists(base_path('tests/Support'));
    }

    protected function configureTestFiles()
    {
        (new Filesystem)->move(base_path('tests/Feature/ExampleTest.php'), base_path('tests/App/ExampleTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature' => 'namespace Tests\App',
        ], base_path('tests/App/ExampleTest.php'));

        (new Filesystem)->copy(base_path('tests/Unit/ExampleTest.php'), base_path('tests/Domain/ExampleTest.php'));
        (new Filesystem)->move(base_path('tests/Unit/ExampleTest.php'), base_path('tests/Support/ExampleTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Unit' => 'namespace Tests\Domain',
        ], base_path('tests/Domain/ExampleTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Unit' => 'namespace Tests\Support',
        ], base_path('tests/Support/ExampleTest.php'));
    }

    protected function configurePhpUnitXmlFile()
    {
        $this->replaceAllInFile([
            '<testsuite name="Unit">
            <directory suffix="Test.php">./tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory suffix="Test.php">./tests/Feature</directory>
        </testsuite>' => '<testsuite name="App">
            <directory suffix="Test.php">./tests/App</directory>
        </testsuite>
        <testsuite name="Domain">
            <directory suffix="Test.php">./tests/Domain</directory>
        </testsuite>
        <testsuite name="Support">
            <directory suffix="Test.php">./tests/Support</directory>
        </testsuite>',
        ], base_path('phpunit.xml'));

    }
}