#Service Reference

All available services are listed below with their associated class.
```bash
bengor.file.application.service.overwrite_file            BenGor\File\Application\Service\OverwriteFileService
bengor.file.application.service.remove_file               BenGor\File\Application\Service\OverwriteFileService
bengor.file.application.service.upload_file               BenGor\File\Application\Service\UploadFileService


bengor.file.infrastructure.filesystem.gaufrette.file      BenGor\File\Infrastructure\Filesystem\Gaufrette\GaufretteFilesystem
bengor.file_bundle.filesystem.gaufrette.file              Gaufrette\Filesystem
            -----------------------------------------------------------------------------------------------
bengor.file.infrastructure.filesystem.symfony.file        BenGor\File\Infrastructure\Filesystem\Symfony\SymfonyFilesystem


bengor_file.doctrine_file_repository                      BenGor\File\Infrastructure\Persistence\Doctrine\DoctrineFileRepository
            -----------------------------------------------------------------------------------------------
bengor_file.sql_file_repository                           BenGor\File\Infrastructure\Persistence\Doctrine\SqlFileRepository


bengor_file.file_factory                                  BenGor\File\Infrastructure\Domain\Model\FileFactory





// Aliases of transactional services

bengor_file.overwrite_file                                Ddd\Application\Service\TransactionalApplicationService
bengor_file.remove_file                                   Ddd\Application\Service\TransactionalApplicationService
bengor_file.upload_file                                   Ddd\Application\Service\TransactionalApplicationService
```
