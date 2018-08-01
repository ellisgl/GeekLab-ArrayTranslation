<?php

namespace GeekLab\ArrayTranslation;

use DOMAttr;
use DOMText;
use DOMElement;
use DOMDocument;
use DOMCdataSection;
use DOMNamedNodeMap;
use DOMException;

class XMLArray
{
    private $document;

    public function __construct()
    {

    }

//    public function decode(string $xml): array
//    {
//        $this->document = new DOMDocument();
//        $this->document->loadXML($xml);
//
//        $decodedData = [];
//
//        if ($this->document->hasChildNodes())
//        {
//            $children = $this->document->childNodes;
//
//            foreach ($children as $child)
//            {
//                $decodedData[$child->nodeName] = $this->convertDomElement($child);
//            }
//        }
//
//        return $decodedData;
//    }

    public function decode(string $contents, $get_attributes = 1): array
    {
        if (!$contents)
        {
            return array();
        }

        if (!function_exists('xml_parser_create'))
        {
            //print "'xml_parser_create()' function not found!";
            return [];
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, $contents, $xml_values);
        xml_parser_free($parser);

        if (!$xml_values)
        {
            return [];
        }//Hmm...

        //Initializations
        $xml_array   = array();
        $parents     = array();
        $opened_tags = array();
        $arr         = array();

        $current = &$xml_array;

        //Go through the tags.
        foreach ($xml_values as $data)
        {
            unset($attributes, $value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = '';
            if ($get_attributes)
            {//The second argument of the function decides this.
                $result = array();
                if (isset($value))
                {
                    $result['value'] = $value;
                }

                //Set the attributes too.
                if (isset($attributes))
                {
                    foreach ($attributes as $attr => $val)
                    {
                        if ($get_attributes == 1)
                        {
                            $result['attr'][$attr] = $val;
                        } //Set all the attributes in a array called 'attr'
                        /**  :TODO: should we change the key name to '_attr'? Someone may use the tagname 'attr'. Same goes for 'value' too */
                    }
                }
            }
            elseif (isset($value))
            {
                $result = $value;
            }

            //See tag status and do the needed.
            if ($type == "open")
            {//The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;

                if (!is_array($current) or (!in_array($tag, array_keys($current))))
                { //Insert New tag
                    $current[$tag] = $result;
                    $current       = &$current[$tag];

                }
                else
                { //There was another element with the same tag name
                    if (isset($current[$tag][0]))
                    {
                        array_push($current[$tag], $result);
                    }
                    else
                    {
                        $current[$tag] = array($current[$tag], $result);
                    }
                    $last    = count($current[$tag]) - 1;
                    $current = &$current[$tag][$last];
                }

            }
            elseif ($type == "complete")
            { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag]))
                { //New Key
                    $current[$tag] = $result;

                }
                else
                { //If taken, put all things inside a list(array)
                    if ((is_array($current[$tag]) and $get_attributes == 0)//If it is already an array...
                        or (isset($current[$tag][0]) and is_array($current[$tag][0]) and $get_attributes == 1))
                    {
                        array_push($current[$tag], $result); // ...push the new element into that array.
                    }
                    else
                    { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                    }
                }

            }
            elseif ($type == 'close')
            { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return $xml_array;
    }

    protected function convertAttributes(DOMNamedNodeMap $nodeMap): ?array
    {
        if ($nodeMap->length === 0)
        {
            return null;
        }

        $result = [];

        /** @var DOMAttr $item */
        foreach ($nodeMap as $item)
        {
            $result[$item->name] = $item->value;
        }

        return ['_attributes' => $result];
    }

    protected function isHomogeneous(array $arr)
    {
        $firstValue = current($arr);

        foreach ($arr as $val)
        {
            if ($firstValue !== $val)
            {
                return false;
            }
        }

        return true;
    }

    protected function convertDomElement(DOMElement $element)
    {
        $sameNames = false;
        $result    = $this->convertAttributes($element->attributes);

        if ($element->childNodes->length > 1)
        {
            $childNodeNames = [];

            foreach ($element->childNodes as $key => $node)
            {
                $childNodeNames[] = $node->nodeName;
            }

            $sameNames = $this->isHomogeneous($childNodeNames);
        }

        foreach ($element->childNodes as $key => $node)
        {
            if ($node instanceof DOMCdataSection)
            {
                $result['_cdata'] = $node->data;
                continue;
            }

            if ($node instanceof DOMText)
            {
                $result = $node->textContent;
                continue;
            }

            if ($node instanceof DOMElement)
            {
                if ($sameNames)
                {
                    $result[$node->nodeName][$key] = $this->convertDomElement($node);
                }
                else
                {
                    if ('name' == $node->nodeName)
                    {
                        var_dump($result);
                    }
                    $result[$node->nodeName] = $this->convertDomElement($node);

                }
                continue;
            }
        }

        return $result;
    }
}