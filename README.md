# DFE

## Getting started

### Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.8/installation#installation)

Clone the repository:

    git clone https://github.com/a21ns1g4ts/dfe

Switch to the repo folder:

    cd dfe

Install all php dependencies using composer:

    composer install

Copy the example env file and config the database credentials.

    cp .env.example .env

> See all env vars available in the .env.example file.

Config in the .env the database vars

Generate a new application key:

    php artisan key:generate

Flush de application cache:

    php artisan optimize

Run the database migrations.

    php artisan migrate --seed

PS: Make sure you set the correct database connection information before running the install command.

Start the local development server:

    php artisan serve

Install all node dependencies using npm:

    npm install

Compile the css and javascript assets:

    npm run dev

You can now access the server at <http://127.0.0.1:8000>

### Finish 

Go to <http://localhost/sysadmin/login> and login with the following credentials:

#### Super Admin
- **Email:** `user@dfe.com`
- **Senha:** `password`

#### DFE Sync Command
    sail php artisan dfe:sync

### Using Laravel Sail to develop

make .env config:

    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=laravel
    DB_USERNAME=root
    DB_PASSWORD=

And run:

    vendor/bin/sail build

    vendor/bin/sail up -d

    vendor/bin/sail sail artisan optimize

    vendor/bin/sail artisan filament-saas:install

    npm run install 

    npm run dev

You can now access the server at <http://localhost>

**For more information: <https://laravel.com/docs/sail>**

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email atila.danvi@outlook.com instead of using the issue tracker.

## Credits

-   [Atila Silva](https://github.com/a21ns1g4ts)
-   [All Contributors](../../contributors)

## License

The MIT License. Please see [license file](LICENSE.md) for more information.
