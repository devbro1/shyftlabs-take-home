const colors = require('tailwindcss/colors');

module.exports = {
    content: ['./src/**/*.{js,jsx,ts,tsx}', './public/index.html'],
    darkMode: 'class', //false or 'media' or 'class',
    theme: {
        extend: {
            width: {
                '1/7': '14.2857143%',
                '2/7': '28.5714286%',
                '3/7': '42.8571429%',
                '4/7': '57.1428571%',
                '5/7': '71.4285714%',
                '6/7': '85.7142857%',
                '1/9': '11.1111111%',
                '2/9': '22.2222222%',
                '3/9': '33.3333333%',
                '4/9': '44.4444444%',
                '5/9': '55.5555555%',
                '6/9': '66.6666666%',
                '7/9': '77.7777777%',
                '8/9': '88.8888888%',
            },
            keyframes: {
                fadeOut: {
                    '0%': {
                        opacity: '1',
                    },
                    '100%': {
                        opacity: '0',
                    },
                },
            },
            animation: {
                fadeOut: 'fadeOut 0.3s ease-in',
            },
            backgroundImage: {
                're-icon-light': "url('/re_icon_light.png')",
                're-icon-dark': "url('/re_icon_dark.png')",
            },
            colors: {
                'deep-blue': '#043750',
                'ocean-blue': '#38869B',
                sand: '#ECEAE1',
                'aqua-green': '#63C3A5',
                'salmon-pink': 'F26F6F',
                'dark-grey': '#292A2D',
            },
        },
    },
    plugins: [],
};
