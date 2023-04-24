<?php
declare(strict_types=1);

namespace Richbuilds\ArrayOf\Tests;

use InvalidArgumentException;
use Richbuilds\ArrayOf\ArrayOf;
use PHPUnit\Framework\TestCase;
use stdClass;
use Throwable;


/**
 *
 */
class ArrayOfTest extends TestCase
{
    /**
     * @return void
     */
    public function testConstructorWithEmptyArray(): void
    {
        $array = new ArrayOf(stdClass::class);

        self::assertCount(0, $array);
    }


    /**
     * @return void
     * @throws Throwable
     */
    public function testConstructorWithNonEmptyArray(): void
    {
        $array = new ArrayOf(stdClass::class, [new stdClass(), new stdClass()]);

        self::assertCount(2, $array);
    }


    /**
     * @return void
     * @throws Throwable
     */
    public function testSetWithValidValue(): void
    {
        $array = new ArrayOf(stdClass::class);
        $object = new stdClass();

        $array->set('key', $object);

        self::assertEquals($object, $array['key']);
    }


    /**
     * @return void
     *
     * @throws Throwable
     */
    public function testSetWithInvalidValue(): void
    {
        $array = new ArrayOf(stdClass::class);

        self::expectExceptionMessage('expected stdClass got string');
        $array->set('key', 'invalid');
    }


    /**
     * @return void
     * @throws Throwable
     */
    public function testSetWithArray(): void
    {
        $array = new ArrayOf(stdClass::class);
        $objects = [new stdClass(), new stdClass()];

        $array->set(['key1' => $objects[0], 'key2' => $objects[1]]);

        self::assertEquals($objects[0], $array['key1']);
        self::assertEquals($objects[1], $array['key2']);
    }


    /**
     * @return void
     * @throws Throwable
     */
    public function testUnset(): void
    {
        $array = new ArrayOf(stdClass::class);
        $object = new stdClass();
        $array->set('key', $object);

        unset($array['key']);

        self::assertEquals(new ArrayOf(stdClass::class), $array);
    }


    /**
     * @return void
     *
     * @throws Throwable
     */
    public function testAll(): void
    {
        $array = new ArrayOf(stdClass::class);
        $objects = [new stdClass(), new stdClass()];
        $array->set(['key1' => $objects[0], 'key2' => $objects[1]]);

        self::assertEquals(new ArrayOf(stdClass::class,['key1' => $objects[0], 'key2' => $objects[1]]), $array);
    }


    /**
     * @return void
     * @throws Throwable
     */
    public function testForEach(): void {
        $array = new ArrayOf(stdClass::class, [new stdClass(), new stdClass()]);

        foreach($array as $value) {
            self::assertInstanceOf(stdClass::class, $value);
        }
    }


    /**
     * @return void
     * @throws Throwable
     */
    public function testIsset(): void {
        $array = new ArrayOf(stdClass::class, ['key'=>new stdClass()]);

        self::assertTrue(isset($array['key']));
        self::assertFalse(isset($array['missing-key']));
    }


    /**
     * @return void
     */
    public function testOffsetSet(): void {
        $array = new ArrayOf(stdClass::class);

        $array['key'] = new stdClass();
        self::assertTrue(isset($array['key']));
    }


    /**
     * @return void
     */
    public function testOffsetSetFailsForInvalidValue(): void {
        $array = new ArrayOf(stdClass::class);

        self::expectException(InvalidArgumentException::class);
        $array['key'] = 'invalid-value';
    }

    /**
     * @return void
     */
    public function testArrayAppending(): void {

        $array = new ArrayOf(stdClass::class);

        $array[] = new stdClass();

        self::assertCount(1, $array);

    }

    /**
     * @return void
     *
     * @throws Throwable
     */
    public function testSetWithArrayPreservesOriginalOnError(): void {

        $array = new ArrayOf(stdClass::class);

        self::expectException(InvalidArgumentException::class);
        $array->set(['key1' => new stdClass(),'key'=>'invalid value']);

        self::assertEmpty($array);
    }
}
