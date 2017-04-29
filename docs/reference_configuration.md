# Configuration Reference

All available configuration options are listed below with their default values.
```yml
ben_gor_file:
    file_class:
        file:
            class: ~                                        # Required
            persistence: doctrine_orm                       # Also, it can be "doctrine_odm_mongodb"
            storage: symfony                                # Also, it can be "gaufrette"
            upload_strategy: default                        # Also, it can be "by_hash" or "suffix_number"
            upload_destination: '%kernel.root_dir%/../web'  # In Gaufrette storage is a configured Gaufrette filesystem
            download_base_url: /files
            data_transformer: BenGorFile\File\Application\DataTransformer\FileDTODataTransformer
```
