<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Abouts\AboutResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\CategorySections\CategorySectionResource;
use App\Filament\Resources\Colors\ColorResource;
use App\Filament\Resources\Comments\CommentResource;
use App\Filament\Resources\Departments\DepartmentResource;
use App\Filament\Resources\DocumentModules\DocumentModuleResource;
use App\Filament\Resources\Documents\DocumentResource;
use App\Filament\Resources\EmployeeDepartments\EmployeeDepartmentResource;
use App\Filament\Resources\Employees\EmployeeResource;
use App\Filament\Resources\Galleries\GalleryResource;
use App\Filament\Resources\Permissions\PermissionResource;
use App\Filament\Resources\PostModules\PostModuleResource;
use App\Filament\Resources\PostRegions\PostRegionResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\PostTags\PostTagResource;
use App\Filament\Resources\Regions\RegionResource;
use App\Filament\Resources\RegionSectionWidgets\RegionSectionWidgetResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Rulers\RulerResource;
use App\Filament\Resources\Sections\SectionResource;
use App\Filament\Resources\SocialMedia\SocialMediaResource;
use App\Filament\Resources\Tags\TagResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Widgets\WidgetResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Violet,
            ])
            ->topNavigation()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])

            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([

                    NavigationGroup::make('Regions & Sections')
                        ->items([
                            ...RegionResource::getNavigationItems(),
                            ...SectionResource::getNavigationItems(),
                            ...WidgetResource::getNavigationItems(),
                            ...RegionSectionWidgetResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Categories & Modules')
                        ->items([
                            ...CategoryResource::getNavigationItems(),
                            ...CategorySectionResource::getNavigationItems(),
                            ...DocumentModuleResource::getNavigationItems(),
                            ...PostModuleResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Posts')
                        ->items([
                            ...PostResource::getNavigationItems(),
                            ...PostRegionResource::getNavigationItems(),
                            ...PostTagResource::getNavigationItems(),
                            ...TagResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('User Management')
                        ->items([
                            ...UserResource::getNavigationItems(),
                            ...RoleResource::getNavigationItems(),
                            ...PermissionResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Organization')
                        ->items([
                            ...DepartmentResource::getNavigationItems(),
                            ...EmployeeResource::getNavigationItems(),
                            ...EmployeeDepartmentResource::getNavigationItems(),
                            ...RulerResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Engagement')
                        ->items([
                            ...CommentResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Documentation')
                        ->items([
                            ...DocumentResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Miscellaneous')
                        ->items([
                            ...AboutResource::getNavigationItems(),
                            ...ColorResource::getNavigationItems(),
                            ...GalleryResource::getNavigationItems(),
                            ...SocialMediaResource::getNavigationItems(),
                        ]),
                ]);
            });
    }
}
