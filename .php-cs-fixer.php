<?php

use PhpCsFixer\Config;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessParenthesisFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessStrlenFixer;
use PhpCsFixerCustomFixers\Fixer\PhpdocParamTypeFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceAfterStatementFixer;
use PhpCsFixerCustomFixers\Fixer\SingleSpaceBeforeStatementFixer;
use PhpCsFixerCustomFixers\Fixer\NoSuperfluousConcatenationFixer;
use PhpCsFixerCustomFixers\Fixers;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$header = <<<EOF
(c) shopware AG <info@shopware.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return (new Config())
    ->registerCustomFixers(new Fixers())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,

        'class_attributes_separation' => ['elements' => ['method' => 'one', 'property' => 'one']],
        'concat_space' => ['spacing' => 'one'],
        'doctrine_annotation_indentation' => true,
        'doctrine_annotation_spaces' => true,
        'general_phpdoc_annotation_remove' => [
             'annotations' => ['copyright', 'category'],
        ],
        'header_comment' => ['header' => $header, 'separate' => 'bottom', 'comment_type' => 'PHPDoc'],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_superfluous_phpdoc_tags' => true,
        'phpdoc_order' => true,
        'phpdoc_summary' => false,
        'phpdoc_var_annotation_correct_order' => true,
        'php_unit_test_case_static_method_calls' => true,
        'single_line_throw' => false,
        'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
        'operator_linebreak' => ['only_booleans' => true],
        'native_function_invocation' => true,
        'declare_strict_types' => true,
        'void_return' => true,

        NoUselessCommentFixer::name() => true,
        SingleSpaceAfterStatementFixer::name() => true,
        SingleSpaceBeforeStatementFixer::name() => true,
        PhpdocParamTypeFixer::name() => true,
        NoSuperfluousConcatenationFixer::name() => true,
        NoUselessStrlenFixer::name() => true,
        NoUselessParenthesisFixer::name() => true,
    ])
    ->setFinder($finder);
