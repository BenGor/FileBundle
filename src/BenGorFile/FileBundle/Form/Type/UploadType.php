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
use BenGorFile\File\Infrastructure\CommandBus\FileCommandBus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Upload file form type.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $commandBus = $options['command_bus'];
        if (!$commandBus instanceof FileCommandBus) {
            throw new \InvalidArgumentException(sprintf(
                '"command_bus" required form option must be an instance of "%s", "%s" given',
                FileCommandBus::class,
                $commandBus
            ));
        }

        $builder
            ->add('bengor_file', FileType::class, [
                'mapped' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($commandBus) {
                $file = $event->getForm()->get('bengor_file')->getData();
                if (null === $file) {
                    return;
                }

                $commandBus->handle(
                    new UploadFileCommand(
                        $file->getClientOriginalName(),
                        file_get_contents($file->getPathname()),
                        $file->getMimeType()
                    )
                );
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['command_bus']);
    }
}
