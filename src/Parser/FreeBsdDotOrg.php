<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPages\Parser;

use AsyncBot\Plugin\LinuxManualPages\Exception\UnexpectedHtmlFormat;
use AsyncBot\Plugin\LinuxManualPages\ValueObject\ManualPage;

final class FreeBsdDotOrg
{
    public function parse(\DOMDocument $dom): ?ManualPage
    {
        $xpath = new \DOMXPath($dom);

        if (!$this->isCommandFound($xpath)) {
            return null;
        }

        return new ManualPage(
            $this->getName($xpath),
            $this->getShortDescription($xpath),
            $this->getLongDescription($xpath),
            $this->getSynopsis($xpath),
        );
    }

    private function isCommandFound(\DOMXPath $xpath): bool
    {
        return (bool) $xpath->evaluate('//a[@name="SYNOPSIS"]')->length;
    }

    private function getName(\DOMXPath $xpath): string
    {
        /** @var \DOMNodeList $nameNodes */
        $nameNodes = $xpath->evaluate('//a[@name="NAME"]/following-sibling::b/text()');

        if (!$nameNodes->length) {
            throw new UnexpectedHtmlFormat('name');
        }

        return ltrim($nameNodes->item(0)->textContent);
    }

    private function getShortDescription(\DOMXPath $xpath): string
    {
        /** @var \DOMNodeList $nameNodes */
        $descriptionNodes = $xpath->evaluate('//a[@name="NAME"]/following-sibling::b/following-sibling::text()');

        if (!$descriptionNodes->length) {
            throw new UnexpectedHtmlFormat('short description');
        }

        return $this->trimDescription($descriptionNodes->item(0)->textContent);
    }

    private function getLongDescription(\DOMXPath $xpath): string
    {
        /** @var \DOMNodeList $nameNodes */
        $descriptionNodes = $xpath->evaluate('//a[@name="DESCRIPTION"]');

        if (!$descriptionNodes->length) {
            throw new UnexpectedHtmlFormat('long description');
        }

        $currentNode = $descriptionNodes->item(0)->nextSibling;

        if ($currentNode === null) {
            throw new UnexpectedHtmlFormat('long description start node');
        }

        $description = '';

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
        /** @var \DOMNodeList $nameNodes */
        $synopsisNodes = $xpath->evaluate('//a[@name="SYNOPSIS"]/following-sibling::b');

        if (!$synopsisNodes->length) {
            throw new UnexpectedHtmlFormat('synopsis');
        }

        $currentNode = $synopsisNodes->item(0);

        if ($currentNode === null) {
            throw new UnexpectedHtmlFormat('synopsis start node');
        }

        $synopsis = '';

        while (!property_exists($currentNode, 'tagName') || $currentNode->tagName !== 'a') {
            $synopsis .= sprintf(' %s', trim($currentNode->textContent));

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
