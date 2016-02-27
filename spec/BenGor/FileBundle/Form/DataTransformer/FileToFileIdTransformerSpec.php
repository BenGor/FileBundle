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

namespace spec\BenGor\FileBundle\Form\DataTransformer;

use BenGor\File\Domain\Model\File;
use BenGor\File\Domain\Model\FileId;
use BenGor\File\Domain\Model\FileRepository;
use BenGor\FileBundle\Form\DataTransformer\FileToFileIdTransformer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Spec file of file to fileId data transformer form class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileToFileIdTransformerSpec extends ObjectBehavior
{
    function let(FileRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FileToFileIdTransformer::class);
    }

    function it_implements_data_transformer_interface()
    {
        $this->shouldImplement(DataTransformerInterface::class);
    }

    function it_does_not_transform_because_file_is_null()
    {
        $this->transform(null);
    }

    function it_does_not_transform_because_file_is_not_a_file_instance()
    {
        $this->shouldThrow(TransformationFailedException::class)->duringTransform('not-file-instance');
    }

    function it_transforms(File $file)
    {
        $fileId = new FileId('dummy-file-id');
        $file->id()->shouldBeCalled()->willReturn($fileId);

        $this->transform($file);
    }

    function it_does_not_reverse_transform_because_file_id_is_null()
    {
        $this->reverseTransform(null);
    }

    function it_does_not_reverse_transform_because_file_of_id_given_does_not_exist(FileRepository $repository)
    {
        $fileId = new FileId('non-exist-file-id');
        $repository->fileOfId($fileId)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(TransformationFailedException::class)->duringReverseTransform('non-exist-file-id');
    }

    function it_reverse_transforms(FileRepository $repository, File $file)
    {
        $fileId = new FileId('dummy-file-id');
        $repository->fileOfId($fileId)->shouldBeCalled()->willReturn($file);

        $this->reverseTransform('dummy-file-id');
    }
}
