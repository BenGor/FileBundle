#Multiple files

In case you need more than one file type you need to go across the following steps that are very similar to those you
followed in [basic configuration](basic_configuration.md) chapter.

First of all you need to define a new file model.

```php
// src/AppBundle/Entity/Image.php

namespace AppBundle\Entity;

use BenGorFile\File\Domain\Model\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 */
class Image extends BaseFile
{
}
```

Next, you have to append the newly created class' definition to the one you created in the 
[basic configuration](basic_configuration.md) chapter:
```yml
# app/config/config.yml

ben_gor_file:
    file_class:
        file:
            class: AppBundle\Entity\File
            filesystem:
                gaufrette: already_configured_gaufrette_filesystem
        image:
            class: AppBundle\Entity\Image
            filesystem:
                gaufrette: already_configured_gaufrette_filesystem
```

With only one file type declared in `config.yml` if you execute the
```bash
$ bin/console debug:container | grep .command_bus`
```
you'll see the following:
```bash
bengor_file.file.command_bus      BenGorFile\SimpleBusBridge\CommandBus\SimpleBusFileCommandBus  
```
Otherwise, if you execute the above command after add he second file type you'll see the following:
```bash
bengor_file.file.command_bus      BenGorFile\SimpleBusBridge\CommandBus\SimpleBusFileCommandBus  
bengor_file.image.command_bus     BenGorFile\SimpleBusBridge\CommandBus\SimpleBusFileCommandBus  
```

> **IMPORTANT!** `your_file_type` mentioned across all the docs refers to the name you assign to each file model. The 
array key bellow file_class will be used for that. In this case `file` and `image` strings will be used to replace 
`your_file_type` in the examples you will find.
 

- Back to the [index](index.md).
