<?php

namespace GeekLab\ArrayTranslation;

/**
 * Translate array <-> igbinary
 *
 * Class igbinary
 * @package GeekLab\ArrayTranslation
 */
class igbinary implements TranslationInterface
{
    /**
     * @param  array $arr
     * @return string
     */
    public function encode(array $arr): string
    {
        if (empty($arr))
        {
            return '';
        }

        return '';
    }

    /**
     * @param  string $str
     * @return array
     */
    public function decode(string $str): array
    {
        if(empty($str))
        {
            return [];
        }

        return [];
    }
}