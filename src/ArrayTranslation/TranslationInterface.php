<?php
// Todo: Create a Interfaces\Convert pacakge.
namespace GeekLab\ArrayTranslation;

interface TranslationInterface
{
    public function encode(array $arr): string;

    public function decode(string $str): array;
}