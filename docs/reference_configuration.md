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
            use_cases:
                upload:
                    enabled: false
                    api_enabled: true
                get_files:
                    enabled: false
                    api_enabled: true
            routes:
                upload:
                    name: bengor_file_file_upload
                    path: /files/upload
                    api_name: bengor_file_file_api_upload
                    api_path: /api/files/upload
                get_files:
                    name: bengor_file_file_get_files
                    path: /files
                    api_name: bengor_file_file_api_get_files
                    api_path: /api/files
```
