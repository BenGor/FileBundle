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

namespace BenGorFile\FileBundle\Form\Type;

use BenGorFile\File\Application\Command\Upload\UploadFileCommand;
use BenGorFile\File\Domain\Model\File;
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileRepository;
use BenGorFile\File\Infrastructure\CommandBus\FileCommandBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as BaseFileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * File form type.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileType extends AbstractType
{
    /**
     * The file command bus.
     *
     * @var FileCommandBus
     */
    protected $commandBus;

    /**
     * The file repository.
     *
     * @var FileRepository
     */
    protected $repository;

    /**
     * Constructor.
     *
     * @param FileCommandBus $commandBus The file command bus
     * @param FileRepository $repository The file repository
     */
    public function __construct(FileCommandBus $commandBus, FileRepository $repository)
    {
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('bengor_file', BaseFileType::class, [
            'required' => $options['required'],
            'label'    => $options['label'],
            'attr'     => $options['attr'],
            'mapped'   => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'     => File::class,
            'error_bubbling' => false,
            'label'          => false,
            'required'       => false,
            'empty_data'     => function (FormInterface $form) {
                return $this->emptyData($form);
            },
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'bengor_file';
    }

    /**
     * Method that encapsulates all the logic of build empty data.
     * It returns an instance of data class object.
     *
     * @param \Symfony\Component\Form\FormInterface $form The form
     *
     * @return File|null
     */
    public function emptyData(FormInterface $form)
    {
        $file = $form->get('bengor_file')->getData();
        if (null === $file) {
            return null;
        }
        $command = new UploadFileCommand(
            $file->getClientOriginalName(),
            file_get_contents($file->getPathname()),
            $file->getMimeType()
        );
        $this->commandBus->handle($command);

        return $this->repository->fileOfId(new FileId($command->id()));
    }
}
