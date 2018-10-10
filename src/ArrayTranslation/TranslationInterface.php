<?php
// Todo: Create a Interfaces\Convert package.
namespace GeekLab\ArrayTranslation;

interface TranslationInterface
{
    public function encode(array $arr): string;

    public function decode(string $str): array;
}