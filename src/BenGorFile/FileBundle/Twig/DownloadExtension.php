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

use BenGorFile\File\Application\Query\FileOfIdQuery;
use BenGorFile\File\Domain\Model\FileDoesNotExistException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * File download by id Twig function.
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
     * The file handlers.
     *
     * @var array
     */
    private $handlers;

    /**
     * Constructor.
     *
     * @param UrlGeneratorInterface $anUrlGenerator The URL generator
     * @param array                 $handlers       The file handlers
     */
    public function __construct(UrlGeneratorInterface $anUrlGenerator, array $handlers)
    {
        $this->urlGenerator = $anUrlGenerator;
        $this->handlers = $handlers;
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
     * Generates the url that returns the file of given file type and file.
     *
     * @param string $fileClass The file type type
     * @param string $q         The file query
     *
     * @return string
     */
    public function download($fileClass, $q)
    {
        try {
            $file = $this->handlers[$fileClass]->__invoke(new FileOfIdQuery($q));
            $filename = $file['file_name'];
        } catch (FileDoesNotExistException $exception) {
            $filename = $q;
        }

        return $this->urlGenerator->generate(
            'bengor_file_' . $fileClass . '_download',
            ['filename' => $filename],
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
