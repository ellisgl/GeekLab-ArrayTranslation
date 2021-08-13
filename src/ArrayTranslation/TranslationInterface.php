<?php
// Todo: Create a Interfaces\Convert package.
namespace GeekLab\ArrayTranslation;

interface TranslationInterface
{
    /**
     * Encode an array to a string.
     *
     * @param array $arr
     *
     * @return string
     */
    public function encode(array $arr): string;

    /**
     * Decode a string to an array.
     * @param string $str
     *
     * @return array
     */
    public function decode(string $str): array;
}
