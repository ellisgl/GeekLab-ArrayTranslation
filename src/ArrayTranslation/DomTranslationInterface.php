<?php

namespace GeekLab\ArrayTranslation;

use \DOMDocument;

interface DomTranslationInterface
{
    public function encode(
        array $arr,
        $rootElement = '',
        bool $replaceSpacesByUnderScoresInKeyNames = true,
        $xmlEncoding = null,
        string $xmlVersion = '1.0'
    ): string;

    public function convertToDom(
        array $arr,
        $rootElement = '',
        bool $replaceSpacesByUnderScoresInKeyNames = true,
        $xmlEncoding = null,
        string $xmlVersion = '1.0'
    );

    public function toDom(): DOMDocument;

    public function decode(string $str): array;
}
