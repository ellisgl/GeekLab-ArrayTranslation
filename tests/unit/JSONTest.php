<?php

class JSONTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \GeekLab\ArrayTranslation\TranslationInterface
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

    protected function _before()
    {
        $this->at = \GeekLab\ArrayTranslation::create('json');
    }

    protected function _after()
    {
    }

    /** @test */
    public function it_can_encode_JSON()
    {
        // Make sure array converts to a JSON.
        self::assertEquals($this->str, $this->at->encode($this->arr));
    }

    /** @test */
    public function it_can_decode_JSON()
    {
        // Make sure JSON converts to array
        self::assertEquals($this->arr, $this->at->decode($this->str));
    }
}