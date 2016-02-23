#Configuration Reference

All available configuration options are listed below with their default values.
```yml
ben_gor_file:
    file_class:
        file:
            class: ~                                        # Required
            persistence: doctrine                           # Also, it can be "sql"
            filesystem:
                gaufrette: ~                                # Already configured Gaufrette filesystem
                symfony: ~                                  # Symfony filesystem path, e.g: %kernel.root_dir%/../web/file
            routes:
                upload:
                    enable: true
                    name: bengor_file_file_upload
                    path: /file/upload
                overwrite:
                    enable: true
                    name: bengor_file_file_overwrite
                    path: /file/overwrite
                remove:
                    enable: true
                    name: bengor_file_file_remove
                    path: /file/remove
```

> **REMEMBER:** *Gaufrette* and *Symfony* filesystems are null by default, but one of these filesystems must
> be configured. Keep in mind that the `gaufrette` option requires `knplabs/knp-gaufrette-bundle`
> If you configure all filesystems, DIC will use the Gaufrette to generate the services on the fly.
