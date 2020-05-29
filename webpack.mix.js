const mix = require('laravel-mix');

mix.webpackConfig({
	watchOptions: {
		ignored: /node_modules/
	},
   resolve: {
       alias: {
           "@js": path.resolve(
               __dirname,
               "resources/js/app"
           ),
           "@admin": path.resolve(
               __dirname,
               "resources/js/admin"
           )
       }
   }
});



mix.js('resources/js/app/app.js', 'public/js')
.js('resources/js/admin/admin.js', 'public/js')
.extract(['vue','vuetify'])
.version();

mix.sass('resources/sass/app.scss', 'public/css')
   .version();