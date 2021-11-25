// module.exports = {
//     plugins: {
//         autoprefixer: {}
//     }
// }
const purgecss = require('@fullhuman/postcss-purgecss')

module.exports = {
    plugins: [
        purgecss({
            content: ['./**/*.html.twig']
        })
    ]
}
