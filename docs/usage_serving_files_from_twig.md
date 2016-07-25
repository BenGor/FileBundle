#Serving files from Twig

This bundles provides a simple Twig function that simplifies the download file process. Keeping in mind that this
bundle supports the multiple file configurations (check *"[Multiple files](usage_multiple_files.md)"* chapter
for more information), the bundle itself generates on the fly the Twig functions using the file type class
as part of the function name. For example if you have to register in the `config.yml`
```yml
# app/config/config.yml

ben_gor_file:
    file_class:
        file:
            class: AppBundle\Entity\File
        image:
            class: AppBundle\Entity\Image
```
the bundle will create the `bengor_file_file_download` and `bengor_file_image_download` functions.

This method needs an only one argument that is the file name and automatically it will generate a public url that
exposes in a safe way the given file. If the file is an image you can wraps inside HTML `img` tag or an embed in case
that file is, for example, a pdf.
```twig
{# your awesome twig template #}

<embed src="{{ bengor_file_image_download('pdf-file.pdf') }}" width="500" height="1000"/>

<img src="{{ bengor_file_image_download('image.jpg') }}"/>
```

- Back to the [index](index.md).
