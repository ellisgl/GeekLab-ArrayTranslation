<?php

use Spatie\Snapshots\MatchesSnapshots;

class XMLTest extends \Codeception\Test\Unit
{
    use MatchesSnapshots;

    /**
     * @var \UnitTester
     */
    protected $tester;

    /** @test array */
    protected $testArray = [];

    /** #test array */
    protected $ultimateArray = [
        [
            [
                'good_guy' => [
                    'name' => 'Luke Skywalker',
                    'weapon' => 'Lightsaber',
                ],
                'bad_guy' => [
                    'name' => 'Sauron',
                    'weapon' => 'Evil Eye',
                ],
            ],
        ],
        [
            [
                'good_guy' => [
                    '_attributes' => ['attr1' => 'value'],
                    'name' => 'Luke Skywalker',
                    'weapon' => 'Lightsaber',
                ],
                'bad_guy' => [
                    'name' => 'Sauron',
                    'weapon' => 'Evil Eye',
                ],
            ],
        ],
        [
            [
                'good_guy' => [
                    'name' => [
                        '_cdata' => '<h1>Luke Skywalker</h1>',
                    ],
                    'weapon' => 'Lightsaber',
                ],
                'bad_guy' => [
                    'name' => '<h1>Sauron</h1>',
                    'weapon' => 'Evil Eye',
                ],
            ],
        ],
    ];

    /**
     * @var \GeekLab\ArrayTranslation\DomTranslationInterface
     */
    protected $at;

    /**
     * Test in the inverse. =)
     *
     * @param  string $testName
     * @return bool|string
     */
    protected function getXMLSnapshot(string $testName)
    {
        return file_get_contents($this->getSnapshotDirectory() . DIRECTORY_SEPARATOR . get_class($this) . '__' . $testName .'__1.xml');
    }

    protected function _before()
    {
        $this->testArray = [
            'Good guy' => [
                'name'   => 'Luke Skywalker',
                'weapon' => 'Lightsaber',
            ],
            'Bad guy'  => [
                'name'   => 'Sauron',
                'weapon' => 'Evil Eye',
            ],
        ];

        $at       = new \GeekLab\ArrayTranslation();
        $this->at = $at->create('xml');
    }

    protected function _after()
    {
    }

    // Encoder Tests

    /** @test */
    public function it_can_encode_xml()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode($this->testArray));
    }

    /** @test */
    public function it_can_encode_empty_array()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([]));
    }

    /** @test */
    public function it_can_encode_root_element()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([], 'helloyouluckpeople'));
    }

    /** @test */
    public function it_can_encode_root_element_attributes_can_also_be_set_in_simplexmlelement_style()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([], [
            '@attributes' => [
                'xmlns' => 'https://github.com/ellisgl/GeekLab-ArrayTranslation',
            ],
        ]));
    }

    /** @test */
    public function it_can_encode_array_with_no_keys()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode(['one', 'two', 'three']));
    }

    /** @test */
    public function encode_will_raise_an_exception_when_spaces_should_not_be_replaced_and_a_key_contains_a_space()
    {
        $this->expectException('DOMException');
        $this->at->encode($this->testArray, '', false);
    }

    /** @test */
    public function it_can_encode_values_as_basic_collection()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'user' => ['one', 'two', 'three'],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_xml_encoding_type()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([], '', false, 'UTF-8'));
    }

    /** @test */
    public function it_can_encode_xml_version()
    {
        $this->assertMatchesSnapshot($this->at->encode([], '', false, null, '1.1'));
    }

    /** @test */
    public function it_can_encode_values_as_collection()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'user' => [
                                                                  [
                                                                      'name' => 'een',
                                                                      'age'  => 10,
                                                                  ],
                                                                  [
                                                                      'name' => 'twee',
                                                                      'age'  => 12,
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_mixed_sequential_array()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'user' => [
                                                                  [
                                                                      'name' => 'een',
                                                                      'age'  => 10,
                                                                  ],
                                                                  'twee' => [
                                                                      'name' => 'twee',
                                                                      'age'  => 12,
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_values_with_special_characters()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode(['name' => 'this & that']));
    }

    /** @test */
    public function it_can_encode_group_by_values_when_values_are_in_a_numeric_array()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode(['user' => ['foo', 'bar']]));
    }

    /** @test */
    public function it_can_encode_attributes_to_xml()
    {
        $withAttributes                            = $this->testArray;
        $withAttributes['Good guy']['_attributes'] = ['nameType' => 1];
        $this->assertMatchesXmlSnapshot($this->at->encode($withAttributes));
    }

    /** @test */
    public function it_can_encode_attributes_as_collection()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'user' => [
                                                                  [
                                                                      '_attributes' => [
                                                                          'name' => 'een',
                                                                          'age'  => 10,
                                                                      ],
                                                                  ],
                                                                  [
                                                                      '_attributes' => [
                                                                          'name' => 'twee',
                                                                          'age'  => 12,
                                                                      ],
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_attributes_also_can_be_set_in_simplexmlelement_style()
    {
        $withAttributes                            = $this->testArray;
        $withAttributes['Good guy']['@attributes'] = ['nameType' => 1];

        $this->assertMatchesXmlSnapshot($this->at->encode($withAttributes));
    }

    /** @test */
    public function it_can_encode_values_set_with_attributes_with_special_characters()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'movie' => [
                                                                  [
                                                                      'title' => [
                                                                          '_attributes' => ['category' => 'SF'],
                                                                          '_value'      => 'STAR WARS',
                                                                      ],
                                                                  ],
                                                                  [
                                                                      'title' => [
                                                                          '_attributes' => ['category' => 'Children'],
                                                                          '_value'      => 'tom & jerry',
                                                                      ],
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_value_also_can_be_set_in_simplexmlelement_style()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'movie' => [
                                                                  [
                                                                      'title' => [
                                                                          '@attributes' => ['category' => 'SF'],
                                                                          '@value'      => 'STAR WARS',
                                                                      ],
                                                                  ],
                                                                  [
                                                                      'title' => [
                                                                          '@attributes' => ['category' => 'Children'],
                                                                          '@value'      => 'tom & jerry',
                                                                      ],
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_values_set_as_cdata()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'movie' => [
                                                                  [
                                                                      'title' => [
                                                                          '_attributes' => ['category' => 'SF'],
                                                                          '_cdata'      => '<p>STAR WARS</p>',
                                                                      ],
                                                                  ],
                                                                  [
                                                                      'title' => [
                                                                          '_attributes' => ['category' => 'Children'],
                                                                          '_cdata'      => '<p>tom & jerry</p>',
                                                                      ],
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_cdata_values_can_also_be_set_in_simplexmlelement_style()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'movie' => [
                                                                  [
                                                                      'title' => [
                                                                          '@attributes' => ['category' => 'SF'],
                                                                          '@cdata'      => '<p>STAR WARS</p>',
                                                                      ],
                                                                  ],
                                                                  [
                                                                      'title' => [
                                                                          '@attributes' => ['category' => 'Children'],
                                                                          '@cdata'      => '<p>tom & jerry</p>',
                                                                      ],
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function encode_doesnt_pollute_attributes_in_collection_and_sequential_nodes()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode([
                                                              'books' => [
                                                                  'book' => [
                                                                      ['name' => 'A', '@attributes' => ['z' => 1]],
                                                                      ['name' => 'B'],
                                                                      ['name' => 'C'],
                                                                  ],
                                                              ],
                                                          ]));
    }

    /** @test */
    public function it_can_encode_keys_starting_with_numeric()
    {
        $this->assertMatchesXmlSnapshot($this->at->encode(['10this' => 1]));
    }

    /** @test */
    public function encode_can_convert_array_to_dom()
    {
        $this->at->convertToDom($this->testArray);

        $resultDom = $this->at->toDom();

        $this->assertSame('Luke Skywalker', $resultDom->getElementsByTagName('name')->item(0)->nodeValue);
        $this->assertSame('Sauron', $resultDom->getElementsByTagName('name')->item(1)->nodeValue);
        $this->assertSame('Lightsaber', $resultDom->getElementsByTagName('weapon')->item(0)->nodeValue);
        $this->assertSame('Evil Eye', $resultDom->getElementsByTagName('weapon')->item(1)->nodeValue);
    }

    // Decoder tests
    /** @test */
    public function it_can_decode_xml()
    {
        $arr = $this->at->encode($this->ultimateArray);
        var_dump($arr);
        var_dump($this->at->decode($arr));
        //echo $this->getXMLSnapshot('it_can_encode_xml');
        //$this->assertMatchesSnapshot($this->at->decode($this->getXMLSnapshot('it_can_encode_xml')));
    }
}