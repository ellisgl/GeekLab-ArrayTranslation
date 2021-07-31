<?php

namespace unit;

use Codeception\Test\Unit;
use GeekLab\ArrayTranslation;
use GeekLab\ArrayTranslation\TranslationInterface;
use UnitTester;

class YAMLTest extends Unit
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
    protected $str = "";

    protected function _before(): void
    {
        $this->at = ArrayTranslation::create('yaml');
        $this->str = <<<EOT
---
a: b
c:
  d: e
  0: f
...

EOT;

    }

    protected function _after(): void
    {
    }

    // Encoder Tests

    /** @test */
    public function it_can_encode_yaml(): void
    {
        self::assertEquals($this->str, $this->at->encode($this->arr));
    }


    /** @test */
    public function it_can_decode_yaml(): void
    {
        self::assertEquals($this->arr, $this->at->decode($this->str));
    }
}
