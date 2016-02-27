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

use BenGor\File\Domain\Model\FileRepository;
use BenGor\FileBundle\Form\DataTransformer\FileToFileIdTransformer;
use BenGor\FileBundle\Form\Type\FileIdType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Spec file of fileId form type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileIdTypeSpec extends ObjectBehavior
{
    function let(FileRepository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FileIdType::class);
    }

    function it_extends_abstract_type()
    {
        $this->shouldHaveType(AbstractType::class);
    }

    function it_builds(FormBuilderInterface $builder)
    {
        $builder->addModelTransformer(
            Argument::type(FileToFileIdTransformer::class)
        )->shouldBeCalled()->willReturn($builder);

        $this->buildForm($builder, []);
    }

    function it_gets_parent()
    {
        $this->getParent()->shouldReturn(HiddenType::class);
    }
}
