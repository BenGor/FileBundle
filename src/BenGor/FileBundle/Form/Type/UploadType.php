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
use BenGor\File\Infrastructure\UploadedFile\Symfony\SymfonyUploadedFile;
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
            ->add('file', SymfonyFileType::class, [
                'mapped' => false,
            ])
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
//            'inherit_data' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);
        $forms['name']->setData($data ? $data->name() : '');
        $forms['file']->setData($data ? $data->uploadedFile() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);
        $data = new UploadFileRequest(
            new SymfonyUploadedFile(
                $forms['file']->getData()
            ),
            $forms['name']->getData()
        );
    }
}
