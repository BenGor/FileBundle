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
class GetFileRoutesLoaderBuilder extends RoutesLoaderBuilder
{
    protected function definitionName()
    {
        return 'bengor.file_bundle.routing.get_file_routes_loader';
    }

    protected function defaultRouteName($file)
    {
        return sprintf('bengor_file_%s_get_file', $file);
    }

    protected function defaultRoutePath($file)
    {
        return sprintf('/%ss/{id}', $file);
    }

    protected function definitionApiName()
    {
        return 'bengor.file_bundle.routing.api_get_file_routes_loader';
    }

    protected function defaultApiRouteName($file)
    {
        return sprintf('bengor_file_%s_api_get_file', $file);
    }

    protected function defaultApiRoutePath($file)
    {
        return sprintf('/api/%ss/{id}', $file);
    }
}
