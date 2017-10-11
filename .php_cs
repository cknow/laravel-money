<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@DoctrineAnnotation' => true,
        '@PHP70Migration' => true,
        'align_multiline_comment' => true,
        'array_syntax' => ['syntax' => 'short'],
        'combine_consecutive_issets' => true,
        'combine_consecutive_unsets' => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ["author", "package"]],
        'heredoc_to_nowdoc' => true,
        'linebreak_after_opening_tag' => true,
        'list_syntax' => ['syntax' => 'short'],
        'no_multiline_whitespace_before_semicolons' => true,
        'no_php4_constructor' => true,
        'no_short_echo_tag' => true,
        'no_unreachable_default_argument_value' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => ['only_untyped' => false],
        'phpdoc_order' => true,
        'phpdoc_types_order' => ['null_adjustment'=> 'always_last'],
        'semicolon_after_instruction' => true,
        'single_line_comment_style' => true
    ])
    ->setCacheFile(__DIR__.'/.php_cs.cache')
    ->setFinder($finder)
    ->setRiskyAllowed(true)
;
