#Multiple users

The [Getting started](getting_started.md) chapter only indicates how it registers your awesome file class. But this
bundle is more powerful that it would seem and it allows use more than one file class in your application. All this
bundle [services](service_reference.md) are register via PHP inside CompilerPass so, the Symfony dependency injection
container loads on the fly depending how many files are registered under `ben_gor_file` configuration section in
`app/config/config.yml`.

If your bundle configuration `file_class` looks like the following code snippet, the services are generated taking
only this file in mind. 
```yml
ben_gor_file:
    file_class:
        file:
            filesystem:
                gaufrette: already_configured_gaufrette_filesystem
```
And for example if you execute the `bin/console debug:container | grep bengor_file.upload_image`
you'll see the following:
```bash
bengor_file.upload_image              Ddd\Application\Service\TransactionalApplicationService
```
Otherwise, if your `file_class` contains multiple choices for example something like this
```yml
ben_gor_file:
    file_class:
        pdf:
            filesystem:
                gaufrette: already_configured_gaufrette_filesystem
        image:
            filesystem:
                gaufrette: already_configured_gaufrette_filesystem
```
the above command will print the following:
```bash
bengor_file.upload_pdf                Ddd\Application\Service\TransactionalApplicationService
bengor_file.upload_image              Ddd\Application\Service\TransactionalApplicationService
```
