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
    }

    private function isCommandFound(\DOMXPath $xpath): bool
    {
        return (bool) $xpath->evaluate("//a[@name='SYNOPSIS']")->length;
    }
}
