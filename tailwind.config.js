/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/views/landing/**/*.blade.php",
        "./resources/js/landing/**/*.js",
    ],

    darkMode: "class",

    theme: {
        extend: {
            colors: {
                primary: "rgb(var(--color-primary) / <alpha-value>)",
                "primary-dark":
                    "rgb(var(--color-primary-dark) / <alpha-value>)",
            },

            fontFamily: {
                sans: [
                    "Outfit",
                    "Instrument Sans",
                    "ui-sans-serif",
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFont",
                    "Segoe UI",
                    "sans-serif",
                ],
                mono: [
                    "Space Mono",
                    "ui-monospace",
                    "SFMono-Regular",
                    "monospace",
                ],
            },

            container: {
                center: true,
                padding: {
                    DEFAULT: "1rem",
                    sm: "2rem",
                    lg: "4rem",
                    xl: "5rem",
                },
            },
        },
    },

    plugins: [],
};
