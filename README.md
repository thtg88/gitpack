# GitPack

GitPack aims at making git-based deployments to AWS Lambda for Laravel applications. You get given a git remote, so you are a simple `git push` away from having your Laravel application on Lambda.

## Usage

The idea is you register to GitPack, add your app, set your AWS user key and secret, set any env variable that the app may need. Finally you get a git remote and you can git push to it and your app will be deployed.

## Technologies

The idea was that by using Spatie's [SSH](https://github.com/spatie/ssh) package, together with the Laravel pipelines architecture, a set of scripts get executed on a remote git server, before having the deployment completed.

GitPack also uses [Bref](https://bref.sh/) and [Serverless](https://www.serverless.com/) under the hood to complete the deployment.

## Development

### Requirements

- PHP 8.0: on macOS you can install it via Homebrew with `brew install php@8.0`
- Composer v2: check the Composer website for [instructions](https://getcomposer.org/download/)
- Postgres v13: on macOS you can install it via Homebrew with `brew install postgres@13`
- Node v14: you can install it using nvm via `nvm install 14.17.1`
- Yarn: you can install it via `npm i -g yarn`

### Setup

```bash
composer install
yarn
php artisan migrate --seed
```

### Server

```bash
php artisan serve
```

### CI

Some handy composer scripts are available to help with code quality:

- `composer run-script check-style`: lint PHP files using [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer), and blade files using [TLint](https://github.com/tighten/tlint)
- `composer run-script stan`: runs static analysis on PHP files using [Psalm](https://github.com/vimeo/psalm/) and its [Laravel plugin](https://github.com/psalm/psalm-plugin-laravel)
- `composer run-script test`: runs unit and feature tests using PHPUnit and Laravel parallel testing feature via Paratest
- `composer run-script fix-style`: fix PHP files style via PHP CS Fixer
- `composer run-script ci`: runs the `check-style`, `stan`, and `test` scripts sequentially

Additionally CI is run using GitHub Actions
