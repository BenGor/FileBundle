#Getting started

The first step is enable the bundle in the `app/config/AppKernel.php`:
```php
<?php

public function registerBundles()
{
    $bundles = [
        // ...
        new BenGor\FileBundle\BenGorFileBundle(),
        // ...
    ];
}
```

After that, you need to extend our `BenGor\UserBundle\Model\User` class in order to build the Doctrine mapping properly.
The following snippet is the minimum code that bundle needs to work.
```php
<?php

namespace AppBundle\Entity;

use BenGor\File\Domain\Model\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bengor_file")
 */
class File extends BaseFile
{
}
```

>Keep in mind that to use **Gaufrette** filesystem (it's recommended way over Symfony filesystem), you need to install,
>enable and configure the `knplabs/knp-gaufrette-bundle` bundle so, you should follow the bundle's [documentation][1].

Next, you have to configure the bundle to work with the specific needs of your application inside
`app/config/config.yml`:
```yml
ben_gor_file:
    file_class:
        file:
            class: AppBundle\Entity\File
            filesystem:
                gaufrette: already_configured_gaufrette_filesystem
```

This bundle has some basic actions such as upload, overwrite and remove already implemented. Just add the following
to your `app/config/routing.yml`:
```yml
ben_gor_file:
    resource: '@BenGorFileBundle/Resources/config/routing/all.yml'
```

That's all! Now that the bundle is configured, the last thing you need to do is update your database:
```bash
$ bin/console doctrine:schema:update --force
```

- For multiple files check [this guide](multiple_files.md).

[1]: https://github.com/KnpLabs/KnpGaufretteBundle#installation
