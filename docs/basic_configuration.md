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

>In order to use **Gaufrette** filesystem (it's recommended way over Symfony filesystem), you need to install,
>enable and configure the `knplabs/knp-gaufrette-bundle` bundle so, you should follow the bundle's [documentation][1].

Next, you have to configure the bundle to work with the specific needs of your application inside
`app/config/config.yml`:
```yml
ben_gor_file:
    file_class:
        file:
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
