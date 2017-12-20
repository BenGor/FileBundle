# Service Reference

All available services are listed below with their associated class.
```bash
bengor_file.file.dto_data_transformer    BenGorFile\File\Application\DataTransformer\FileDTODataTransformer

bengor_file.image.overwrite              alias for "bengor.file.application.command.overwrite_image"
bengor_file.image.remove                 alias for "bengor.file.application.command.remove_image"
bengor_file.image.rename                 alias for "bengor.file.application.command.rename_image"
bengor_file.file.upload                  alias for "bengor.file.application.command.upload_file"

bengor_file.file.command_bus             BenGorFile\SimpleBusBridge\CommandBus\SimpleBusFileCommandBus
bengor_file.file.event_bus               BenGorFile\SimpleBusBridge\EventBus\SimpleBusFileEventBus

bengor_file.file.factory                 BenGorFile\File\Infrastructure\Domain\Model\FileFactory

bengor_file.file.by_id_query             BenGorFile\File\Application\Query\FileOfIdHandler
bengor_file.file.by_name_query           BenGorFile\File\Application\Query\FileOfNameHandler
bengor_file.count_files_query            BenGorFile\File\Application\Query\CountFilesHandler
bengor_file.filter_files_query           BenGorFile\File\Application\Query\FilterFilesHandler

bengor_file.file.filesystem              BenGorFile\GaufretteFilesystemBridge\Infrastructure\Domain\Model\GaufretteFilesystem
                                         BenGorFile\SymfonyFilesystemBridge\Infrastructure\Domain\Model\SymfonyFilesystem

bengor_file.file.repository              BenGorFile\DoctrineORMBridge\Infrastructure\Persistence\DoctrineORMFileRepository
                                         BenGorFile\File\Infrastructure\Persistence\Sql\SqlFileRepository
                                         BenGorFile\DoctrineODMMongoDBBridge\Infrastructure\Persistence\DoctrineODMMongoDBFileRepository
                                         
bengor_file.file.specification_factory   BenGorFile\DoctrineORMBridge\Infrastructure\Persistence\DoctrineORMFileSpecificationFactory
                                         BenGorFile\File\Infrastructure\Persistence\Sql\SqlFileSpecificationFactory
                                          

// Creates by Symfony DIC to the correct use of the bundle. Please, don't use them.

bengor.file.application.command.overwrite_file     BenGorFile\File\Application\Command\Upload\OverwriteFileHandler
bengor.file.application.command.remove_file        BenGorFile\File\Application\Command\Upload\RemoveFileHandler
bengor.file.application.command.rename_file        BenGorFile\File\Application\Command\Upload\RenameFileHandler
bengor.file.application.command.upload_file        BenGorFile\File\Application\Command\Upload\UploadFileHandler

bengor.file.application.data_transformer.file_dto  BenGorFile\File\Application\DataTransformer\FileDTODataTransformer

bengor.file.application.query.file_of_id           BenGorFile\File\Application\Query\FileOfIdHandler
bengor.file.application.query.file_of_name         BenGorFile\File\Application\Query\FileOfNameHandler
bengor.file.application.query.count_files          BenGorFile\File\Application\Query\CountFilesHandler
bengor.file.application.query.filter_files         BenGorFile\File\Application\Query\FilterFilesHandler
```
- Back to the [index](index.md).
