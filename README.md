# WooCommerce Order Source Attribution

MVP/Prototype of order attribution/source functionality in WordPress.

# What is WooCommerce Order Source Attribution?
WooCommerce Order Source Attribution help merchants understand which marketing activities, channels or campaigns are leading to orders on their stores. 


### Requirements

WooCommerce Order Source Attribution requires recent versions of PHP (7.4 or newer), and WordPress and WooCommerce (we recommend the latest, and support the last two versions, a.k.a. L-2).


## Development

Install dependencies:

-   `npm install` to install JavaScript dependencies.
-   `composer install` to gather PHP dependencies.

Now you can build the plugin using one of these commands:

-   `npm build`: Build a production version and package as a zip file.

### Branches

-   `main` branch is the most up to date code.

### Development tools

There are a number of development tools available as npm scripts. Check the [`package.json`](https://github.com/woocommerce/woocommerce-order-source-attribution/blob/main/package.json) file for more.

-   `npm run lint:php`: Run [`phpcs`](https://github.com/squizlabs/PHP_CodeSniffer) to validate PHP code style.

Please use these tools to ensure your code changes are consistent with the rest of the code base. This code follows WooCommerce and WordPress standards.



## PHPUnit

### Prerequisites

Install [`composer`](https://getcomposer.org/), `git`, `svn`, and either `wget` or `curl`.

Change to the plugin root directory and type:

```bash
$ composer install
```


### Install Test Dependencies

To run the unit tests you need WordPress, [WooCommerce](https://github.com/woocommerce/woocommerce), and the WordPress Unit Test lib (included in the [core development repository](https://make.wordpress.org/core/handbook/testing/automated-testing/phpunit/)).

Install them using the `install-wp-tests.sh` script:

```bash
$ ./bin/install-wp-tests.sh <db-name> <db-user> <db-pass> <db-host>
```

Example:

```bash
$ ./bin/install-wp-tests.sh wordpress_tests root root localhost
```

This script installs the test dependencies into your system's temporary directory and also creates a test database.

You can also specify the path to their directories by setting the following environment variables:

-   `WP_TESTS_DIR`: WordPress Unit Test lib directory
-   `WP_CORE_DIR`: WordPress core directory
-   `WC_DIR`: WooCommerce directory

### Running Tests

Change to the plugin root directory and type:

```bash
$ vendor/bin/phpunit
```

The tests will execute and you'll be presented with a summary.


