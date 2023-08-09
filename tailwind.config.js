/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    // "./resources/**/*.vue",
  ],
  theme: {
    container: {
      center: true
    },
    extend: {
      colors: {
        'primary': '#0d9296',
        'primary-d': '#0b7f83',
        'accent': '#ffb700',
        'accent-d': '#f5af00',
        'light': '#dafafb'
      },
      fontFamily: {
        'display': ['Montserrat', 'sans-serif'],
        'body': ['Source Sans Pro', 'sans-serif'],
        'handwriting': ['Solitreo', 'sans-serif'],
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography')
  ],
}
