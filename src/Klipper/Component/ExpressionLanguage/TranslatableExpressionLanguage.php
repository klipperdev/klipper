<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\ExpressionLanguage;

use Klipper\Component\ExpressionLanguage\Exception\InvalidArgumentException;
use Klipper\Component\ExpressionLanguage\Exception\LogicException;
use Klipper\Component\ExpressionLanguage\Functions\BaseFunction;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage as BaseExpressionLanguage;
use Symfony\Component\ExpressionLanguage\ParsedExpression;
use Symfony\Component\ExpressionLanguage\SyntaxError;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Expression language with translatable exception messages.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class TranslatableExpressionLanguage extends BaseExpressionLanguage
{
    /**
     * @var array
     */
    public const MESSAGES = [
        'Unexpected token "(.+)" of value "(.+)"' => 'parser.unexpected_token',
        'The function "(.+)" does not exist' => 'parser.function_not_exist',
        'Variable "(.+)" is not valid' => 'parser.variable_not_valid',
        'A hash key must be a quoted string, a number, a name, or an expression enclosed in parentheses (unexpected token "(.+)" of value "(.+)"' => 'parser.hash_quoted_string',
        'Expected name' => 'parser.expected_name',
        'Unexpected end of expression' => 'token_stream.unexpected_end',
        'Unexpected "(.+)"' => 'lexer.unexpected',
        'Unclosed "(.+)"' => 'lexer.unclosed',
        'Unexpected character "(.+)"' => 'lexer.unexpected_character',
    ];

    protected TranslatorInterface $translator;

    /**
     * @param TranslatorInterface                   $translator The translator
     * @param null|CacheItemPoolInterface           $cache      The cache
     * @param ExpressionFunctionProviderInterface[] $providers  The expression function providers
     */
    public function __construct(
        TranslatorInterface $translator,
        ?CacheItemPoolInterface $cache = null,
        array $providers = []
    ) {
        parent::__construct($cache, $providers);

        $this->translator = $translator;
    }

    /**
     * Get the functions.
     *
     * @return ExpressionFunction[]
     */
    public function getFunctions(): array
    {
        return $this->functions;
    }

    /**
     * Check if the value can be evaluated with the expression language.
     *
     * @param null|mixed $value The value
     */
    public function isEvaluable($value): bool
    {
        if (\is_string($value)) {
            $pattern = '/('.implode('|', array_keys($this->functions)).')\(/';
            preg_match($pattern, $value, $matches);

            return !empty($matches);
        }

        return false;
    }

    public function addFunction(ExpressionFunction $function): void
    {
        if (!$function instanceof BaseFunction) {
            throw new InvalidArgumentException('The expression language function must be an instance of Klipper\Component\ExpressionLanguage\Functions\BaseFunction');
        }

        parent::addFunction($function);
    }

    /**
     * @param mixed $expression
     * @param mixed $names
     */
    public function parse($expression, $names): ParsedExpression
    {
        try {
            return parent::parse($expression, $names);
        } catch (SyntaxError $e) {
            throw new LogicException($this->getTranslatedMessage($e->getMessage()));
        }
    }

    /**
     * Get the translated message of error.
     *
     * @param string $message The error message
     */
    protected function getTranslatedMessage(string $message): string
    {
        foreach (static::MESSAGES as $pattern => $trans) {
            $pattern = '/'.$pattern.' around position ([\d]+) for expression `(.+)`./';
            preg_match($pattern, $message, $matches);

            if ($matches) {
                $params = $matches;
                array_shift($matches);

                foreach ($matches as $pos => $value) {
                    $params['{'.$pos.'}'] = $value;
                }

                $message = $this->translator->trans('expression_language.'.$trans, $params, 'validators');

                break;
            }
        }

        return $message;
    }

    protected function registerFunctions(): void
    {
        // remove all default functions
    }
}
