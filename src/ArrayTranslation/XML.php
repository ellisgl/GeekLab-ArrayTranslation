<?php

namespace GeekLab\ArrayTranslation;

use DOMElement;
use DOMDocument;
use DOMException;
use GeekLab\ArrayTranslation;

/**
 * Translate array <-> XML
 * Stolen from:
 *   https://github.com/spatie/array-to-xml
 *   https://github.com/vyuldashev/xml-to-array
 *
 * Class XML
 *
 * @package GeekLab\ArrayTranslation
 */
class XML implements DomTranslationInterface
{
    /**
     * The root DOM Document.
     *
     * @var DOMDocument
     */
    private $document;
    /**
     * Set to enable replacing space with underscore.
     *
     * @var bool
     */
    private $replaceSpacesByUnderScoresInKeyNames = true;


    /**
     * @var \GeekLab\ToolBox\Arrays;
     */
    private $Arrays;

    /**
     * Converts an array to DOMDocument
     *
     * @param array  $array
     * @param string $rootElement
     * @param bool   $replaceSpacesByUnderScoresInKeyNames
     * @param null   $xmlEncoding
     * @param string $xmlVersion
     *
     * @throws DOMException
     */
    public function convertToDom(
        array $array,
        $rootElement = '',
        $replaceSpacesByUnderScoresInKeyNames = true,
        $xmlEncoding = null,
        $xmlVersion = '1.0'
    ) {
        $this->Arrays                               = new \GeekLab\ToolBox\Arrays();
        $this->document                             = new DOMDocument($xmlVersion, $xmlEncoding);
        $this->replaceSpacesByUnderScoresInKeyNames = $replaceSpacesByUnderScoresInKeyNames;

        if ($this->isArrayAllKeySequential($array) && !empty($array)) {
            //throw new DOMException('Invalid Character Error');

            // Nope, we will rename the key some something that should be "unique".
            foreach ($array as $key => $value) {
                $array = $this->Arrays->renameKey($array, $key, '_int_' . $key);
            }
        }

        $root = $this->createRootElement($rootElement);

        $this->document->appendChild($root);
        $this->convertElement($root, $array);
    }

    /**
     * @param array   $arr
     * @param         $rootElement
     * @param bool    $replaceSpacesByUnderScoresInKeyNames
     * @param null    $xmlEncoding
     * @param string  $xmlVersion
     *
     * @return string
     * @throws DOMException
     */
    public function encode(
        array $arr,
        $rootElement = '',
        bool $replaceSpacesByUnderScoresInKeyNames = true,
        $xmlEncoding = null,
        string $xmlVersion = '1.0'
    ): string {
        $this->convertToDom($arr, $rootElement, $replaceSpacesByUnderScoresInKeyNames, $xmlEncoding, $xmlVersion);
        return $this->toXML();
    }

    /**
     * @param string $str
     * @param string $xmlType "simplexml"|"domdocument"
     *
     * @return array
     * @throws \Exception
     */
    public function decode(string $str, string $xmlType = 'DOMDocument'): array
    {
        //$arr = new XMLArray();
        //return $arr->decode($str);
        $array = (array)json_decode(json_encode(simplexml_load_string($str)), true);

        // Rename keys
        if ('SimpleXML' == $xmlType) {
            return $array;
        } elseif ('DOMDocument' == $xmlType) {
            // Need convert the '@attributes' to '_attributes'
            $this->Arrays->explore($array, function (&$value, $key)
            {
                if ('@attributes' === $key) {
                    $this->Arrays->renameKey($value, $key, '_attributes');
                }
            });
            return $array;
        } else {
            throw new \Exception('Invalid data XML type: "' . $xmlType . '" is not supported.');
        }
    }

    /**
     * Return as XML.
     *
     * @return string
     */
    public function toXml(): string
    {
        return $this->document->saveXML();
    }

    /**
     * Return as DOM object.
     *
     * @return DOMDocument
     */
    public function toDom(): DOMDocument
    {
        return $this->document;
    }

    /**
     * Parse individual element.
     *
     * @param DOMElement      $element
     * @param string|string[] $value
     */
    private function convertElement(DOMElement $element, $value): void
    {
        $sequential = $this->isArrayAllKeySequential($value);

        if (!is_array($value)) {
            $element->nodeValue = htmlspecialchars($value);
            return;
        }

        foreach ($value as $key => $data) {
            if (!$sequential) {
                if (($key === '_attributes') || ($key === '@attributes')) {
                    $this->addAttributes($element, $data);
                } elseif ((($key === '_value') || ($key === '@value')) && is_string($data)) {
                    $element->nodeValue = htmlspecialchars($data);
                } elseif ((($key === '_cdata') || ($key === '@cdata')) && is_string($data)) {
                    $element->appendChild($this->document->createCDATASection($data));
                } else {
                    $this->addNode($element, $key, $data);
                }
            } elseif (is_array($data)) {
                $this->addCollectionNode($element, $data);
            } else {
                $this->addSequentialNode($element, $data);
            }
        }
    }

    /**
     * Add node.
     *
     * @param DOMElement      $element
     * @param string          $key
     * @param string|string[] $value
     */
    private function addNode(DOMElement $element, $key, $value): void
    {
        if ($this->replaceSpacesByUnderScoresInKeyNames) {
            $key = str_replace(' ', '_', $key);
        }

        if (is_int($key)) {
            $key = '_int_' . $key;
        } elseif (is_numeric(substr($key, 0, 1))) {
            $key = '_' . $key;
        }

        $child = $this->document->createElement($key);

        $element->appendChild($child);
        $this->convertElement($child, $value);
    }

    /**
     * Add collection node.
     *
     * @param DOMElement      $element
     * @param string|string[] $value
     *
     * @internal param string $key
     */
    private function addCollectionNode(DOMElement $element, $value): void
    {
        if ($element->childNodes->length === 0 && $element->attributes->length === 0) {
            $this->convertElement($element, $value);
            return;
        }

        $child = new DOMElement($element->tagName);

        $element->parentNode->appendChild($child);
        $this->convertElement($child, $value);
    }

    /**
     * Add sequential node.
     *
     * @param DOMElement      $element
     * @param string|string[] $value
     *
     * @internal param string $key
     */
    private function addSequentialNode(DOMElement $element, $value): void
    {
        if (empty($element->nodeValue)) {
            $element->nodeValue = htmlspecialchars($value);
            return;
        }

        $child            = new DOMElement($element->tagName);
        $child->nodeValue = htmlspecialchars($value);

        $element->parentNode->appendChild($child);
    }

    /**
     * Check if array are all sequential.
     *
     * @param array|string $value
     *
     * @return bool
     */
    private function isArrayAllKeySequential($value): bool
    {
        if (!is_array($value)) {
            return false;
        }

        if (count($value) <= 0) {
            return true;
        }

        return [true] === array_unique(array_map('is_int', array_keys($value)));
    }

    /**
     * Add attributes.
     *
     * @param DOMElement $element
     * @param string[]   $data
     */
    private function addAttributes($element, $data): void
    {
        foreach ($data as $attrKey => $attrVal) {
            $element->setAttribute($attrKey, $attrVal);
        }
    }

    /**
     * Create the root element.
     *
     * @param string|array $rootElement
     *
     * @return DOMElement
     */
    private function createRootElement($rootElement): DOMElement
    {
        if (is_string($rootElement)) {
            $rootElementName = $rootElement ?: 'root';
            return $this->document->createElement($rootElementName);
        }

        $rootElementName = $rootElement['rootElementName'] ?? 'root';
        $element         = $this->document->createElement($rootElementName);

        foreach ($rootElement as $key => $value) {
            if ($key !== '_attributes' && $key !== '@attributes') {
                continue;
            }

            $this->addAttributes($element, $rootElement[$key]);
        }

        return $element;
    }
}
