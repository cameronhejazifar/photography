# Photography

## Installation

#### PHP Configuration

Make sure the following configurations are set:

`php.ini` (On Mac - /usr/local/etc/php/7.3/php.ini)
```ini
upload_max_filesize = 256M
memory_limit = 512M
post_max_size = 256M
```

`php-memory-limits.ini` (On Mac - /usr/local/etc/php/7.3/conf.d/php-memory-limits.ini)
```ini
upload_max_filesize = 256M
memory_limit = 512M
post_max_size = 256M
```

`nginx.conf` (On Mac - /usr/local/etc/nginx/nginx.conf)
```apacheconfig
client_max_body_size 256M;
```

`valet.conf` (On Mac - /usr/local/etc/nginx/valet/valet.conf)
```apacheconfig
client_max_body_size 256M;
```

#### Google Drive API Key

In the [Google API Console](https://console.developers.google.com/apis/dashboard), you'll need to setup an OAuth consent screen and a Credential.

The Client ID Credential that you setup needs to be scoped for `Google_Service_Drive::DRIVE` (`https://www.googleapis.com/auth/drive`).

Once you've created the credential / access token, save them and add them to the .env:
```ini
GOOGLEDRIVE_CLIENT_ID=<your-client-id>
GOOGLEDRIVE_CLIENT_SECRET=<your-client-secret>
```

#### Flickr API Key

You'll also need to create [Flickr API Keys](https://www.flickr.com/services/api/keys/). Once you've created your keys, set them in the .env:
```ini
FLICKR_CONSUMER_KEY=<your-api-key>
FLICKR_CONSUMER_SECRET_KEY=<your-api-secret>
```

---


## Commands

#### Create User
`php artisan create-user`
This command allows you to manually create a user in the database. You will be prompted for the email, password, and name of the user to be created. Email addresses must be unique among users.


---


<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
