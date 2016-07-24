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

use BenGorFile\File\Domain\Model\FileName;
use BenGorFile\File\Domain\Model\Filesystem;

class ViewExtension extends \Twig_Extension
{
    /**
     * The filesystem.
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Constructor.
     *
     * @param Filesystem $aFilesystem The filesystem
     */
    public function __construct($aFilesystem)
    {
        $this->filesystem = $aFilesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('bengor_file_filesystem_view', [$this, 'view'], ['is_safe' => ['html']]),
        ];
    }

    /**
     *
     */
    public function view($name)
    {
        // TODO
    }


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bengor_file_filesystem_view';
    }
}
