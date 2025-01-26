import defaultTheme from "tailwindcss/defaultTheme";
import preset from "./vendor/filament/support/tailwind.config.preset";
import forms from "@tailwindcss/forms";
import colors from "tailwindcss/colors.js";

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./app/Filament/**/*.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: colors.sky,
            },
        },
    },

    plugins: [forms],
};
