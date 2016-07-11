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

namespace spec\BenGorFile\FileBundle\Form\Type;

use BenGorFile\File\Infrastructure\CommandBus\FileCommandBus;
use BenGorFile\FileBundle\Form\Type\UploadType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Spec file of file form type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(UploadType::class);
    }

    function it_extends_abstract_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_builds(FormBuilderInterface $builder, FileCommandBus $fileCommandBus)
    {
        $builder->add('bengor_file', FileType::class, [
            'mapped' => false,
        ])->shouldBeCalled()->willReturn($builder);
        $builder->addEventListener(FormEvents::POST_SUBMIT, Argument::type('closure'))
            ->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, ['command_bus' => $fileCommandBus]);
    }

    function it_configures_options(OptionsResolver $resolver)
    {
        $resolver->setRequired(['command_bus'])->shouldBeCalled()->willReturn($resolver);

        $this->configureOptions($resolver);
    }
}
