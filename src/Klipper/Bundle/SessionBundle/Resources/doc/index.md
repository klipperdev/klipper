Getting Started
===============

The Symfony2 session can be stored in a database using PDO, 
and this bundle facilitates the configuration and initialization of the 
sessions in the main database (or another).

## Prerequisites

This version of the bundle requires Symfony 2.4+.

## Installation

Installation is a quick, 2 step process:

1. Download the bundle using composer
2. Enable the bundle
3. Configure the bundle (optional)

### Step 1: Download the bundle using composer

Tell composer to download the bundle by running the command:

```bash
$ composer require klipper/session-bundle
```

Composer will install the bundle to your project's `vendor/klipper` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Klipper\Bundle\SessionBundle\KlipperSessionBundle(),
    );
}
```

### Step 3: Configure the bundle (optional)

You can override the default configuration adding `klipper_session` tree in `app/config/config.yml`.
For see the reference of Klipper Session Configuration, execute command:

```bash
$ php app/console config:dump-reference KlipperSessionBundle
```

### Next Steps

Now that you have completed the basic installation and configuration of the
Klipper SessionBundle, you are ready to learn about usages of the bundle.

The following documents are available:

- [Command Initialization](command_initialization.md)
