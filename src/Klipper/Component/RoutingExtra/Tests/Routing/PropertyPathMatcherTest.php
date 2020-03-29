<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Component\RoutingExtra\Tests\Routing;

use Klipper\Component\RoutingExtra\Routing\PropertyPathMatcher;
use Klipper\Component\RoutingExtra\Routing\PropertyPathMatcherInterface;
use Klipper\Component\RoutingExtra\Tests\Fixtures\Model\Foo;
use PHPUnit\Framework\TestCase;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 *
 * @internal
 */
final class PropertyPathMatcherTest extends TestCase
{
    /**
     * @var PropertyPathMatcherInterface
     */
    protected $matcher;

    protected function setUp(): void
    {
        $this->matcher = new PropertyPathMatcher();
    }

    public function getMatcherConfig()
    {
        $dataArray = [
            'custom_id' => 42,
        ];
        $dataObject = new Foo();
        $dataObject->setBar(42);

        return [
            [$dataArray, ['id' => '{{[custom_id]}}'], ['id' => 42]],
            [$dataArray, ['id' => '{{[custom_id] }}'], ['id' => 42]],
            [$dataArray, ['id' => '{{ [custom_id]}}'], ['id' => 42]],
            [$dataArray, ['id' => '{{ [custom_id] }}'], ['id' => 42]],

            [$dataObject, ['id' => '{{bar}}'], ['id' => 42]],
            [$dataObject, ['id' => '{{bar }}'], ['id' => 42]],
            [$dataObject, ['id' => '{{ bar}}'], ['id' => 42]],
            [$dataObject, ['id' => '{{ bar }}'], ['id' => 42]],
        ];
    }

    /**
     * @dataProvider getMatcherConfig
     *
     * @param array|object $data
     * @param array        $parameters
     * @param array        $validParameters
     */
    public function testMatcherParameters($data, array $parameters, array $validParameters): void
    {
        $result = $this->matcher->matchRouteParameters($parameters, $data);

        $this->assertEquals($validParameters, $result);
    }
}
