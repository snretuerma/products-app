<p align="center">
<h1>Product Manager</h1>
</p>

## Getting Started

1. Clone the repository by running <br>
   `  git clone`

> **Note** : <br>
>
> ### Laravel Sail <br>
>
> If you are planning to use **Laravel Sail**, please refer to the installation and setup documentation [here](https://laravel.com/docs/9.x/sail).

2. Install [Composer](https://getcomposer.org/) and get inside the application directory.

```
    cd <app_name>
```

3. Install the php dependencies by running

```
    composer install
    # if you are running in laravel sail
    sail composer install
```

4. Add the application `.env` and generatate the app key.

```
    cp .env.example .env

    php artisan key:generate

    # if you are running in laravel sail
    sail artisan key:generate
```

5. Configure the application's `.env`. Don't forget to configure the application database.

6. Create the database tables.

```
    php artisan migrate

    # if you want to run the database seeder, run
    php artisan db:seed

    # if you are running in laravel sail
    sail artisan migrate

    sail artisan db:seed


    # or if you want to migrate and seed
    sail artisan artisan migrate --seed
```

5. Install the [npm](https://nodejs.org/en/) and build the npm packages

```
    npm install
    npm run dev
```

6. Run the Laravel application

```
    php artisan serve
```

## Technologies Used

-   [PHP](https://www.php.net/) : `v.8.0.2`
-   [Laravel](https://github.com/laravel/framework) : `v.9.19`
-   [Vue](https://github.com/vuejs/) : `v.3.2.41`
-   [Vite](https://laravel.com/docs/9.x/vite) : `v.4.0.0`
<!-- -   [TailwindCSS](https://github.com/vuejs/) : `v.3.2.1`
-   [Flowbite](1.6.0) : `v.3.2.1` -->
