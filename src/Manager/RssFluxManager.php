<?php

namespace App\Manager;

/**
 * Class RssFluxManager
 * @package App\Manager
 */
class RssFluxManager
{
    /**
     * @return array
     */
    public function read()
    {
        $xml = $this->getXML();

        /** @var array $items */
        $items = $this->xmlToArray($xml);

        /** @var array $words */
        $words = $this->getAllWords($items);

        /** @var array $countWords */
        $countWords = $this->countWords($words);

        /** @var array $mostUsedWord */
        $mostUsedWord = $this->getMostUsedWords($countWords);

        return $mostUsedWord;
    }

    /**
     * @return \SimpleXMLElement
     */
    protected function getXML(): \SimpleXMLElement
    {
        $context = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
        $url = 'https://www.theregister.co.uk/software/headlines.atom';

        $xml = file_get_contents($url, false, $context);
        return simplexml_load_string($xml);
    }

    /**
     * @param $xmlObject
     * @param array $out
     * @return array
     */
    protected function xmlToArray($xmlObject, $out = array()): array
    {
        foreach ((array)$xmlObject as $index => $node)
            $out[$index] = (is_object($node) || is_array($node)) ? $this->xmlToArray($node) : $node;

        return $out;
    }

    /**
     * @param $items
     * @return array
     */
    protected function getAllWords(array $items): array
    {
        /** @var array $words */
        $words = [];

        foreach ($items['entry'] as $key => $item) {
            $summary = strip_tags($item['summary']);
            $summary = preg_replace("/[^a-zA-Z ]/", "", $summary);

            if (empty($summary)) {
                continue;
            }

            $words = array_merge(explode(' ', $summary), $words);
        }

        return $words;
    }

    /**
     * @param array $words
     * @return array
     */
    protected function countWords(array $words): array
    {
        $countWords = [];

        foreach ($words as $word) {
            $word = strtolower(trim($word));

            if (empty($word))
                continue;

            $countWords[$word] = empty($countWords[$word]) ? 1 : $countWords[$word]+1;
        }

        return $countWords;
    }

    /**
     * @param array $countWords
     * @return array
     */
    protected function getMostUsedWords(array $countWords): array
    {
        $json = __DIR__ . '/../../public/json/mostUsedWords.json';
        $string = file_get_contents($json);

        $mostUsedWords = json_decode($string, true);

        foreach ($mostUsedWords['words'] as $word) {;
            if (!empty($countWords[$word])) {
                unset($countWords[$word]);
            }
        }

        arsort($countWords);

        $result = array_slice($countWords, 0, 10);

        return $result;
    }
}