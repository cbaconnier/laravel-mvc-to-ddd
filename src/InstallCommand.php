<?php

namespace Cbaconnier\LaravelMvcToDdd;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;

class InstallCommand extends Command
{
    protected $signature = 'ddd:install';

    protected $description = 'Automatically change the default Laravel MVC architecture to DDD architecture';

    public function handle()
    {
        $this->createBaseDirectory();
        $this->installBaseFiles();

        if (file_exists(base_path('config/fortify.php'))) {
            $this->installForitfyBaseDirectory();
            $this->installForitfyBaseFiles();
        }

        $this->updateComposer();
    }

    protected function createBaseDirectory()
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

    protected function installBaseFiles()
    {
        (new Filesystem)->move(base_path('app/Console/Kernel.php'), base_path('src/App/ConsoleKernel.php'));
        $this->replaceAllInFile([
            'namespace App\Console;' => 'namespace App;',
            'class Kernel'           => 'class ConsoleKernel',
            ' as ConsoleKernel'      => '',
            'extends ConsoleKernel'  => 'extends Kernel',
        ], base_path('src/App/ConsoleKernel.php'));

        /* Exceptions */
        (new Filesystem)->move(base_path('app/Exceptions/Handler.php'), base_path('src/App/Exceptions/Handler.php'));

        /* Http */
        (new Filesystem)->move(base_path('app/Http/Controllers/Controller.php'), base_path('src/App/Controllers/Controller.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Controllers;' => 'namespace App\Controllers;',
        ], base_path('src/App/Controllers/Controller.php'));


        (new Filesystem)->move(base_path('app/Http/Kernel.php'), base_path('src/App/HttpKernel.php'));
        $this->replaceAllInFile([
            'namespace App\Http;'  => 'namespace App;',
            'class Kernel'         => 'class HttpKernel',
            '\App\Http\Middleware' => '\Support\Middleware',
            ' as HttpKernel'       => '',
            'extends HttpKernel'   => 'extends Kernel',
        ], base_path('src/App/HttpKernel.php'));

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


        // Create files (Application.php)
        copy(__DIR__ . '/../stubs/Application.php', base_path('src/App/Application.php'));


        // Updates other files
        $this->replaceAllInFile([
            '$app = new Illuminate\Foundation\Application(
    $_ENV[\'APP_BASE_PATH\'] ?? dirname(__DIR__)
);'                                     => 'use App\Application;

$app = (new Application(
    $_ENV[\'APP_BASE_PATH\'] ?? dirname(__DIR__)
))->useAppPath(\'src/App\');',
            'App\Http\Kernel::class'    => 'App\HttpKernel::class',
            'App\Console\Kernel::class' => 'App\ConsoleKernel::class',
        ], base_path('bootstrap/app.php'));


        $this->replaceAllInFile([
            'App\Models\User::class' => 'Domain\User\Models\User::class',
        ], base_path('config/auth.php'));


        $this->replaceAllInFile([
            'App.Models.User.{id}' => 'Domain.User.Models.User.{id}',
        ], base_path('routes/channels.php'));
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

    protected function installForitfyBaseDirectory()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/User/Actions'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/User/Rules'));
    }

    protected function installForitfyBaseFiles()
    {
        (new Filesystem)->move(base_path('app/Actions/Fortify/CreateNewUser.php'), base_path('src/Domain/User/Actions/CreateNewUserAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify' => 'namespace Domain\User\Actions',
            'use App\Models\User;'          => 'use Domain\User\Models\User;
use Domain\User\Rules\PasswordValidationRules;',
            'class CreateNewUser'           => 'class CreateNewUserAction',
            '@return \App\Models\User'      => '@return \Domain\User\Models\User',
        ], base_path('src/Domain/User/Actions/CreateNewUserAction.php'));


        (new Filesystem)->move(base_path('app/Providers/FortifyServiceProvider.php'), base_path('src/App/Providers/FortifyServiceProvider.php'));
        $this->replaceAllInFile([
            'App\Actions\Fortify\CreateNewUser'                => 'Domain\User\Actions\CreateNewUserAction',
            'App\Actions\Fortify\ResetUserPassword'            => 'Domain\User\Actions\ResetUserPasswordAction',
            'App\Actions\Fortify\UpdateUserPassword'           => 'Domain\User\Actions\UpdateUserPasswordAction',
            'App\Actions\Fortify\UpdateUserProfileInformation' => 'Domain\User\Actions\UpdateUserProfileInformationAction',
            'CreateNewUser::class'                             => 'CreateNewUserAction::class',
            'UpdateUserProfileInformation::class'              => 'UpdateUserProfileInformationAction::class',
            'UpdateUserPassword::class'                        => 'UpdateUserPasswordAction::class',
            'ResetUserPassword::class'                         => 'ResetUserPasswordAction::class',
        ], base_path('src/App/Providers/FortifyServiceProvider.php'));

        (new Filesystem)->move(base_path('app/Actions/Fortify/PasswordValidationRules.php'), base_path('src/Domain/User/Rules/PasswordValidationRules.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify' => 'namespace Domain\User\Rules',
        ], base_path('src/Domain/User/Rules/PasswordValidationRules.php'));

        (new Filesystem)->move(base_path('app/Actions/Fortify/ResetUserPassword.php'), base_path('src/Domain/User/Actions/ResetUserPasswordAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify' => 'namespace Domain\User\Actions',
            'class ResetUserPassword'       => 'class ResetUserPasswordAction',
            'use Laravel\Fortify\Contracts\ResetsUserPasswords;' => 'use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Domain\User\Rules\PasswordValidationRules;'
        ], base_path('src/Domain/User/Actions/ResetUserPasswordAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Fortify/UpdateUserPassword.php'), base_path('src/Domain/User/Actions/UpdateUserPasswordAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify'        => 'namespace Domain\User\Actions',
            'class UpdateUserPassword'             => 'class UpdateUserPasswordAction',
            'use Illuminate\Support\Facades\Hash;' => 'use Domain\User\Rules\PasswordValidationRules;
use Illuminate\Support\Facades\Hash;',
        ], base_path('src/Domain/User/Actions/UpdateUserPasswordAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Fortify/UpdateUserProfileInformation.php'), base_path('src/Domain/User/Actions/UpdateUserProfileInformationAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify'        => 'namespace Domain\User\Actions',
            'class UpdateUserProfileInformation'   => 'class UpdateUserProfileInformationAction',
        ], base_path('src/Domain/User/Actions/UpdateUserProfileInformationAction.php'));

    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     * @return void
     */
    protected function replaceInFile($search, $replace, $path)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }

    /**
     * Replace a given string within a given file.
     *
     * @param array $values
     * @param string $path
     * @return void
     */
    protected function replaceAllInFile($values, $path)
    {
        foreach ($values as $search => $replace) {
            $this->replaceInFile($search, $replace, $path);
        }
    }

}