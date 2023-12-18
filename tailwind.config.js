/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: "class",
    content: [
        './**/*.html',
        './**/*.php',
        './**/*.js',
    ],
    theme: {
        fontFamily: {
            "sans": ['Gabarito', 'sans-serif']
        },
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
                },
                ping: {
                    '0%': {
                        transform: 'scale(1)',
                        opacity: 1
                    },
                    '75%, 100%': {
                        transform: 'scale(2)',
                        opacity: 0
                    },
                },
                bounce: {
                    '0%, 100%': {
                        transform: 'translateY(-25%)',
                        animationTimingFunction: 'cubic-bezier(0.8,0,1,1)'
                    },
                    "50%": {
                        transform: 'translateY(0)',
                        animationTimingFunction: 'cubic-bezier(0,0,0.2,1)'
                    },
                }

            }
        },
        animation: {
            wiggle: 'wiggle 0.5s ease-in-out infinite',
            ping: 'ping 3s cubic-bezier(0, 0, 0.2, 1) infinite',
            bounce: 'bounce 1s infinite'
        }
    },
    plugins: [],
}

