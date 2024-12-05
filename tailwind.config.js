/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      screens: {
        "custom-2xl" : "1292px"
      }
    },
  },
  plugins: [],
}
