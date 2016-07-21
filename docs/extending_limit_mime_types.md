#Limit mime types

The domain has a value object named FileMimeType. This VO contains a list of all the possible mime types accepted by
Apache. However, sometimes you want to become more restrict allowed mime types of your *File* so, keeping this case in
mind, base file domain object provides a `availableMimeTypes` method that limits the VO mime types.
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
    protected function availableMimeTypes()
    {
        return ['image/jpeg', 'image/jpg', 'image/png']; 
    }
}
```

The following code with basic configuration chapters entity everything works well, "$file" is an instance of File,
otherwise, with above File entity the following code throws an exception because the "application/pdf" mime type given
not found inside available mime types.
```php
<?php

use AppBundle\Entity;

$file = new File(
    new FileId('dummy-id'),
    new FileName('dummy-file-name.pdf'),
    new FileMimeType('application/pdf')
);
```

- Back to the [index](index.md).
