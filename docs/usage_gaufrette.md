# Gaufrette
Instead of use the Symfony filesystem as storage layer you can use the Gaufrette solution that is a more robust option
and provides different adapters, for example, it can store in an easy way the data in S3.

Firstly, install the bundle with Composer
```shell
$ composer require bengor-file/gaufrette-filesystem-bridge-bundle
```
Once the bundle has been installed enable it in the AppKernel:
```php
// app/config/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
        new BenGorFile\GaufretteFilesystemBridgeBundle\BenGorFileGaufretteFilesystemBridgeBundle(),  
        // ...
    ];
}
```
## Configuration
In order to use Gaufrette you have to configure it. Here is a sample configuration that stores your file in your
local filesystem, but keep in mind, you can use your preferred adapters. Check the [Gaufrette][1] documentation for
more information.
``` yml
# app/config/config.yml

knp_gaufrette:
    stream_wrapper: ~ 
    adapters:
        image_adapter:
            local:
                directory: '%kernel.root_dir%/../web/uploads/images'
                create: true
    filesystems:
        image_filesystem:
            adapter: image_adapter

ben_gor_file:
    file_class:
        image:
            class: AppBundle\Entity\Image
            storage: gaufrette
            upload_destination: image_filesystem
```

**Note:**
> Make sure that Gaufrette stream wrapper is enabled and its protocol must have the `gaufrette` name, otherwise it
does not work.

**Note:**
> In this case `upload_destination` refers to a Gaufrette filesystem.

- Back to the [index](index.md).

[1]: https://github.com/KnpLabs/KnpGaufretteBundle
