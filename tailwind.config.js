module.exports = {
    future: {
        // removeDeprecatedGapUtilities: true,
        // purgeLayersByDefault: true,
    },
    purge: [
        './resources/**/*.php',
        './resources/**/*.html',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            maxHeight: (theme, { breakpoints }) => ({
                none: 'none',
                xs: '20rem',
                sm: '24rem',
                md: '28rem',
                lg: '32rem',
                xl: '36rem',
                '2xl': '42rem',
                '3xl': '48rem',
                '4xl': '56rem',
                '5xl': '64rem',
                '6xl': '72rem',
                full: '100%',
                ...breakpoints(theme('screens')),
                ...theme('spacing'),
            }),
            fontFamily: {
                'lato': ['Lato', 'sans-serif'],
                'signerica': ['Signerica', 'sans-serif'],
            },
            fontSize: {
                logo: '8rem',
                'logo-mobile': '4rem',
                xxs: '0.4rem',
            },
            letterSpacing: {
                logo: '1rem',
                'logo-mobile': '0.6rem',
            },
            zIndex: {
                behind: '-1',
            },
            backdropFilter: {
                'none': 'none',
                'blur-1': 'blur(1px)',
                'blur-3': 'blur(3px)',
                'blur-5': 'blur(5px)',
                'blur-10': 'blur(10px)',
                'blur-15': 'blur(15px)',
                'blur-20': 'blur(20px)',
            },
        },
    },
    variants: {},
    plugins: [
        require('tailwindcss-filters'),
    ],
};
