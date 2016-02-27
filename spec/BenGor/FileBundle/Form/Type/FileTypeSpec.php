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

namespace spec\BenGor\FileBundle\Form\Type;

use BenGor\File\Application\Service\UploadFileRequest;
use BenGor\File\Domain\Model\FileRepository;
use BenGor\FileBundle\Form\Type\FileIdType;
use BenGor\FileBundle\Form\Type\FileType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as UploadedFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Spec file of file form type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileTypeSpec extends ObjectBehavior
{
    function let(FileRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FileType::class);
    }

    function it_extends_abstract_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_builds(FormBuilderInterface $builder)
    {
        $builder->add('uploaded_file', UploadedFileType::class, [
            'mapped' => false,
        ])->shouldBeCalled()->willReturn($builder);
        $builder->add('name', TextType::class, [
            'required' => false,
        ])->shouldBeCalled()->willReturn($builder);
        $builder->add('file', FileIdType::class, [
            'label'    => false,
            'required' => false,
        ])->shouldBeCalled()->willReturn($builder);
        $builder->setDataMapper($this)->shouldBeCalled()->willReturn($builder);
        $builder->addEventListener('form.post_set_data', Argument::type('closure'))
            ->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, ['request' => UploadFileRequest::class]);
    }

    function it_configures_options(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class'      => null,
            'empty_data'      => null,
            'request'         => UploadFileRequest::class,
        ])->shouldBeCalled()->willReturn($resolver);

        $this->configureOptions($resolver);
    }
}
