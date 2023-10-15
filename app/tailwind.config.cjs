
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./index.html",
        "./src/**/*.{svelte,js,ts,jsx,tsx}",

    ],
    theme: {
        extend: {},
    },
    daisyui: {
        themes: ["lofi","pastel"],
    },
    plugins: [require("daisyui"), require('@tailwindcss/typography')],
}
