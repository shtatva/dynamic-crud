# Dynamic CRUD

This is a PHP library for dynamic CRUD operations.

## Installation

To install this library, you can use [Composer](https://getcomposer.org/). Add the following lines to your `composer.json` file:

```json
{
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/shtatva/dynamic-crud.git"
        }
    ]
}
```

Now run the following command to fetch and update the required dependencies:

```
composer require shtatva/dynamic-crud
```

We need to generate a middleware for Inertia by using the next command:

```
php artisan inertia:middleware
```

And to use the middleware that we just generated, open the app/Http/Kernel.php file and go to the web middleware group and add generated middleware to the web group:

```
'web' => [
    // ...
    \App\Http\Middleware\HandleInertiaRequests::class,
],
```

Run these command to publish all files

```
php artisan app:publish-all-files
```

Add the follwing lines to the web.php routes file
```
Route::namespace('App\Http\Controllers')->group(function () {
    // Add more routes within the same namespace here
				
});
```

Add these lines in the package.json file

```json
{
    "dependencies": {
        "dynamic-crud-react": "github:shtatva/dynamic-crud-react#main"
    }    
}
```

Now run the following command to fetch and update the required dependencies:

```
npm install
```

After installation, we will need to configure Vite in our application so let’s do this in vite.config.js:

```
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        react(), // React plugin that we installed for vite.js
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

Lastly we have to run the migration for importing table to the database.

```
php artisan migrate
```

Okay, and the final step — run the local server and run vite in the terminal:

```
php artisan serve
npm run dev
```


