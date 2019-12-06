<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPages\ValueObject;

final class ManualPage
{
    private string $name;

    private string $shortDescription;

    private string $longDescription;

    private string $synopsis;

    public function __construct(string $name, string $shortDescription, string $longDescription, string $synopsis)
    {
        $this->name             = $name;
        $this->shortDescription = $shortDescription;
        $this->longDescription  = $longDescription;
        $this->synopsis         = $synopsis;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getShortDescription(): string
    {
        return $this->shortDescription;
    }

    public function getLongDescription(): string
    {
        return $this->longDescription;
    }

    public function getSynopsis(): string
    {
        return $this->synopsis;
    }
}
