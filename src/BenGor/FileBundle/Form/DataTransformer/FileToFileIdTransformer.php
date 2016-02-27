<?php

/*
 * This file is part of the BenGorFileBundle bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\FileBundle\Form\DataTransformer;

use BenGor\File\Domain\Model\File;
use BenGor\File\Domain\Model\FileId;
use BenGor\File\Domain\Model\FileRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * File to FileId form data transformer class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileToFileIdTransformer implements DataTransformerInterface
{
    /**
     * The file repository.
     *
     * @var FileRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param FileRepository $repository The file repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($file)
    {
        if (!$file instanceof File) {
            throw new TransformationFailedException(
                sprintf(
                    'The entity should be a "BenGor\File\Domain\Model\File instance, %s" instance given',
                    File::class
                )
            );
        }

        return $file->id()->id();
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($id)
    {
        $file = $this->repository->fileOfId(new FileId($id));
        if (!$file instanceof File) {
            throw new TransformationFailedException(
                sprintf('The given %s id does not match with any database file_id', $id)
            );
        }

        return $file;
    }
}
