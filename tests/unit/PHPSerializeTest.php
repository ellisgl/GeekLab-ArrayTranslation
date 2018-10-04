<?php
class PHPSerializeTest extends \Codeception\Test\Unit
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
    protected $str = 'a:2:{s:1:"a";s:1:"b";s:1:"c";a:2:{s:1:"d";s:1:"e";i:0;s:1:"f";}}';

    protected function _before()
    {
        $this->at = \GeekLab\ArrayTranslation::create('php_serialize');
    }

    protected function _after()
    {
    }

    // Encoder Tests

    /** @test */
    public function it_can_encode_php_serialized_function()
    {
        self::assertEquals($this->str, $this->at->encode($this->arr));
    }

    /** @test */
    public function it_can_decode_php_serialized_function()
    {
        self::assertEquals($this->arr, $this->at->decode($this->str));
    }
}