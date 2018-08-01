<?php

namespace GeekLab\ArrayTranslation;

/**
 * Translate array <-> YAML
 *
 * Class YAML
 * @package GeekLab\ArrayTranslation
 */
class YAML implements TranslationInterface
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

        return yaml_emit($arr);
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

        return yaml_parse($str);
    }
}