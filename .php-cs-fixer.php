<?php

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_extra_blank_lines' => true,
        'trim_array_spaces' => true,
        'whitespace_after_comma_in_array' => true,
        'single_quote' => true,
        'binary_operator_spaces' => [
            'default' => 'single_space'
        ],
        'braces' => [
            'allow_single_line_closure' => true
        ],
        'indentation_type' => true,
        'method_chaining_indentation' => true, // Ensures proper indentation for method chaining
        'trailing_comma_in_multiline' => ['elements' => ['arrays']], // Forces trailing commas in arrays
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'no_multiline_whitespace_around_double_arrow' => true, // Fixes excessive spaces in arrays
    ])
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
            ->exclude(['vendor', 'storage', 'bootstrap/cache'])
            ->name('*.php')
            ->notName('*.blade.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    );
