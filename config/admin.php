<?php

use App\Admin\Modules;

return [

    /*
    | Admin CRUD modules, in menu order. Each is served by CrudController
    | at /admin/{key} and guarded by "{key}.view|create|update|delete".
    */
    'modules' => [
        Modules\ProjectModule::class,
        Modules\ClientModule::class,
        Modules\TestimonialModule::class,
        Modules\ServiceModule::class,
        Modules\SolutionModule::class,
        Modules\SolutionFeatureGroupModule::class,
        Modules\SolutionFeatureModule::class,
        Modules\PricingPlanModule::class,
        Modules\StatModule::class,
        Modules\FaqModule::class,
        Modules\TeamMemberModule::class,
        Modules\PageModule::class,
        Modules\PostModule::class,
        Modules\PostCategoryModule::class,
        Modules\TagModule::class,
        Modules\IndustryModule::class,
        Modules\TechnologyModule::class,
        Modules\LandingPageModule::class,
        Modules\RedirectModule::class,
        Modules\UserModule::class,
    ],

    /*
    | Roles and what they may do. "*" grants every action on a resource.
    | Super Admin bypasses all checks (Gate::before).
    */
    'roles' => [
        'Super Admin' => ['*'],
        'Admin' => ['content', 'leads', 'seo', 'settings', 'dashboard', 'activity'],
        'Editor' => ['content', 'seo', 'dashboard'],
        'Sales' => ['leads', 'dashboard'],
    ],

    // Permission groups used above. Module keys not listed belong to "content".
    'groups' => [
        'seo' => ['landing-pages', 'redirects'],
        'system' => ['users'],
    ],

    // Non-CRUD permissions.
    'abilities' => [
        'dashboard' => ['dashboard.view'],
        'leads' => ['leads.view', 'leads.update', 'leads.delete', 'leads.export'],
        'settings' => ['settings.view', 'settings.update'],
        'activity' => ['activity.view'],
        'system' => ['system.manage'],
    ],

];
