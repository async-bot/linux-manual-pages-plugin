<?php declare(strict_types=1);

namespace AsyncBot\Plugin\LinuxManualPages;

use Amp\Http\Client\HttpClientBuilder;
use AsyncBot\Core\Http\Client;
use function Amp\Promise\wait;

require_once __DIR__ . '/vendor/autoload.php';

$plugin = new Plugin(new Client(HttpClientBuilder::buildDefault()));

var_dump(wait($plugin->search('locate')));
