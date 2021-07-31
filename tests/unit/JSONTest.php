<?php

namespace unit;

use Codeception\Test\Unit;
use GeekLab\ArrayTranslation;
use GeekLab\ArrayTranslation\TranslationInterface;
use UnitTester;

class JSONTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * @var TranslationInterface
     */
    protected $at;

    /**
     * @var array
     */
    protected $arr = ['a' => 'b', 'c' => ['d' => 'e', 'f']];

    /**
     * @var string
     */
    protected $str = '{"a":"b","c":{"d":"e","0":"f"}}';

    protected function _before(): void
    {
        $this->at = ArrayTranslation::create('json');
    }

    protected function _after(): void
    {
    }

    /** @test */
    public function it_can_encode_JSON(): void
    {
        // Make sure array converts to a JSON.
        self::assertEquals($this->str, $this->at->encode($this->arr));
    }

    /** @test */
    public function it_can_decode_JSON(): void
    {
        // Make sure JSON converts to array
        self::assertEquals($this->arr, $this->at->decode($this->str));
    }
}
