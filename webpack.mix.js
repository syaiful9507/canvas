const mix = require('laravel-mix')

mix
  .webpackConfig({
    plugins: []
  })
  .options({
    terser: {
      terserOptions: {
        compress: {
          drop_console: true
        }
      }
    }
  })
  .setPublicPath('public')
  .copyDirectory('resources/img', 'public/img')
  .js('resources/js/app.js', 'public/js')
  .vue({ version: 3 })
  .postCss('resources/css/app.css', 'public/css', [require('tailwindcss')])
  .alias({
    '@': 'resources/js',
  })

if (mix.inProduction()) {
  mix.version()
}
