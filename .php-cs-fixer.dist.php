<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__);

$config = new PhpCsFixer\Config();
return $config->setRules([
//        'array_syntax' => ['syntax' => 'short'],
//        'binary_operator_spaces' => ['operators' =>
//            [
//                '=' => 'single_space',
//                '=>' => 'single_space'
//            ]
//        ],
//        'blank_line_after_namespace' => true,
//        'blank_line_after_opening_tag' => true,
//        'blank_line_before_statement' => true,
//        'braces' => true,
//        'cast_spaces' => true,
        'ordered_imports' => true,
        'no_unused_imports' => true,
    ])->setFinder($finder);
