<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models;
use App\Observers;
use App\Services\TenantService;
use App\Policies;


class AsteriskServiceProvider extends ServiceProvider
{
    /**
     * Registro de mapeamentos de Policy.
     */
    protected $policies = [
        Models\AsteriskHttp::class => Policies\AsteriskHttpPolicy::class,
        Models\Cdr::class => Policies\CdrPolicy::class,
        Models\DialplanExtension::class => Policies\DialplanExtensionPolicy::class,
        Models\Dialplan::class => Policies\DialplanPolicy::class,
        Models\DidNumber::class => Policies\DidNumberPolicy::class,
        Models\Feature::class => Policies\FeaturePolicy::class,
        Models\ManagerUser::class => Policies\ManagerUserPolicy::class,
        Models\PjsipAor::class => Policies\PjsipAorPolicy::class,
        Models\PjsipAuth::class => Policies\PjsipAuthPolicy::class,
        Models\PjsipEndpoint::class => Policies\PjsipEndpointPolicy::class,
        Models\QueueMember::class => Policies\QueueMemberPolicy::class,
        Models\Queue::class => Policies\QueuePolicy::class,
        Models\QueueRule::class => Policies\QueueRulePolicy::class,
        Models\Role::class => Policies\RolePolicy::class,
        Models\Tenant::class => Policies\TenantPolicy::class,
        Models\TenantPermission::class => Policies\TenantPermissionPolicy::class,
        Models\Transport::class => Policies\TransportPolicy::class,
        Models\User::class => Policies\UserPolicy::class,
        Models\VoicemailBox::class => Policies\VoicemailBoxPolicy::class,
        //permission policy mapping
        \Spatie\Permission\Models\Permission::class => \App\Policies\PermissionPolicy::class,
        \Spatie\Permission\Models\Role::class => \App\Policies\RolePolicy::class,
    ];

    /**
     * REGISTER: Registre apenas "promessas". 
     * Não tente usar o banco de dados aqui.
     */
    public function register(): void
    {
        // Unificando o Singleton do TenantService aqui
        $this->app->singleton(TenantService::class, function () {
            return new TenantService();
        });

        // Seu singleton de sessão
        $this->app->singleton('currentTenant', function () {
            return session('current_tenant');
        });
    }

        /**
         * BOOT: Aqui o Laravel já "carregou". 
         * Lugar perfeito para os Observers (Gatilhos do Asterisk).
         */
        public function boot(): void
        {
        // Força o vínculo antes de qualquer outra coisa
        \Illuminate\Support\Facades\Gate::policy(
            \Spatie\Permission\Models\Role::class, 
            \App\Policies\RolePolicy::class
        );
        
        \Illuminate\Support\Facades\Gate::policy(
            \Spatie\Permission\Models\Permission::class, 
            \App\Policies\PermissionPolicy::class
        );

        // Registra o restante do seu array $policies
        foreach ($this->policies as $model => $policy) {
            \Illuminate\Support\Facades\Gate::policy($model, $policy);
        }
        // Vinculando Models aos Observers
        Models\Dialplan::observe(Observers\DialplanObserver::class);
        Models\DidNumber::observe(Observers\DidNumberObserver::class);
        Models\Extension::observe(Observers\ExtensionObserver::class);
        Models\PjsipAor::observe(Observers\PjsipAorObserver::class);
        Models\PjsipAuth::observe(Observers\PjsipAuthObserver::class);
        Models\PjsipEndpoint::observe(Observers\PjsipEndpointObserver::class);
        Models\Queue::observe(Observers\QueueObserver::class);
        Models\QueueMember::observe(Observers\QueueMemberObserver::class);
        Models\Tenant::observe(Observers\TenantObserver::class);
        Models\Transport::observe(Observers\TransportObserver::class);
        Models\VoicemailBox::observe(Observers\VoicemailBoxObserver::class);
        Models\Invite::observe(Observers\InviteObserver::class);
        Models\ManagerUser::observe(Observers\ManagerUserObserver::class);
        Models\Feature::observe(Observers\FeatureObserver::class);
        Models\DialplanExtension::observe(Observers\DialplanExtensionObserver::class);
    }
}