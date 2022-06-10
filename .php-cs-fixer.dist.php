<?php

$finder = (new PhpCsFixer\Finder())
    ->in(['src', 'tests'])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => 'This file is part of the Zikula package.

Copyright Zikula - https://ziku.la/

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.',
            'location' => 'after_declare_strict',
        ],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
