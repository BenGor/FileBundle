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

use AppBundle\Entity\User;
use BenGor\File\Application\Service\UploadFileRequest;
use BenGor\File\Infrastructure\UploadedFile\Symfony\SymfonyUploadedFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType as SymfonyFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType //implements DataMapperInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
            ])
            ->add('photo', UploadType::class, [
//                'mapped' => false,
            ]);
//            ->setDataMapper($this);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
//            'empty_data' => null,
        ]);
    }

//    /**
//     * {@inheritdoc}
//     */
//    public function mapDataToForms($data, $forms)
//    {
//        $forms = iterator_to_array($forms);
//        $forms['firstName']->setData($data ? $data->name() : '');
//        $forms['file']->setData($data ? $data->uploadedFile() : null);
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function mapFormsToData($forms, &$data)
//    {
//        $forms = iterator_to_array($forms);
//        $data = new UploadFileRequest(
//            new SymfonyUploadedFile(
//                $forms['file']->getData()
//            ),
//            $forms['name']->getData()
//        );
//    }
}
