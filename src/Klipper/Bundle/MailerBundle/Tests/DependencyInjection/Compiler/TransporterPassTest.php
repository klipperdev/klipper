<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\MailerBundle\Tests\DependencyInjection\Compiler;

use Klipper\Bundle\MailerBundle\DependencyInjection\Compiler\TransporterPass;
use Klipper\Component\Mailer\Mailer;
use Klipper\Component\Mailer\Transporter\TransporterInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

/**
 * Tests for transport pass.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class TransporterPassTest extends KernelTestCase
{
    /**
     * @var TransporterPass
     */
    protected $pass;

    protected function setUp(): void
    {
        $this->pass = new TransporterPass();
    }

    protected function tearDown(): void
    {
        $this->pass = null;
    }

    public function testProcessWithoutService(): void
    {
        $container = $this->getContainer();

        $this->pass->process($container);
        static::assertFalse($container->has('klipper_mailer.mailer'));
    }

    public function testProcessWithAddTransporters(): void
    {
        $container = $this->getContainer();
        $mailerDef = new Definition(Mailer::class);
        $mailerDef->setArguments([[]]);

        $container->setDefinition('klipper_mailer.mailer', $mailerDef);

        static::assertCount(0, $mailerDef->getArgument(0));

        // add mocks
        $transporter = new Definition(TransporterInterface::class);
        $transporter->addTag('klipper_mailer.transporter');

        $container->setDefinition('test.transporter', $transporter);

        // test
        $this->pass->process($container);

        static::assertCount(1, $mailerDef->getArgument(0));
    }

    /**
     * Gets the container.
     *
     * @return ContainerBuilder
     */
    protected function getContainer(): ContainerBuilder
    {
        return new ContainerBuilder(new ParameterBag([
            'kernel.debug' => false,
            'kernel.environment' => 'test',
            'kernel.name' => 'kernel',
            'kernel.charset' => 'UTF-8',
            'kernel.bundles' => [],
        ]));
    }
}
