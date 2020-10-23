<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\Choice\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Choice as BaseChoice;
use Symfony\Component\Validator\Constraints\ChoiceValidator;

/**
 * Choice constraint.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @Annotation
 */
class Choice extends BaseChoice
{
    /**
     * @var bool
     */
    public $strict = true;

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return ChoiceValidator::class;
    }
}
