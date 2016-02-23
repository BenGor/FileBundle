<?php

namespace BenGor\FileBundle\Form\Type;

use BenGor\File\Domain\Model\FileRepository;
use BenGor\FileBundle\Form\DataTransformer\FileToFileIdTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * File id form type class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileIdType extends AbstractType
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new FileToFileIdTransformer($this->repository);
        $builder->addModelTransformer($transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return HiddenType::class;
    }
}
