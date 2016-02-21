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


$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . '/src')
    ->notName('*.yml')
    ->notName('*.xml')
    ->notName('*Spec.php');

return Symfony\CS\Config\Config::create()
    ->finder($finder)
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->fixers([
        '-unalign_double_arrow',
        '-concat_without_spaces',
        'align_double_arrow',
        'concat_with_spaces',
        'multiline_spaces_before_semicolon',
        'newline_after_open_tag',
        'ordered_use',
        'php4_constructor',
        'phpdoc_order',
        'short_array_syntax',
        'short_echo_tag',
        'strict',
        'strict_param'
    ]);
