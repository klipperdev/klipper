<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\DefaultValue;

/**
 * A builder for creating {@link Block} instances.
 *
 * @author Francois Pluchino
 */
class ObjectBuilder extends ObjectConfigBuilder implements ObjectBuilderInterface
{
    private ObjectFactoryInterface $factory;

    /**
     * Creates a new object default value builder.
     */
    public function __construct(ObjectFactoryInterface $factory, array $options = [])
    {
        parent::__construct($options);

        $this->factory = $factory;
    }

    public function getObjectFactory(): ObjectFactoryInterface
    {
        return $this->factory;
    }

    public function getObject(): object
    {
        if (null === $this->getData()) {
            $this->setData($this->getType()->newInstance($this, $this->getOptions()));
        }

        $this->getType()->buildObject($this, $this->getOptions());
        $this->getType()->finishObject($this, $this->getOptions());

        $this->getObjectConfig();

        return $this->data;
    }
}
