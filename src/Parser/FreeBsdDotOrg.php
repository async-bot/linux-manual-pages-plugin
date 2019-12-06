<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPages\Parser;

final class FreeBsdDotOrg
{
    public function parse(\DOMDocument $dom)
    {
        $xpath = new \DOMXPath($dom);

        if (!$this->isCommandFound($xpath)) {
            return null;
        }

        return [
            'name'        => $this->getName($xpath),
            'shortDescription' => $this->getShortDescription($xpath),
            'longDescription'  => $this->getLongDescription($xpath),
            'synopsis'    => $this->getSynopsis($xpath),
        ];
    }

    private function isCommandFound(\DOMXPath $xpath): bool
    {
        return (bool) $xpath->evaluate('//a[@name="SYNOPSIS"]')->length;
    }

    private function getName(\DOMXPath $xpath): string
    {
        return ltrim($xpath->evaluate('//a[@name="NAME"]/following-sibling::b/text()')->item(0)->textContent);
    }

    private function getShortDescription(\DOMXPath $xpath): string
    {
        $description = $xpath->evaluate('//a[@name="NAME"]/following-sibling::b/following-sibling::text()')->item(0)->textContent;

        return $this->trimDescription($description);
    }

    private function getLongDescription(\DOMXPath $xpath): string
    {
        $description = '';

        $currentNode = $xpath->evaluate('//a[@name="DESCRIPTION"]')->item(0)->nextSibling;

        while ($this->isPartOfLongDescription($currentNode)) {
            $description .= sprintf(' %s', trim($currentNode->textContent));

            $currentNode = $currentNode->nextSibling;
        }

        return $this->trimDescription($description);
    }

    private function isPartOfLongDescription(\DOMNode $currentNode): bool
    {
        if ($currentNode instanceof \DOMText) {
            return true;
        }

        if ($currentNode->tagName !== 'a') {
            return true;
        }

        return $currentNode->getAttribute('name') === '';
    }

    private function getSynopsis(\DOMXPath $xpath): string
    {
        $synopsis = '';

        $currentNode = $xpath->evaluate("//a[@name='SYNOPSIS']/following-sibling::b")->item(0);

        while (!property_exists($currentNode, 'tagName') || $currentNode->tagName !== 'a') {
            $synopsis .= " " . trim($currentNode->textContent);
            $currentNode = $currentNode->nextSibling;
        }

        return $this->trimDescription($synopsis);
    }

    private function trimDescription(string $description): string
    {
        $description = str_replace(["\r\n", "\r", "\n"], ' ', $description);
        $description = trim($description, ' -');
        $description = preg_replace('/\s+/', ' ', $description);

        return $description;
    }
}
