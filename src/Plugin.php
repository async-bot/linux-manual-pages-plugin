<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPages;

use Amp\Promise;
use AsyncBot\Core\Http\Client;
use AsyncBot\Plugin\LinuxManualPages\Retriever\SearchOnFreeBsdDotOrg;

final class Plugin
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function search(string $keywords): Promise
    {
        return (new SearchOnFreeBsdDotOrg($this->httpClient))->retrieve($keywords);
    }
}
