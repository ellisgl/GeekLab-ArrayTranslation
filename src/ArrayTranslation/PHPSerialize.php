<?php

namespace GeekLab\ArrayTranslation;

/**
 * Translate array <-> PHP internal serialized data
 * Stolen from: https://github.com/psr7-sessions/session-encode-decode/
 *
 * Class PHPSerialize
 * @package GeekLab\ArrayTranslation
 */
class PHPSerialize implements TranslationInterface
{
    /**
     * @param array $arr
     *
     * @return string
     */
    public function encode(array $arr): string
    {
        if (empty($arr))
        {
            return '';
        }

        return serialize($arr);
    }

    /**
     * @param  string $str
     * @return array
     */
    public function decode(string $str): array
    {
        if (empty($str))
        {
            return [];
        }

        return unserialize($str);
    }
}
