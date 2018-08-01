<?php

namespace GeekLab\ArrayTranslation;

/**
 * Translate array <-> PHP internal session serialized data
 * Stolen from: https://github.com/psr7-sessions/session-encode-decode/
 *
 * Class PHP
 * @package GeekLab\ArrayTranslation
 */
class PHP implements TranslationInterface
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

        $encodedData = '';

        foreach ($arr as $key => $value)
        {
            $encodedData .= $key . '|' . serialize($value);
        }

        return $encodedData;
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

        preg_match_all('/(^|;|\})(\w+)\|/i', $str, $matches, PREG_OFFSET_CAPTURE);

        $decodedData = [];
        $lastOffset  = null;
        $currentKey  = '';

        foreach ($matches[2] as $value)
        {
            $offset = $value[1];

            if (null !== $lastOffset)
            {
                $valueText                = substr($str, $lastOffset, $offset - $lastOffset);
                $decodedData[$currentKey] = unserialize($valueText);
            }

            $currentKey = $value[0];
            $lastOffset = $offset + strlen($currentKey) + 1;
        }

        $valueText                = substr($str, $lastOffset);
        $decodedData[$currentKey] = unserialize($valueText);

        return $decodedData;
    }
}