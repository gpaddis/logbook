[![Build Status](https://travis-ci.org/gpaddis/logbook.svg?branch=develop)](https://travis-ci.org/gpaddis/logbook)
[![StyleCI](https://styleci.io/repos/104880175/shield?branch=develop)](https://styleci.io/repos/104880175)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/gpaddis/logbook/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/gpaddis/logbook/?branch=develop)

**Logbook** is a database powered web application for the collection and analysis of library statistics.

### Testing the app
**This application is currently under active development**. To play with it locally, you will need to have at least MySQL, PHP and composer installed on your machine (check out the [requirements for laravel](https://laravel.com/docs/5.5/installation)), then do the following:
* clone this repository (branch: develop)
* create a MySQL database, give it any name
* rename the `.env.example` file to `.env` in the project's directory, edit it and specify your local configuration (check the [docs](https://laravel.com/docs/5.5/configuration))
* run `composer install`
* run `php artisan key:generate`
* run `php artisan migrate`
* run `php artisan db:seed`

To start the app and test it, run `php artisan serve` and open the link in your browser. As a first move, you will have to register an **admin account**: go to `/register-admin` and fill the form. Enjoy!
