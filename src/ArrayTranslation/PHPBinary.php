<?php

namespace GeekLab\ArrayTranslation;

/**
 * Translate array <-> PHP internal binary serialized data
 * Stolen from: https://github.com/wikimedia/php-session-serializer/blob/master/src/Wikimedia/PhpSessionSerializer.php
 *
 * Class PHPBinary
 * @package GeekLab\ArrayTranslation
 */
class PHPBinary implements TranslationInterface
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
            if (strcmp($key, intval($key)) === 0)
            {
                continue;
            }

            $l = strlen($key);

            if ($l > 127)
            {
                continue;
            }

            $v = serialize($value);

            if ($v === null)
            {
                return null;
            }

            $encodedData .= chr($l) . $key . $v;
        }

        return $encodedData;
    }

    /**
     * @param  string $str
     * @return array
     */
    public function decode(string $str): array
    {
        $decodedData = [];

        while ($str !== '' && $str !== false)
        {
            $l = ord($str[0]);

            if (strlen($str) < ($l & 127) + 1)
            {
                return [];
            }

            // "undefined" marker
            if ($l > 127)
            {
                $str = substr($str, ($l & 127) + 1);
                continue;
            }

            $key = substr($str, 1, $l);
            $str = substr($str, $l + 1);

            if (empty(str))
            {
                return [];
            }

            list($ok, $value) = $this->unserializeValue($str);

            if (!$ok)
            {
                return null;
            }

            $decodedData[$key] = $value;
        }

        return $decodedData;
    }

    /**
     * @param  $string
     * @return array
     */
    private function unserializeValue(&$string): array
    {
        $error = null;

        set_error_handler(function ($errNo, $errStr) use (&$error)
        {
            $error = $errStr;
            return true;
        });

        $unserialized = unserialize($string);

        restore_error_handler();

        if ($error !== null)
        {
            return [false, null];
        }

        $serialized = serialize($unserialized);
        $l          = strlen($serialized);

        if (substr($string, 0, $l) !== $serialized)
        {
            return [false, null];
        }

        $string = substr($string, $l);

        return [true, $unserialized];
    }
}