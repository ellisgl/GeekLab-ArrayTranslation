<?php

namespace GeekLab;

use GeekLab\ArrayTranslation\TranslationInterface;

class ArrayTranslation
{
    public function create(string $type = '', string $handler = '')
    {
        if(!empty($handler))
        {
            // Make sure handler is accessible
            if(!class_exists($handler))
            {
                throw new \Exception('Class "' . $handler . '" does not exist or is not accessible.');
            }

            $translator = new $handler();

            if(!$translator instanceof TranslationInterface)
            {
                throw new \Exception('Class "' . $handler . '" is not an instance of GeekLab\ArrayTranslation\TranslationInterface.');
            }

            return $translator;
        }

        switch (strtolower($type))
        {
            case 'php_serialize':
                return new ArrayTranslation\PHPSerialize();
                break;

            case 'php_binary':
                return new ArrayTranslation\PHPBinary();
                break;

            case 'php':
                return new ArrayTranslation\PHP();
                break;

            case 'wddx':
                return new ArrayTranslation\WDDX();
                break;

            case 'igbinary':
                return new ArrayTranslation\igbinary();
                break;

            case 'json':
                return new ArrayTranslation\JSON();
                break;

            case 'xml':
                return new ArrayTranslation\XML();
                break;
            case 'yaml':
                return new ArrayTranslation\YAML();
                break;

            default:
                // Throw invalid 'data' error
                throw new \Exception('Invalid data type: "' . $type . '" is not supported.');
        }
    }
}