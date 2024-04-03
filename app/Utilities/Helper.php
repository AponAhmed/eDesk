<?php

namespace App\Utilities;

use DOMDocument;
use DOMXPath;
use League\HTMLToMarkdown\HtmlConverter;

class Helper
{


    public static function htmlToMarkdown($html)
    {
        // Load the HTML string into a DOMDocument
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // Suppress errors
        $dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        libxml_use_internal_errors(false); // Re-enable errors

        // Remove style attributes from all elements
        $xpath = new DOMXPath($dom);
        $elements = $xpath->query('//*[@style]');
        foreach ($elements as $element) {
            $element->removeAttribute('style');
        }

        // Replace all div tags with their inner HTML content
        $divs = $xpath->query('//div');
        foreach ($divs as $div) {
            $replacement = $dom->createDocumentFragment();
            while ($div->childNodes->length > 0) {
                $replacement->appendChild($div->childNodes->item(0));
            }
            $div->parentNode->replaceChild($replacement, $div);
        }

        // Convert modified HTML to Markdown
        $converter = new HtmlConverter();
        return $converter->convert($dom->saveHTML());
    }
}
