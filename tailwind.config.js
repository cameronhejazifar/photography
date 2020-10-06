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
        },
    },
    variants: {},
    plugins: [],
};
