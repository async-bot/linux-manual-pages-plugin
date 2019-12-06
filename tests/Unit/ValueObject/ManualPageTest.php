<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPagesTest\Unit\ValueObject;

use AsyncBot\Plugin\LinuxManualPages\ValueObject\ManualPage;
use PHPUnit\Framework\TestCase;

class ManualPageTest extends TestCase
{
    private ManualPage $manualPage;

    protected function setUp(): void
    {
        $this->manualPage = new ManualPage('command', 'The short description', 'The long description', 'The synopsis');
    }

    public function testGetName(): void
    {
        $this->assertSame('command', $this->manualPage->getName());
    }

    public function testGetShortDescription(): void
    {
        $this->assertSame('The short description', $this->manualPage->getShortDescription());
    }

    public function testGetLongDescription(): void
    {
        $this->assertSame('The long description', $this->manualPage->getLongDescription());
    }

    public function testGetSynopsis(): void
    {
        $this->assertSame('The synopsis', $this->manualPage->getSynopsis());
    }
}
