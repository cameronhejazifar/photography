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
            fontFamily: {
                'lato': ['Lato', 'sans-serif'],
                'signerica': ['Signerica', 'sans-serif'],
            },
            fontSize: {
                logo: '8rem',
            },
            letterSpacing: {
                logo: '1rem',
            },
            zIndex: {
                behind: '-1',
            },
            opacity: {
                '10': '0.1',
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
