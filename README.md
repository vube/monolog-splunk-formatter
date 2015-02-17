# Splunk Formatter

[![Build Status](https://travis-ci.org/vube/monolog-splunk-formatter.svg)](https://travis-ci.org/vube/monolog-splunk-formatter)
[![Coverage Status](https://coveralls.io/repos/vube/monolog-splunk-formatter/badge.svg)](https://coveralls.io/r/vube/monolog-splunk-formatter)
[![Latest Stable Version](https://poser.pugx.org/vube/monolog-splunk-formatter/v/stable.png)](https://packagist.org/packages/vube/monolog-splunk-formatter)
[![Dependency Status](https://www.versioneye.com/user/projects/54e2df278bd69f90bd00007a/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54e2df278bd69f90bd00007a)

A Splunk Formatter for Monolog

## About

`vube/monolog-splunk-formatter` is a formatter for use with [Monolog](https://github.com/Seldaek/monolog).
 It augments the [Monolog LineFormatter](https://github.com/Seldaek/monolog/blob/master/src/Monolog/Formatter/LineFormatter.php)
 by adding Splunk-optimized handling of associative array contexts.

## Prerequisites/Requirements

- PHP 5.3.0 or greater
- Composer

## Installation

Installation is possible using Composer

```
composer require vube/monolog-splunk-formatter ~1.0
```

## Usage

Create an instance of `\Vube\Monolog\Formatter\SplunkLineFormatter`
 and set it as the formatter for the `\Monolog\Handler\StreamHandler`
 that you use with your `\Monolog\Logger` instance.

```
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Vube\Monolog\Formatter\SplunkLineFormatter;

$log = new Logger('DEMO');
$handler = new StreamHandler('php://stdout', Logger::WARNING);
$handler->setFormatter(new SplunkLineFormatter());
$log->pushHandler($handler);

$log->addError('Bad stuff happened', array('detail1' => 'something', 'detail2' => 'otherthing'));
```

## Unit Testing

`vube/monolog-splunk-formatter` ships with unit tests using [PHPUnit](https://github.com/sebastianbergmann/phpunit/).

- If PHPUnit is installed globally run `phpunit` to run the tests.

- If PHPUnit is not installed globally, install it locally through composer by running `composer install --dev`. Run the tests themselves by calling `vendor/bin/phpunit`.

Unit tests are also automatically run [on Travis CI](http://travis-ci.org/vube/monolog-splunk-formatter)

## License

`vube/monolog-splunk-formatter` is released under the MIT public license. See the enclosed `LICENSE` for details.

### Thanks

Thanks to [Bramus](https://github.com/bramus) for contributing a Monolog formatter, I was inspired by and reused some of his work.
