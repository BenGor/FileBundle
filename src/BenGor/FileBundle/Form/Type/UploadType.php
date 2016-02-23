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

namespace BenGor\FileBundle\Form\Type;

use BenGor\File\Application\Service\UploadFileRequest;
use BenGor\File\Domain\Model\File;
use BenGor\File\Infrastructure\UploadedFile\Symfony\SymfonyUploadedFile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType as SymfonyFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Upload file form type.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadType extends AbstractType implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('uploaded_file', SymfonyFileType::class, [
                'mapped' => false,
            ])
            ->add('file', EntityHiddenType::class, array(
                'label' => false,
                'class' => File::class,
                'required' => false,
//                'mapped' => false
            ))
            ->setDataMapper($this);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class'      => UploadFileRequest::class,
            'empty_data'      => null,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);
        $forms['name']->setData($data ? $data->name() : '');
        $forms['uploaded_file']->setData($data ? $data->uploadedFile() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        $data = new UploadFileRequest(
            new SymfonyUploadedFile(
                $forms['uploaded_file']->getData()
            ),
            $forms['name']->getData()
        );
    }
}
