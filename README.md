[![Build Status](https://travis-ci.org/gpaddis/logbook.svg?branch=develop)](https://travis-ci.org/gpaddis/logbook)

**Logbook** is a database powered web application for the collection and analysis of library statistics.

### Testing the app
**This application is currently under active development**. To play with it locally, you will need to have at least PHP and composer installed on your machine (check out the [requirements for laravel](https://laravel.com/docs/5.5/installation)), then do the following:
* clone this repository (branch: develop)
* create a MySQL database, give it any name
* copy the .env.example file to an .env in the project's directory, edit it and specify your local configuration (check the [docs](https://laravel.com/docs/5.5/configuration))
* run `composer install`
* run `php artisan key:generate`
* run `php artisan migrate`

To start the app and test it, run `php artisan serve` and open the link in your browser.
