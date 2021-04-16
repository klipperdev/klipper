<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DoctrineChoice\Validator\Constraints;

use Klipper\Component\DoctrineChoice\Model\ChoiceInterface;
use Klipper\Component\DoctrineExtensionsExtra\Validator\Constraints\EntityChoice;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @Annotation
 * @Target({"PROPERTY", "METHOD", "ANNOTATION"})
 */
class EntityDoctrineChoice extends EntityChoice
{
    public ?string $entityClass = ChoiceInterface::class;

    public ?string $namePath = 'value';

    /**
     * Define the type of the doctrine choice.
     */
    public ?string $type = null;

    public function __construct(
        $choices = null,
        $callback = null,
        bool $multiple = null,
        bool $strict = null,
        int $min = null,
        int $max = null,
        string $message = null,
        string $multipleMessage = null,
        string $minMessage = null,
        string $maxMessage = null,
        $groups = null,
        $payload = null,
        array $options = []
    ) {
        parent::__construct(
            $choices,
            $callback,
            $multiple,
            $strict,
            $min,
            $max,
            $message,
            $multipleMessage,
            $minMessage,
            $maxMessage,
            $groups,
            $payload,
            $options
        );

        $this->criteria = array_merge($this->criteria, [
            'type' => $this->type,
        ]);
    }

    public function getDefaultOption(): string
    {
        return 'type';
    }

    public function getRequiredOptions(): array
    {
        return [
            'type',
        ];
    }
}
