/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: "class",
  content: [
    './**/*.html',
    './**/*.php',
    './**/*.js',
  ],
  theme: {
    extend: {
      keyframes: {
        wiggle: {
          '0%, 100%': {
            transform: 'rotate(-0.5deg)',
            opacity: 0.8
          },
          '50%': {
            transform: 'rotate(0.5deg)',
            opacity: 1
          },
        }
      },
      animation: {
        wiggle: 'wiggle 0.5s ease-in-out infinite',
      }
    },
  },
  plugins: [],
}

