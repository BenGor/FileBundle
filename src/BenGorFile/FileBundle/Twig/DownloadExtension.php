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

namespace BenGorFile\FileBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * File download Twig function.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DownloadExtension extends \Twig_Extension
{
    /**
     * The URL generator.
     *
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $anUrlGenerator The URL generator
     */
    public function __construct(UrlGeneratorInterface $anUrlGenerator)
    {
        $this->urlGenerator = $anUrlGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction($this->getName(), [$this, 'download'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Generates the url that returns the file of given file type and name.
     *
     * @param string $fileClass The file type type
     * @param string $name      The file name
     *
     * @return string
     */
    public function download($fileClass, $name)
    {
        return $this->urlGenerator->generate(
            'bengor_file_' . $fileClass . '_download',
            ['filename' => $name],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bengor_file_download';
    }
}
