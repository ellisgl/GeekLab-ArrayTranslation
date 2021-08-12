<?php

namespace GeekLab;

use \Exception;
use GeekLab\ArrayTranslation\TranslationInterface;

/**
 * Class ArrayTranslation - Factory
 *
 * @package geeklab\arraytranslation
 */
class ArrayTranslation
{
    public static function create(string $type = '', string $handler = '')
    {
        if (!empty($handler)) {
            // Make sure handler is accessible.
            if (!class_exists($handler)) {
                throw new Exception("Class '$handler' does not exist or is not accessible.");
            }

            $translator = new $handler();

            if (!$translator instanceof TranslationInterface) {
                throw new Exception(
                    "Class '$handler' is not an instance of GeekLab\ArrayTranslation\TranslationInterface."
                );
            }

            return $translator;
        }

        switch (strtolower($type)) {
            case 'php_serialize':
                return new ArrayTranslation\PHPSerialize();

            case 'php_binary':
                return new ArrayTranslation\PHPBinary();

            case 'php':
                return new ArrayTranslation\PHP();

            case 'json':
                return new ArrayTranslation\JSON();

            case 'xml':
                return new ArrayTranslation\XML();

            case 'yaml':
                return new ArrayTranslation\YAML();

            default:
                // Throw invalid 'data' error
                throw new Exception("Invalid data type: '$type' is not supported.");
        }
    }
}
