# PHP 8.1 Demo Code

This code demonstrates various PHP 8.0 and 8.1 features in a realistic, functional (but incomplete) codebase. The code is part of some DNS validation logic.

This code came from a code along session in the October 2021 [Arizona PHP User Group](https://www.meetup.com/azPHPUG) meetup, and was inspired by some refactoring work I was doing at my day job.

## Feature Sightings

- Enums
- Throw exceptions as an expression
- Constructor property promotion
- Readonly properties
- Union types
- `static` return type
- First class callables
- `new` in initializers
- `match` expressions
- Trailing commas in parameter lists
- `catch` exceptions without a variable

## Running the Code

### Requirements

- Docker
- PHP
- Composer

### Instructions

1. Uncomment lines 10 and 11 in `index.php`.
    - This is because you are running Composer locally, and not in the container, so the current version of Composer you have will not include the enums in with the autoloader. 
2. Run `composer run shell` to download the php 8.1 image and boot up a docker container for it with a shell.
3. Run `php index.php` within the container's shell to execute this file.
4. Run `exit` to exit and shutdown the container.

## Resources

- https://stitcher.io/blog/new-in-php-81
- https://www.php.net/manual/en/migration80.php
- https://hub.docker.com/r/phpdaily/php
