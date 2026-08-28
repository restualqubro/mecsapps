<?php

namespace App\Filament\Pages\Setting;

use App\Settings\GeneralSettings;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;

use function Filament\Support\is_app_url;

class ManageGeneral extends SettingsPage
{
    use HasPageShield;

    protected static string $settings = GeneralSettings::class;

    protected static ?int $navigationSort = 99;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    // public string $themePath = '';

    // public string $twConfigPath = '';

    // public function mount(): void
    // {
    //     $this->themePath = resource_path('css/filament/admin/theme.css');
    //     $this->twConfigPath = resource_path('css/filament/admin/tailwind.config.js');

    //     $this->fillForm();
    // }

    protected function fillForm(): void
    {
        $settings = app(static::getSettings());

        $data = $this->mutateFormDataBeforeFill($settings->toArray());

        // $fileService = new FileService;

        // $data['theme-editor'] = $fileService->readfile($this->themePath);

        // $data['tw-config-editor'] = $fileService->readfile($this->twConfigPath);

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Site')
                    ->label(fn () => __('page.general_settings.sections.site'))
                    ->description(fn () => __('Manage basic settings'))
                    ->icon('heroicon-o-code-bracket')
                    ->schema([
                        Forms\Components\Grid::make()->schema([
                            Forms\Components\TextInput::make('brand_name')
                                ->label(fn () => __('Brand Name'))
                                ->required(),     
                                Forms\Components\TextInput::make('brand_logoHeight')
                                    ->label(fn () => __('Brand Logo Height'))
                                    ->required()                       
                        ]),
                        Forms\Components\Grid::make()->schema([                                                                                                
                            Forms\Components\FileUpload::make('brand_logo')
                                    ->label(fn () => __('Brand Logo'))
                                    ->image()
                                    ->directory('sites')
                                    ->visibility('public')
                                    ->moveFiles()
                                    ->required(),                                                               
                            Forms\Components\FileUpload::make('site_favicon')
                                ->label(fn () => __('Site Icon'))
                                ->image()
                                ->directory('sites')
                                ->visibility('public')
                                ->moveFiles()
                                ->acceptedFileTypes(['image/x-icon', 'image/vnd.microsoft.icon'])
                                ->required(),
                        ]),
                    ]),
                Forms\Components\Tabs::make('Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Color Palette')
                            ->schema([
                                Forms\Components\ColorPicker::make('site_theme.primary')
                                    ->label(fn () => __('primary'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.secondary')
                                    ->label(fn () => __('secondary'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.gray')
                                    ->label(fn () => __('gray'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.success')
                                    ->label(fn () => __('success'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.danger')
                                    ->label(fn () => __('danger'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.info')
                                    ->label(fn () => __('info'))->rgb(),
                                Forms\Components\ColorPicker::make('site_theme.warning')
                                    ->label(fn () => __('warning'))->rgb(),
                            ])
                            ->columns(3),                        
                    ])
                    ->persistTabInQueryString()
                    ->columnSpanFull(),
            ])
            ->columns(3)
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->mutateFormDataBeforeSave($this->form->getState());

            $settings = app(static::getSettings());

            $settings->fill($data);
            $settings->save();

            // $fileService = new FileService;
            // $fileService->writeFile($this->themePath, $data['theme-editor']);
            // $fileService->writeFile($this->twConfigPath, $data['tw-config-editor']);

            Notification::make()
                ->title('Settings updated.')
                ->success()
                ->send();

            $this->redirect(static::getUrl(), navigate: FilamentView::hasSpaMode() && is_app_url(static::getUrl()));
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Settings");
    }

    public static function getNavigationLabel(): string
    {
        return __("General");
    }

    public function getTitle(): string|Htmlable
    {
        return __("General Settings");
    }

    public function getHeading(): string|Htmlable
    {
        return __("General Settings");
    }

    public function getSubheading(): string|Htmlable|null
    {
        return __("Manage general site settings here.");
    }
}
