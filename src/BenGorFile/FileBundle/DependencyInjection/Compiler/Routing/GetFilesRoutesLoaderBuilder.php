<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\FileBundle\DependencyInjection\Compiler\Routing;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GetFilesRoutesLoaderBuilder extends RoutesLoaderBuilder
{
    protected function definitionName()
    {
        return 'bengor.file_bundle.routing.get_files_routes_loader';
    }

    protected function defaultUploadDir($file)
    {
        return sprintf('/%ss', $file);
    }

    protected function defaultRouteName($file)
    {
        return sprintf('bengor_file_%s_get_files', $file);
    }

    protected function defaultRoutePath($file)
    {
        return sprintf('/%ss', $file);
    }

    protected function definitionApiName()
    {
        return 'bengor.file_bundle.routing.api_get_files_routes_loader';
    }

    protected function defaultApiRouteName($file)
    {
        return sprintf('bengor_file_%s_api_get_files', $file);
    }

    protected function defaultApiRoutePath($file)
    {
        return sprintf('/api/%ss', $file);
    }
}
