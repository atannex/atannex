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
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\PostTags\PostTagResource;
use App\Filament\Resources\Regions\RegionResource;
use App\Filament\Resources\RegionSectionWidgets\RegionSectionWidgetResource;
use App\Filament\Resources\Reviews\ReviewResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Rulers\RulerResource;
use App\Filament\Resources\Sections\SectionResource;
use App\Filament\Resources\SocialMedia\SocialMediaResource;
use App\Filament\Resources\Tags\TagResource;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Videos\VideoResource;
use App\Filament\Resources\Widgets\WidgetResource;
use Filament\Auth\Pages\Login;
use Filament\Auth\Pages\Register;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
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
            ->login(Login::class)
            ->registration(Register::class)
            ->registration(false)
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->colors([
                'primary' => Color::Violet,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->brandName('ATANNEX')

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
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
                        ->icon('heroicon-o-map')
                        ->items([
                            ...RegionResource::getNavigationItems(),
                            ...SectionResource::getNavigationItems(),
                            ...WidgetResource::getNavigationItems(),
                            ...RegionSectionWidgetResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Categories')
                        ->icon('heroicon-o-rectangle-stack')
                        ->collapsed()
                        ->items([
                            ...CategoryResource::getNavigationItems(),
                            ...CategorySectionResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Modules')
                        ->icon('heroicon-o-cube')
                        ->collapsed()
                        ->items([
                            ...DocumentModuleResource::getNavigationItems(),
                            ...PostModuleResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Posts')
                        ->icon('heroicon-o-newspaper')
                        ->collapsed()
                        ->items([
                            ...PostResource::getNavigationItems(),
                            ...PostTagResource::getNavigationItems(),
                            ...TagResource::getNavigationItems(),
                            ...VideoResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('User Management')
                        ->icon('heroicon-o-users')
                        // ->visible(fn (): bool => auth()->user()->isAdmin())
                        ->collapsed()
                        ->items([
                            ...UserResource::getNavigationItems(),
                            ...RoleResource::getNavigationItems(),
                            ...PermissionResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Organization')
                        ->icon('heroicon-o-building-office')
                        ->collapsed()
                        ->items([
                            ...DepartmentResource::getNavigationItems(),
                            ...EmployeeResource::getNavigationItems(),
                            ...EmployeeDepartmentResource::getNavigationItems(),
                            ...RulerResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Engagement')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->collapsed()
                        ->items([
                            ...CommentResource::getNavigationItems(),
                            ...ReviewResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Documentation')
                        ->icon('heroicon-o-document')
                        ->collapsed()
                        ->items([
                            ...DocumentResource::getNavigationItems(),
                        ]),

                    NavigationGroup::make('Miscellaneous')
                        ->icon('heroicon-o-ellipsis-horizontal-circle')
                        ->collapsed()
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
