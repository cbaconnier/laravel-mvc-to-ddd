<?php

namespace Cbaconnier\LaravelMvcToDdd\Installers;

use Illuminate\Filesystem\Filesystem;

class JetstreamInstaller extends Installer
{

    public function enabled(): bool
    {
        return file_exists(base_path('config/jetstream.php'));
    }

    protected function stackIs(string $stack): bool
    {
        $config = include base_path('config/jetstream.php');
        return $config['stack'] === $stack;
    }

    protected function isTeamEnabled(): bool
    {
        return file_exists(base_path('app/Models/Team.php'));
    }


    public function install(): void
    {

        $this->configureBaseFiles();

        if ($this->stackIs('livewire')) {
            $this->createLivewireDirectories();
            $this->configureLivewireFiles();
        }

        if ($this->stackIs('inertia')) {
            $this->configureInertiaFiles();
        }

        if ($this->isTeamEnabled()) {
            $this->createTeamDirectories();
            $this->configureTeamFiles();

            $this->createTeamTestDirectories();
            $this->configureTeamTestFiles();
        }

        $this->createTestDirectories();
        $this->configureTestFiles();
    }

    protected function configureBaseFiles()
    {
        /* Actions */
        (new Filesystem)->move(base_path('app/Actions/Jetstream/DeleteUser.php'), base_path('src/Domain/User/Actions/DeleteUserAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Jetstream;' => 'namespace Domain\User\Actions;',
            'App\Models\Team'                  => 'Domain\Team\Models\Team',
            'App\Models\User'                  => 'Domain\User\Models\User',
            'class DeleteUser'                 => 'class DeleteUserAction',
        ], base_path('src/Domain/User/Actions/DeleteUserAction.php'));

        /* Providers */
        (new Filesystem)->move(base_path('app/Providers/JetstreamServiceProvider.php'), base_path('src/App/Providers/JetstreamServiceProvider.php'));
        $this->replaceAllInFile([
            'use App\Actions\Jetstream\DeleteUser;'   => 'use Domain\User\Actions\DeleteUserAction;',
            'use Illuminate\Support\ServiceProvider;' => 'use Domain\User\Models\User;
use Illuminate\Support\ServiceProvider;',
            'DeleteUser::class'                       => 'DeleteUserAction::class',
            '$this->configurePermissions();'          => '$this->configurePermissions();

        Jetstream::useUserModel(User::class);
',
        ], base_path('src/App/Providers/JetstreamServiceProvider.php'));


    }

    protected function createLivewireDirectories()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('src/App/View'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/App/View/Components'));
    }

    protected function configureLivewireFiles()
    {
        /* Views */
        (new Filesystem)->move(base_path('app/View/Components/AppLayout.php'), base_path('src/App/View/Components/AppLayout.php'));
        (new Filesystem)->move(base_path('app/View/Components/GuestLayout.php'), base_path('src/App/View/Components/GuestLayout.php'));
    }

    protected function configureInertiaFiles()
    {
        (new Filesystem)->move(base_path('app/Http/Middleware/HandleInertiaRequests.php'), base_path('src/Support/Middleware/HandleInertiaRequests.php'));
        $this->replaceAllInFile([
            'namespace App\Http\Middleware;' => 'namespace Support\Middleware;',
        ], base_path('src/Support/Middleware/HandleInertiaRequests.php'));
    }

    protected function createTeamDirectories()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/Team'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/Team/Models'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/Team/Actions'));
        (new Filesystem)->ensureDirectoryExists(base_path('src/Domain/Team/Policies'));
        (new Filesystem)->ensureDirectoryExists(base_path('database/factories/Domain/Team/Models'));
    }

    protected function configureTeamFiles()
    {
        /* Actions */
        (new Filesystem)->move(base_path('app/Actions/Jetstream/AddTeamMember.php'), base_path('src/Domain/Team/Actions/AddTeamMemberAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Jetstream;' => 'namespace Domain\Team\Actions;',
            'App\Models\Team'                  => 'Domain\Team\Models\Team',
            'App\Models\User'                  => 'Domain\User\Models\User',
            'class AddTeamMember'              => 'class AddTeamMemberAction',
        ], base_path('src/Domain/Team/Actions/AddTeamMemberAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Jetstream/CreateTeam.php'), base_path('src/Domain/Team/Actions/CreateTeamAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Jetstream;' => 'namespace Domain\Team\Actions;',
            'App\Models\Team'                  => 'Domain\Team\Models\Team',
            'App\Models\User'                  => 'Domain\User\Models\User',
            'class CreateTeam'                 => 'class CreateTeamAction',
        ], base_path('src/Domain/Team/Actions/CreateTeamAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Jetstream/DeleteTeam.php'), base_path('src/Domain/Team/Actions/DeleteTeamAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Jetstream;' => 'namespace Domain\Team\Actions;',
            'App\Models\Team'                  => 'Domain\Team\Models\Team',
            'class DeleteTeam'                 => 'class DeleteTeamAction',
        ], base_path('src/Domain/Team/Actions/DeleteTeamAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Jetstream/InviteTeamMember.php'), base_path('src/Domain/Team/Actions/InviteTeamMemberAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Jetstream;' => 'namespace Domain\Team\Actions;',
            'App\Models\Team'                  => 'Domain\Team\Models\Team',
            'App\Models\User'                  => 'Domain\User\Models\User',
            'class InviteTeamMember'           => 'class InviteTeamMemberAction',
        ], base_path('src/Domain/Team/Actions/InviteTeamMemberAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Jetstream/RemoveTeamMember.php'), base_path('src/Domain/Team/Actions/RemoveTeamMemberAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Jetstream;' => 'namespace Domain\Team\Actions;',
            'App\Models\Team'                  => 'Domain\Team\Models\Team',
            'App\Models\User'                  => 'Domain\User\Models\User',
            'class RemoveTeamMember'           => 'class RemoveTeamMemberAction',
        ], base_path('src/Domain/Team/Actions/RemoveTeamMemberAction.php'));

        (new Filesystem)->move(base_path('app/Actions/Jetstream/UpdateTeamName.php'), base_path('src/Domain/Team/Actions/UpdateTeamNameAction.php'));
        $this->replaceAllInFile([
            'namespace App\Actions\Jetstream;' => 'namespace Domain\Team\Actions;',
            'class UpdateTeamName'             => 'class UpdateTeamNameAction',
            'App\Models\Team'                  => 'Domain\Team\Models\Team',
            'App\Models\User'                  => 'Domain\User\Models\User',
        ], base_path('src/Domain/Team/Actions/UpdateTeamNameAction.php'));

        /* Models */
        (new Filesystem)->move(base_path('app/Models/Membership.php'), base_path('src/Domain/Team/Models/Membership.php'));
        $this->replaceAllInFile([
            'namespace App\Models;' => 'namespace Domain\Team\Models;',
        ], base_path('src/Domain/Team/Models/Membership.php'));

        (new Filesystem)->move(base_path('app/Models/Team.php'), base_path('src/Domain/Team/Models/Team.php'));
        $this->replaceAllInFile([
            'namespace App\Models;' => 'namespace Domain\Team\Models;',
        ], base_path('src/Domain/Team/Models/Team.php'));

        (new Filesystem)->move(base_path('app/Models/TeamInvitation.php'), base_path('src/Domain/Team/Models/TeamInvitation.php'));
        $this->replaceAllInFile([
            'namespace App\Models;' => 'namespace Domain\Team\Models;',
        ], base_path('src/Domain/Team/Models/TeamInvitation.php'));

        /* Policies */
        (new Filesystem)->move(base_path('app/Policies/TeamPolicy.php'), base_path('src/Domain/Team/Policies/TeamPolicy.php'));
        $this->replaceAllInFile([
            'namespace App\Policies;' => 'namespace Domain\Team\Policies;',
            'App\Models\Team'         => 'Domain\Team\Models\Team',
            'App\Models\User'         => 'Domain\User\Models\User',
        ], base_path('src/Domain/Team/Policies/TeamPolicy.php'));

        /* Providers */
        $this->replaceAllInFile([
            'use App\Actions\Jetstream\AddTeamMember;'    => 'use Domain\Team\Actions\AddTeamMemberAction;',
            'use App\Actions\Jetstream\CreateTeam;'       => 'use Domain\Team\Actions\CreateTeamAction;',
            'use App\Actions\Jetstream\DeleteTeam;'       => 'use Domain\Team\Actions\DeleteTeamAction;',
            'use App\Actions\Jetstream\InviteTeamMember;' => 'use Domain\Team\Actions\InviteTeamMemberAction;',
            'use App\Actions\Jetstream\RemoveTeamMember;' => 'use Domain\Team\Actions\RemoveTeamMemberAction;',
            'use App\Actions\Jetstream\UpdateTeamName;'   => 'use Domain\Team\Actions\UpdateTeamNameAction;',

            'use Illuminate\Support\ServiceProvider;' => 'use Domain\Team\Models\Membership;
use Domain\Team\Models\Team;
use Domain\Team\Models\TeamInvitation;
use Illuminate\Support\ServiceProvider;',

            'CreateTeam::class'       => 'CreateTeamAction::class',
            'UpdateTeamName::class'   => 'UpdateTeamNameAction::class',
            'AddTeamMember::class'    => 'AddTeamMemberAction::class',
            'InviteTeamMember::class' => 'InviteTeamMemberAction::class',
            'RemoveTeamMember::class' => 'RemoveTeamMemberAction::class',
            'DeleteTeam::class'       => 'DeleteTeamAction::class',

            '$this->configurePermissions();' => '$this->configurePermissions();

        Jetstream::useMembershipModel(Membership::class);
        Jetstream::useTeamModel(Team::class);
        Jetstream::useTeamInvitationModel(TeamInvitation::class);
',
        ], base_path('src/App/Providers/JetstreamServiceProvider.php'));


        /* Factories */
        (new Filesystem)->move(base_path('database/factories/TeamFactory.php'), base_path('database/factories/Domain/Team/Models/TeamFactory.php'));
        $this->replaceAllInFile([
            'namespace Database\Factories;' => 'namespace Database\Factories\Domain\Team\Models;',
            'App\Models\User'               => 'Domain\User\Models\User',
            'App\Models\Team'               => 'Domain\Team\Models\Team',
        ], base_path('database/factories/Domain/Team/Models/TeamFactory.php'));

        /* Fortify modifiers */
        /* Actions */
        $this->replaceAllInFile([
            'App\Models\Team' => 'Domain\Team\Models\Team;',
        ], base_path('src/Domain/User/Actions/CreateNewUserAction.php'));

        /* Factories */
        $this->replaceAllInFile([
            'App\Models\Team' => 'Domain\Team\Models\Team;',
        ], base_path('database/factories/Domain/User/Models/UserFactory.php'));


    }

    protected function createTeamTestDirectories()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('tests/App/Teams'));
    }

    protected function configureTeamTestFiles()
    {
        (new Filesystem)->move(base_path('tests/Feature/CreateTeamTest.php'), base_path('tests/App/Teams/CreateTeamTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Teams;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Teams/CreateTeamTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/DeleteTeamTest.php'), base_path('tests/App/Teams/DeleteTeamTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Teams;',
            'App\Models\User'          => 'Domain\User\Models\User',
            'App\Models\Team'          => 'Domain\Team\Models\Team',
        ], base_path('tests/App/Teams/DeleteTeamTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/InviteTeamMemberTest.php'), base_path('tests/App/Teams/InviteTeamMemberTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Teams;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Teams/InviteTeamMemberTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/LeaveTeamTest.php'), base_path('tests/App/Teams/LeaveTeamTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Teams;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Teams/LeaveTeamTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/RemoveTeamMemberTest.php'), base_path('tests/App/Teams/RemoveTeamMemberTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Teams;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Teams/RemoveTeamMemberTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/UpdateTeamMemberRoleTest.php'), base_path('tests/App/Teams/UpdateTeamMemberRoleTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Teams;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Teams/UpdateTeamMemberRoleTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/UpdateTeamNameTest.php'), base_path('tests/App/Teams/UpdateTeamNameTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Teams;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Teams/UpdateTeamNameTest.php'));
    }

    protected function createTestDirectories()
    {
        (new Filesystem)->ensureDirectoryExists(base_path('tests/App/ApiTokens'));
        (new Filesystem)->ensureDirectoryExists(base_path('tests/App/Auth'));
        (new Filesystem)->ensureDirectoryExists(base_path('tests/App/Profile'));
    }

    protected function configureTestFiles()
    {
        /* ApiTokens */
        (new Filesystem)->move(base_path('tests/Feature/ApiTokenPermissionsTest.php'), base_path('tests/App/ApiTokens/ApiTokenPermissionsTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\ApiTokens;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/ApiTokens/ApiTokenPermissionsTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/CreateApiTokenTest.php'), base_path('tests/App/ApiTokens/CreateApiTokenTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\ApiTokens;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/ApiTokens/CreateApiTokenTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/DeleteApiTokenTest.php'), base_path('tests/App/ApiTokens/DeleteApiTokenTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\ApiTokens;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/ApiTokens/DeleteApiTokenTest.php'));

        /* Auth */
        (new Filesystem)->move(base_path('tests/Feature/AuthenticationTest.php'), base_path('tests/App/Auth/AuthenticationTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Auth;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Auth/AuthenticationTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/EmailVerificationTest.php'), base_path('tests/App/Auth/EmailVerificationTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Auth;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Auth/EmailVerificationTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/PasswordConfirmationTest.php'), base_path('tests/App/Auth/PasswordConfirmationTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Auth;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Auth/PasswordConfirmationTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/PasswordResetTest.php'), base_path('tests/App/Auth/PasswordResetTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Auth;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Auth/PasswordResetTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/RegistrationTest.php'), base_path('tests/App/Auth/RegistrationTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Auth;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Auth/RegistrationTest.php'));

        /* Profile */
        (new Filesystem)->move(base_path('tests/Feature/BrowserSessionsTest.php'), base_path('tests/App/Profile/BrowserSessionsTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Profile;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Profile/BrowserSessionsTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/DeleteAccountTest.php'), base_path('tests/App/Profile/DeleteAccountTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Profile;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Profile/DeleteAccountTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/ProfileInformationTest.php'), base_path('tests/App/Profile/ProfileInformationTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Profile;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Profile/ProfileInformationTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/TwoFactorAuthenticationSettingsTest.php'), base_path('tests/App/Profile/TwoFactorAuthenticationSettingsTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Profile;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Profile/TwoFactorAuthenticationSettingsTest.php'));

        (new Filesystem)->move(base_path('tests/Feature/UpdatePasswordTest.php'), base_path('tests/App/Profile/UpdatePasswordTest.php'));
        $this->replaceAllInFile([
            'namespace Tests\Feature;' => 'namespace Tests\App\Profile;',
            'App\Models\User'          => 'Domain\User\Models\User',
        ], base_path('tests/App/Profile/UpdatePasswordTest.php'));
    }


}