Getting Started
===============

## Prerequisites

This version of the bundle requires Symfony 3.

## Installation

Installation is a quick, 2 step process:

1. Download the bundle using composer
2. Enable the bundle

### Step 1: Download the bundle using composer

Tell composer to download the bundle by running the command:

``` bash
$ composer require klipper/doctrine-extensions-bundle
```

Composer will install the bundle to your project's `vendor/klipper` directory.

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Klipper\Bundle\DoctrineExtensionsBundle\KlipperDoctrineExtensionsBundle(),
    );
}
```

### Next Steps

Now that you have completed the basic installation and configuration of the
Klipper DoctrineExtensionsBundle, you are ready to learn about usages of the bundle.

The following documents are available:

- [Doctrine unique entity validation](doctrine_unique_entity.md)
- [Doctrine callback validation](doctrine_callback_validation.md)
