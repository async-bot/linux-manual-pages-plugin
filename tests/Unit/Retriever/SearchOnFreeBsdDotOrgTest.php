<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPagesTest\Unit\Retriever;

use Amp\Http\Client\HttpClientBuilder;
use AsyncBot\Core\Http\Client;
use AsyncBot\Plugin\LinuxManualPages\Retriever\SearchOnFreeBsdDotOrg;
use AsyncBot\Plugin\LinuxManualPages\ValueObject\ManualPage;
use AsyncBot\Plugin\LinuxManualPagesTest\Fakes\HttpClient\MockResponseInterceptor;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class SearchOnFreeBsdDotOrgTest extends TestCase
{
    public function testRetrieveReturnsManualPage(): void
    {
        $httpClient = new Client(
            (new HttpClientBuilder())->intercept(
                new MockResponseInterceptor(file_get_contents(TEST_DATA_DIR . '/ResponseHtml/FreeBsdDotOrg/valid.html')),
            )->build(),
        );

        $manualPage = wait((new SearchOnFreeBsdDotOrg($httpClient))->retrieve('updatedb'));

        $this->assertInstanceOf(ManualPage::class, $manualPage);
    }
}
