import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/D:\PIECE 4\PROJECT\PHP\HERD\mecsapps\app\Filament\Clusters\Compensation\**/*.php',
        './resources/views/filament/d:\-p-i-e-c-e4\-p-r-o-j-e-c-t\-p-h-p\-h-e-r-d\mecsapps\app\-filament\-clusters\-compensation\**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                secondary: {
                    50: "rgba(var(--secondary-50), <alpha-value>)",
                    100: "rgba(var(--secondary-100), <alpha-value>)",
                    200: "rgba(var(--secondary-200), <alpha-value>)",
                    300: "rgba(var(--secondary-300), <alpha-value>)",
                    400: "rgba(var(--secondary-400), <alpha-value>)",
                    500: "rgba(var(--secondary-500), <alpha-value>)",
                    600: "rgba(var(--secondary-600), <alpha-value>)",
                    700: "rgba(var(--secondary-700), <alpha-value>)",
                    800: "rgba(var(--secondary-800), <alpha-value>)",
                    900: "rgba(var(--secondary-900), <alpha-value>)",
                },
            },
        },
    },
}
