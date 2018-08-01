<?php

namespace GeekLab\ArrayTranslation;

/**
 * Translate array <-> JSON
 *
 * Class JSON
 * @package GeekLab\ArrayTranslation
 */
class JSON implements TranslationInterface
{
    /**
     * @param  array $arr
     * @return string
     */
    public function encode(array $arr): string
    {
        if (empty($arr))
        {
            return '[]';
        }

        return json_encode($arr);
    }

    /**
     * @param  string $str
     * @return array
     */
    public function decode(string $str): array
    {
        if (empty($str) || '[]' === $str || '{}' === $str)
        {
            return [];
        }

        return json_decode($str, TRUE);
    }
}