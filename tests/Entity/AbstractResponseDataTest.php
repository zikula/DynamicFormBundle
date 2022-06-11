<?php

declare(strict_types=1);

/*
 * This file is part of the Zikula package.
 *
 * Copyright Zikula - https://ziku.la/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zikula\Bundle\DynamicFormBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Zikula\Bundle\DynamicFormBundle\Entity\AbstractResponseData;

/**
 * Note: phpstan doesn't like magic __get, __set, __isset, __unset.
 * This is why so many lines are ignored below.
 *
 * @covers \Zikula\Bundle\DynamicFormBundle\Entity\AbstractResponseData
 */
class AbstractResponseDataTest extends TestCase
{
    private AbstractResponseData $responseData;

    protected function setUp(): void
    {
        $this->responseData = new class() extends AbstractResponseData {
        };
    }

    public function testSetAndGet(): void
    {
        $data = [
            'foo' => 1,
            'bar' => 'red',
            'baz' => ['one' => 1],
        ];
        $this->responseData->setData($data);

        $this->assertSame($data, $this->responseData->getData());
    }

    public function testMagicSetter(): void
    {
        $this->responseData->foo = 1; // @phpstan-ignore-line
        /** @var array<string, mixed> $data */
        $data = $this->responseData->getData();
        $this->assertSame(1, $data['foo']);
    }

    public function testMagicGetter(): void
    {
        $this->responseData->setData(['foo' => 1]);
        $this->assertSame(1, $this->responseData->foo); // @phpstan-ignore-line
    }

    public function testMagicIsset(): void
    {
        $this->responseData->setData(['foo' => 1]);
        $this->assertTrue(isset($this->responseData->foo));
        $this->assertFalse(isset($this->responseData->bar));
    }

    public function testMagicUnset(): void
    {
        $this->responseData->setData(['foo' => 1]);
        unset($this->responseData->foo);
        $this->assertFalse(isset($this->responseData->foo));
        $this->assertNull($this->responseData->foo); // uses __get @phpstan-ignore-line
    }
}
