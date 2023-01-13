<?php

namespace Cbaconnier\LaravelMvcToDdd\Installers;

use Illuminate\Filesystem\Filesystem;

class FortifyInstaller extends Installer
{

    public function enabled(): bool
    {
        return file_exists(base_path('config/fortify.php'));
    }

    public function install(): void
    {
        $this->createDirectories();
        $this->configureFiles();
    }

    protected function createDirectories()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/User/Actions'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/User/Rules'));
    }


    protected function configureFiles()
    {
        /* Actions */
        (new Filesystem)->move(base_path('app/Actions/Fortify/CreateNewUser.php'), base_path('src/Domain/User/Actions/CreateNewUserAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify' => 'namespace Domain\User\Actions',
            'use App\Models\User;'          => 'use Domain\User\Models\User;
use Domain\User\Rules\PasswordValidationRules;',
            'class CreateNewUser'           => 'class CreateNewUserAction',
            '@return \App\Models\User'      => '@return \Domain\User\Models\User',
        ], base_path('src/Domain/User/Actions/CreateNewUserAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Fortify/ResetUserPassword.php'), base_path('src/Domain/User/Actions/ResetUserPasswordAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify'                      => 'namespace Domain\User\Actions',
            'App\Models\User'                                    => 'Domain\User\Models\User',
            'class ResetUserPassword'                            => 'class ResetUserPasswordAction',
            'use Laravel\Fortify\Contracts\ResetsUserPasswords;' => 'use Laravel\Fortify\Contracts\ResetsUserPasswords;
use Domain\User\Rules\PasswordValidationRules;',
        ], base_path('src/Domain/User/Actions/ResetUserPasswordAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Fortify/UpdateUserPassword.php'), base_path('src/Domain/User/Actions/UpdateUserPasswordAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify' => 'namespace Domain\User\Actions',
            'class UpdateUserPassword'      => 'class UpdateUserPasswordAction',
            'App\Models\User'               => 'Domain\User\Models\User',

            'use Illuminate\Support\Facades\Hash;' => 'use Domain\User\Rules\PasswordValidationRules;
use Illuminate\Support\Facades\Hash;',
        ], base_path('src/Domain/User/Actions/UpdateUserPasswordAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Fortify/UpdateUserProfileInformation.php'), base_path('src/Domain/User/Actions/UpdateUserProfileInformationAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify'      => 'namespace Domain\User\Actions',
            'App\Models\User'                    => 'Domain\User\Models\User',
            'class UpdateUserProfileInformation' => 'class UpdateUserProfileInformationAction',
        ], base_path('src/Domain/User/Actions/UpdateUserProfileInformationAction.php'));

        /* Actions -> Rules */
        (new Filesystem)->move(base_path('app/Actions/Fortify/PasswordValidationRules.php'), base_path('src/Domain/User/Rules/PasswordValidationRules.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Fortify' => 'namespace Domain\User\Rules',
        ], base_path('src/Domain/User/Rules/PasswordValidationRules.php'));

        /* Providers */
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
    }


}