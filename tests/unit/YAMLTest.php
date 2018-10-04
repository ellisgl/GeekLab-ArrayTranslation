<?php
class YAMLTest extends \Codeception\Test\Unit
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
    protected $str = "";

    protected function _before()
    {
        $this->at = \GeekLab\ArrayTranslation::create('yaml');
        $this->str = <<<EOT
---
a: b
c:
  d: e
  0: f
...

EOT;

    }

    protected function _after()
    {
    }

    // Encoder Tests

    /** @test */
    public function it_can_encode_yaml()
    {
        //var_dump($this->at->encode($this->arr));
        self::assertEquals($this->str, $this->at->encode($this->arr));
    }


    /** @test */
    public function it_can_decode_yaml()
    {
        self::assertEquals($this->arr, $this->at->decode($this->str));
    }
}