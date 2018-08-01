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
        $at       = new \GeekLab\ArrayTranslation();
        $this->at = $at->create('json');
    }

    protected function _after()
    {
    }

    // tests
    public function testJSONEncode()
    {
        // Make sure array converts to a JSON.
        self::assertEquals($this->str, $this->at->encode($this->arr));
    }

    public function testJSONDecode()
    {
        // Make sure JSON converts to array
        self::assertEquals($this->arr, $this->at->decode($this->str));
    }
}