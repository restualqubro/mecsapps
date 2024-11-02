<?php

namespace App\Providers\Filament;

use App\Livewire\MyProfileExtended;
use App\Settings\GeneralSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Print\ServiceReceipt;
use App\Http\Controllers\Print\SelesaiReceipt;
use App\Http\Controllers\Print\InvoiceReceipt;
use App\Http\Controllers\Print\FakturJual;
use App\Http\Controllers\Print\FakturPreorder;
use App\Http\Controllers\Print\StockMinus;
use App\Http\Controllers\Print\ServiceData;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel            
            ->default()
            ->id('admin')
            ->path('')
            ->login()            
            ->favicon(fn (GeneralSettings $settings) => Storage::url($settings->site_favicon))
            ->brandName(fn (GeneralSettings $settings) => $settings->brand_name)
            ->brandLogo(fn (GeneralSettings $settings) => Storage::url($settings->brand_logo))
            ->brandLogoHeight(fn (GeneralSettings $settings) => $settings->brand_logoHeight)
            ->colors(fn (GeneralSettings $settings) => $settings->site_theme)
            ->maxContentWidth('full')   
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->navigationGroups([
                NavigationGroup::make()
                     ->label('Services'),
                NavigationGroup::make()
                     ->label('Transactions'),
                    NavigationGroup::make()
                     ->label('Finances'),
                NavigationGroup::make()
                     ->label('Stocks'),                
                NavigationGroup::make()
                     ->label('Connects'),
                    NavigationGroup::make()
                     ->label('Retur'),
                NavigationGroup::make()
                    ->label('Access'),                    
                NavigationGroup::make()                    
                    ->label('Settings')

            ])
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->routes(function () {
                //  add to /portal/*
                Route::post('/whatsapp', function () {
                    return redirect()->away('wa.me');
                });     
                Route::get('/print/servicereceipt/{id}', [ServiceReceipt::class, 'print']);
                Route::get('/print/selesaireceipt', [SelesaiReceipt::class, 'print']);
                Route::get('/print/invoicereceipt/{id}', [InvoiceReceipt::class, 'print']);
                Route::get('/print/fakturjual/{id}', [FakturJual::class, 'print']);
                Route::get('/print/fakturpreorder/{id}', [FakturPreorder::class, 'print']);
                Route::get('/print/reportstockminus/', [StockMinus::class, 'print']);
                Route::get('/print/reportservicedata/', [ServiceData::class, 'print']);
            })
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'sm' => 1
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        shouldRegisterNavigation: false,
                        navigationGroup: 'Settings',
                        hasAvatars: true,
                        slug: 'my-profile'
                    )
                    ->myProfileComponents([
                        'personal_info' => MyProfileExtended::class,
                    ]),
            ]);
    }
}
