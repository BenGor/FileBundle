# Getting started

By default we recommend the following installation that will add the following adapters to the file bundle.

* Persistence: [DoctrineORM](https://github.com/BenGorFile/DoctrineORMBridgeBundle)
* Storage: [SymfonyFilesystem](https://github.com/BenGorFile/SymfonyFilesystemBridgeBundle)
* Bus: [SimpleBus](https://github.com/BenGorFile/SimpleBusBridgeBundle)

```
{
    "require": {
        "bengor-file/file-bundle": "^0.2",

        "bengor-file/doctrine-orm-bridge-bundle": "^1.0",
        "bengor-file/symfony-filesystem-bridge-bundle": "^1.0",
        "bengor-file/simple-bus-bridge-bundle": "^1.0"
    }
} 
```

> Some other adapters for [persistence](adapters_persistence.md), [storages](adapters_storage.md) and 
[buses](adapters_buses.md) are available.

To install the desired adapters and the bundle itself run the following in the project root:

```bash
$ composer update
```

> Make sure you have [composer](http://getcomposer.org) globally installed 

Once the bundle has been installed enable it in the AppKernel:
```php
// app/config/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        
        // Dependencies required by the bundle, keep the order.
        // First bridges and then the FileBundle
                
        // Bridges
        new BenGorFile\SymfonyFilesystemBridgeBundle\BenGorFileSymfonyFilesystemBridgeBundle(),
        new BenGorFile\DoctrineORMBridgeBundle\BenGorFileDoctrineORMBridgeBundle(),
        new BenGorFile\SimpleBusBridgeBundle\BenGorFileSimpleBusBridgeBundle(),
        new BenGorFile\SimpleBusBridgeBundle\BenGorFileSimpleBusDoctrineORMBridgeBundle(),
      
        // File bundle
        new BenGorFile\FileBundle\BenGorFileBundle(),
        // ...
    ];
}
```

After that, you need to extend our `BenGorFile\File\Domain\Model\File` class in order to build the Doctrine mapping properly.
The following snippet is the minimum code that bundle needs to work.
```php
// src/AppBundle/Entity/File.php

namespace AppBundle\Entity;

use BenGorFile\File\Domain\Model\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bengor_file")
 */
class File extends BaseFile
{
}
```

Next, you have to configure the bundle to work with the specific needs of your application in the `config.yml`:
```yml
ben_gor_file:
    file_class:
        file:
            class: AppBundle\Entity\File
```

That's all! Now that the bundle is configured, the last thing you need to do is update your database:
```bash
$ bin/console doctrine:schema:update --force
```

- For **multiple files** check [this guide](usage_multiple_files.md).
- In order to use **MongoDB's Doctrine ODM** as persistence layer follow [this chapter](doctrine_odm_mongodb.md).
- Back to the [index](index.md).

[1]: https://github.com/KnpLabs/KnpGaufretteBundle#installation
