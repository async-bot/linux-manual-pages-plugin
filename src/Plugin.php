<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPages;

use Amp\Promise;
use AsyncBot\Core\Http\Client;
use AsyncBot\Plugin\LinuxManualPages\Retriever\SearchOnFreeBsdDotOrg;
use AsyncBot\Plugin\LinuxManualPages\ValueObject\ManualPage;

final class Plugin
{
    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return Promise<ManualPage|null>
     */
    public function search(string $keywords): Promise
    {
        return (new SearchOnFreeBsdDotOrg($this->httpClient))->retrieve($keywords);
    }
}
