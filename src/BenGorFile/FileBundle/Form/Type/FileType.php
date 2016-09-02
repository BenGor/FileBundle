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
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as BaseFileType;
use Symfony\Component\Form\FormBuilderInterface;
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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('bengor_file', BaseFileType::class, [
            'required' => $options['required'],
            'label'    => $options['label'],
            'attr'     => $options['attr'],
            'mapped'   => $options['mapped'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'     => UploadFileCommand::class,
            'error_bubbling' => false,
            'label'          => false,
            'required'       => false,
            'mapped'         => false,
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
     * @param FormInterface $form The form
     *
     * @return UploadFileCommand|null
     */
    public function emptyData(FormInterface $form)
    {
        $file = $form->get('bengor_file')->getData();
        if (null === $file) {
            return;
        }

        return new UploadFileCommand(
            $file->getClientOriginalName(),
            file_get_contents($file->getPathname()),
            $file->getMimeType()
        );
    }
}
