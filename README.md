# GeekLab-ArrayTranslation
[![CI](https://github.com/ellisgl/GeekLab-ArrayTranslation/actions/workflows/ci.yml/badge.svg)](https://github.com/ellisgl/GeekLab-ArrayTranslation/actions/workflows/ci.yml)
[![Packagist Version](https://img.shields.io/packagist/v/geeklab/arraytranslation)](https://packagist.org/packages/geeklab/arraytranslation)
[![PHP Version](https://img.shields.io/packagist/dependency-v/geeklab/arraytranslation/php)](https://packagist.org/packages/geeklab/arraytranslation)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen)](https://phpstan.org/)
[![License: BSD-3-Clause](https://img.shields.io/badge/License-BSD--3--Clause-blue.svg)](LICENSE.BSD)

Convert an array to another data format or convert a data format to an array.

## Supports:
* JSON
* XML
* PHP internal session serialized data
* PHP internal binary serialized data
* PHP Serialized() data
* YAML

## Todo:
* WDDX support
* igbinary support

## Installation:
composer require geeklab/arraytranslation 

## Usage:
    <?php
    require_once('../vendor/autoload.php');
    
    $at  =  \GeekLab\ArrayTranslation::create('yaml');
    $out = $at->encode(array('a' => 'x', 'b' => y', 'c' => 'z');

## API:
### \GeekLab\ArrayTranslation::create(string $type, string $handler): object

#### Description:
This is the factory to return the class for array<->data type translation.

`$type` can be set to xml, json, yaml, php, php_binary or php_binary for now.

`$handler` is for pointing to a customer handler. Must implement GeekLab\ArrayTranslation\TranslationInterface
#### Usage:
`$at = \GeekLab\ArrayTranslation::create('json');`

### \GeekLab\ArrayTranslation::encode(array $arr): string

### Description:
This method will convert an array to the type the object was created with.

### Usage:
`$x = $at->encode(array('a', 'b', 'c'));`

### \GeekLab\ArrayTranslation::encode(string $str): array

### Description:
This method will convert a string (data type) to an array.

### Usage:
`$y = $at->decode('["a","b","c"]');`