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

use BenGorFile\FileBundle\Form\Type\FileType;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as BaseFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Spec file of FileType class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileTypeSpec extends ObjectBehavior
{
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
        $builder->add('bengor_file', BaseFileType::class, [
            'required' => false,
            'label'    => false,
            'attr'     => '',
            'mapped'   => false,
        ])->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, [
            'required' => false,
            'label'    => false,
            'attr'     => '',
            'mapped'   => false,
        ]);
    }

    function it_configures_options(OptionsResolver $resolver)
    {
        $this->configureOptions($resolver);
    }
}
