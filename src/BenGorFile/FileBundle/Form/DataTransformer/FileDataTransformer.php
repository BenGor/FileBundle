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

namespace BenGorFile\FileBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * File form data transformer.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileDataTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($file)
    {
        return [
            'bengor_file' => $file,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($data)
    {
        return $data['bengor_file'];
    }
}
