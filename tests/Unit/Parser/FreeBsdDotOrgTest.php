<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPagesTest\Unit\Parser;

use AsyncBot\Plugin\LinuxManualPages\Exception\UnexpectedHtmlFormat;
use AsyncBot\Plugin\LinuxManualPages\Parser\FreeBsdDotOrg;
use AsyncBot\Plugin\LinuxManualPages\ValueObject\ManualPage;
use PHPUnit\Framework\TestCase;
use function Room11\DOMUtils\domdocument_load_html;

class FreeBsdDotOrgTest extends TestCase
{
    private function getDomFromFakeResponse(string $filename): \DOMDocument
    {
        return domdocument_load_html(
            file_get_contents(TEST_DATA_DIR . '/ResponseHtml/FreeBsdDotOrg/' . $filename),
        );
    }

    public function testParseReturnsNullWhenCommandCouldNotBeFound(): void
    {
        $this->assertNull((new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('command-not-found.html'),
        ));
    }

    public function testParseReturnsManualPageWhenValid(): void
    {
        $manualPage = (new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('valid.html'),
        );

        $this->assertInstanceOf(ManualPage::class, $manualPage);
    }

    public function testParseReturnsCorrectName(): void
    {
        $manualPage = (new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('valid.html'),
        );

        $this->assertSame('locate.updatedb', $manualPage->getName());
    }

    public function testParseReturnsCorrectShortDescription(): void
    {
        $manualPage = (new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('valid.html'),
        );

        $this->assertSame('update locate database', $manualPage->getShortDescription());
    }

    public function testParseReturnsCorrectLongDescription(): void
    {
        $manualPage = (new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('valid.html'),
        );

        $this->assertSame(
            'The locate.updatedb utility updates the database used by locate(1) . It is typically run once a week by the /etc/periodic/weekly/310.locate script. The contents of the newly built database can be controlled by the /etc/locate.rc file.',
            $manualPage->getLongDescription(),
        );
    }

    public function testParseReturnsCorrectSynopsis(): void
    {
        $manualPage = (new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('valid.html'),
        );

        $this->assertSame('/usr/libexec/locate.updatedb', $manualPage->getSynopsis());
    }

    public function testParseThrowsOnMissingNameElement(): void
    {
        $this->expectException(UnexpectedHtmlFormat::class);
        $this->expectExceptionMessage('Could not find the "name" element in the document');

        (new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('missing-name-element.html'),
        );
    }

    public function testParseThrowsOnMissingLongDescriptionElement(): void
    {
        $this->expectException(UnexpectedHtmlFormat::class);
        $this->expectExceptionMessage('Could not find the "long description" element in the document');

        (new FreeBsdDotOrg())->parse(
            $this->getDomFromFakeResponse('missing-long-description-element.html'),
        );
    }
}
