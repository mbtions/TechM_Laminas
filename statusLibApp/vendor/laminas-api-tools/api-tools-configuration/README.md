Laminas Configuration
================

[![Build Status](https://travis-ci.org/laminas-api-tools/api-tools-configuration.png)](https://travis-ci.org/laminas-api-tools/api-tools-configuration)

Introduction
------------

`api-tools-configuration` is a module that provides configuration services that provide for the
runtime management and modification of Laminas application based configuration files.

Requirements
------------
  
Please see the [composer.json](composer.json) file.

Installation
------------

Run the following `composer` command:

```console
$ composer require "laminas-api-tools/api-tools-configuration:~1.0-dev"
```

Alternately, manually add the following to your `composer.json`, in the `require` section:

```javascript
"require": {
    "laminas-api-tools/api-tools-configuration": "~1.0-dev"
}
```

And then run `composer update` to ensure the module is installed.

Finally, add the module name to your project's `config/application.config.php` under the `modules`
key:

```php
return array(
    /* ... */
    'modules' => array(
        /* ... */
        'Laminas\ApiTools\Configuration',
    ),
    /* ... */
);
```

Configuration
-------------

### User Configuration

The top-level configuration key for user configuration of this module is `api-tools-configuration`.

```php
'api-tools-configuration' => array(
    'config_file' => 'config/autoload/development.php',
    'enable_short_array' => false,
),
```

#### Key: `enable_short_array`

Set this value to a boolean `true` if you want to use PHP 5.4's square bracket (aka "short") array
syntax.

Laminas Events
----------

There are no events or listeners.

Laminas Services
------------

#### Laminas\ApiTools\Configuration\ConfigWriter

`Laminas\ApiTools\Configuration\ConfigWriter` is by default an instance of `Laminas\Config\Writer\PhpArray`.  This
service serves the purpose of providing the necessary dependencies for `ConfigResource` and
`ConfigResourceFactory`.

#### Laminas\ApiTools\Configuration\ConfigResource

`Laminas\ApiTools\Configuration\ConfigResource` service is used for modifying an existing configuration files with
methods such as `patch()` and `replace()`.  The service returned by the service manager is bound to
the file specified in the `config_file` key.

#### Laminas\ApiTools\Configuration\ConfigResourceFactory

`Laminas\ApiTools\Configuration\ConfigResourceFactory` is a factory service that provides consumers with the
ability to create `Laminas\ApiTools\Configuration\ConfigResource` objects, with dependencies injected for specific
config files (not the one listed in the `module.config.php`.

#### Laminas\ApiTools\Configuration\ModuleUtils

`Laminas\ApiTools\Configuration\ModuleUtils` is a service that consumes the `ModuleManager` and provides the
ability to traverse modules to find their path on disk as well as the path to their configuration
files.
