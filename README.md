# Application Tracker

## Local development:
You'll need two terminal windows one to run `php artisan serve` and another to run `npm run dev` this will allow you to run both the backend and frontend with Hot Module Reloading, so any changes made to `['resources/css/**', 'resources/js/**', 'routes/**']` will reload the frontend in the browser.

## NPM Scripts
- `dev` - Run dev client/server
- `build:dev` - Build development version of client and SSR
- `build:prod` - Build production version of client and SSR
- `lint` - Run linter and prettier checks and write changes
- `prettier` - Formats Vue/JS/Json files based on `Prettier` config
- `test:lint` - Dry run of `lint` and output a list of warnings/errors
- `test:prettier` - Dry run of `prettier` and output a list of warnings/errors

## Composer Commands:
Automated Scripts
- `update` will publish third-party assets and regenerate all ide-helpers
- `install` will generate ziggy routes `resources/js/ziggy.js`
- `lint` - Format PHP based to Laravel Standards.
- `test:lint` - Dry run of `lint` output a list of warnings/errors.
- `test` - Run tests in parallel with no coverage and ensure database is recreated
- `doc:models` - [PHPDocs for models](https://github.com/barryvdh/laravel-ide-helper#automatic-PHPDocs-for-models) within `App/Models`
- `analyse` - Analayse the code base using LaraStan

## Artisan Commands:
- `ziggy:generate` - This generates a JSON file for all the route names used on the frontend, in most cases this will be executed by Vite but in a production environment it's best to execute this before building the client/server for the frontend.
- `ide-helper:generate` - [PHPDoc generation for Laravel Facades](https://github.com/barryvdh/laravel-ide-helper#automatic-phpdoc-generation-for-laravel-facades) 
- `ide-helper:meta` - [PhpStorm Meta file](https://github.com/barryvdh/laravel-ide-helper#phpstorm-meta-for-container-instances)

**Note:** `ide-helper:generate` `ide-helper:meta` and `doc:models` are always executed on `composer update`.
