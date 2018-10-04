<?php
class PHPTest extends \Codeception\Test\Unit
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
    protected $str = 'a|s:1:"b";c|a:2:{s:1:"d";s:1:"e";i:0;s:1:"f";}';

    protected function _before()
    {
        $this->at = \GeekLab\ArrayTranslation::create('php');
    }

    protected function _after()
    {
    }

    // Encoder Tests

    /** @test */
    public function it_can_encode_php_serialized()
    {
        //var_dump($this->at->encode($this->arr));
        self::assertEquals($this->str, $this->at->encode($this->arr));
    }


    /** @test */
    public function it_can_decode_php_serialized()
    {
        self::assertEquals($this->arr, $this->at->decode($this->str));
    }
}