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
     * The file type.
     *
     * @var string
     */
    private $fileClass;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $anUrlGenerator The URL generator
     * @param string                $aFileClass     The file type
     */
    public function __construct(UrlGeneratorInterface $anUrlGenerator, $aFileClass)
    {
        $this->urlGenerator = $anUrlGenerator;
        $this->fileClass = $aFileClass;
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
     * Generates the url that returns the file of given name.
     *
     * @param string $name The file name
     *
     * @return string
     */
    public function download($name)
    {
        return $this->urlGenerator->generate(
            'bengor_file_' . $this->fileClass . '_download',
            ['filename' => $name],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bengor_file_' . $this->fileClass . '_download';
    }
}
