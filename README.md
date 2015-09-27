# ZF2 Restful API Example

This is an example application showing how to create a RESTful JSON API using PHP and [Zend Framework 2](http://framework.zend.com/).
It is inspired from [zf2-restful-api] (https://github.com/stevenalexander/zf2-restful-api) of [stevenalexander] (https://github.com/stevenalexander)
Doctrine & PHPUnit is also enabled

## Requirements

* PHP 5.3+
* Web server [setup with virtual host to serve project folder](http://framework.zend.com/manual/2.2/en/user-guide/skeleton-application.html#virtual-host)
* [Composer](http://getcomposer.org/) (manage dependencies)

## Creating the API

1. Get composer:

    ```
    curl -sS https://getcomposer.org/installer | php
    ```

2. Create the composer.json file to get ZF2:

    ```
    {
        "require": {
            "php": ">=5.3.3",
            "zendframework/zendframework": ">=2.2.4"
        }
    }
    ```

3. Install the dependencies:

    ```
    php composer.phar install
    ```

4. public/index.php (for directing calls to Zend and static)

    ```
    <?php

    chdir(dirname(__DIR__));

    if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
        return false;
    }

    require 'init_autoloader.php';
    Zend\Mvc\Application::init(require 'config/application.config.php')->run();
    ```
5. public/.htaccess (for redirecting non-asset requests to index.php)

    ```
    RewriteEngine On
    # The following rule tells Apache that if the requested filename
    # exists, simply serve it.
    RewriteCond %{REQUEST_FILENAME} -s [OR]
    RewriteCond %{REQUEST_FILENAME} -l [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule ^.*$ - [NC,L]
    # The following rewrites all other queries to index.php. The
    # condition ensures that if you are using Apache aliases to do
    # mass virtual hosting, the base path will be prepended to
    # allow proper resolution of the index.php file; it will work
    # in non-aliased environments as well, providing a safe, one-size
    # fits all solution.
    RewriteCond %{REQUEST_URI}::$1 ^(/.+)(.+)::\2$
    RewriteRule ^(.*) - [E=BASE:%1]
    RewriteRule ^(.*)$ %{ENV:BASE}index.php [NC,L]
    ```

## more detail comming soon