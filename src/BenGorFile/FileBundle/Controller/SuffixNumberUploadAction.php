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

namespace BenGorFile\FileBundle\Controller;

use BenGorFile\File\Application\Command\Upload\SuffixNumberUploadFileCommand;

/**
 * BenGor file's suffix number upload action trait.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
trait SuffixNumberUploadAction
{
    use UploadAction;

    /**
     * {@inheritdoc}
     */
    private function command()
    {
        return SuffixNumberUploadFileCommand::class;
    }
}
