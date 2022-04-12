<a href="https://skrepr.com/">
  <p align="center">
    <img width="200" height="100" src="https://cdn.skrepr.com/logo/skrepr_liggend.svg" alt="skrepr_logo" />
  </p>
</a>
<h1 align="center">Skrepr Teams Connector Bundle</h1>
<div align="center">
  <a href="https://github.com/skrepr/teams-connector-bundle/releases"><img src="https://img.shields.io/github/release/skrepr/teams-connector-bundle.svg" alt="Releases"/></a><a> </a>
  <a href="https://github.com/skrepr/teams-connector-bundle/blob/main/LICENSE"><img src="https://img.shields.io/github/license/skrepr/teams-connector-bundle.svg" alt="LICENSE"/></a><a> </a>
  <a href="https://github.com/skrepr/teams-connector-bundle/issues"><img src="https://img.shields.io/github/issues/skrepr/teams-connector-bundle.svg" alt="Issues"/></a><a> </a>
  <a href="https://github.com/skrepr/teams-connector-bundle/stars"><img src="https://img.shields.io/github/stars/skrepr/teams-connector-bundle.svg" alt="Stars"/></a><a> </a>
</div>

Symfony bundle integration of the [skrepr/teams-connector](https://github.com/skrepr/teams-connector) library.

## Documentation

All the how to manipulate the Teams client is on the [skrepr/teams documentation](https://github.com/skrepr/teams-connector#create-a-simple-card).

## Prerequisites

This version of the project requires:
* PHP 7.4+
* Symfony 4.4/5.4/6.0+ (any stable or lts version of Symfony)

## Installation

You can install the library through composer:

``` bash
$ composer require skrepr/teams-connector-bundle
```

The bundle should be enabled by syfmony/flex but if not:

``` php
// config/bundles.php

<?php

return [
    Skrepr\TeamsConnectorBundle\SkreprTeamsConnectorBundle::class => ['dev' => true, 'test' => true],
];

```

You can get an error message because the configuration isn't done yet.

## Configuration

To configure the bundle you only need to specify your Teams endpoint:

```yaml
skrepr_teams_connector:
    endpoint: 'https://...'
```

It is the easiest to create this in a new file `config/packages/teams-connector.yaml`.

## Usage
The Teams client instance can be retrieved from the `skrepr_teams_connector.client` service.

Here is an example:

```php
declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Client\ClientExceptionInterface;
use Skrepr\TeamsConnector\Card;
use Skrepr\TeamsConnector\Client;
use Symfony\Component\HttpFoundation\Response;

class TestController
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function index(): Response
    {
        $card = new Card('Hello teams!');

        try {
            $this->client->send($card);
        } catch (ClientExceptionInterface $e) {
            return new Response($e->getMessage());
        }

        return new Response('Card has been send');
    }
}
```

All the how to manipulate the Teams connector client is on  [skrepr/teams-connector](https://github.com/skrepr/teams-connector).


## Test
To test this module you can use our docker test script which will execute phpunit on several PHP versions.
You must have docker installed to run this script.

```shell
./phpunit-in-docker.sh
```
