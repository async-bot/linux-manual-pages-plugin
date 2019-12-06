<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPages\Retriever;

use Amp\Promise;
use AsyncBot\Core\Http\Client;
use function Amp\call;

final class SearchOnFreeBsdDotOrg
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function retrieve(string $command): Promise
    {
        return call(function () use ($command) {
            $dom = yield $this->httpClient->requestHtml(
                sprintf('https://www.freebsd.org/cgi/man.cgi?query=%s', urlencode($command)),
            );
        });
    }
}
