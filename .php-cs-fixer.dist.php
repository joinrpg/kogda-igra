<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/public_html');

return (new PhpCsFixer\Config())
    ->setIndent('    ')
    ->setLineEnding("\r\n")
    ->setRules([
        'encoding'                          => true,
        'indentation_type'                  => true,
        'line_ending'                       => true,
        'no_trailing_whitespace'            => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_whitespace_in_blank_line'       => true,
        'single_blank_line_at_eof'          => true,
    ])
    ->setFinder($finder);
