<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2025 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

namespace JmapClient\Tests\Integration;

use JmapClient\Authentication\Basic;
use JmapClient\Client;

class ClientFactory
{
    public static function instance(string $serviceName): Client
    {
        // Load the credentials from the JSON file
        $servicesFile = __DIR__ . '/../resources/services.json';
        $servicesData = file_get_contents($servicesFile);
        $servicesData = json_decode($servicesData, true);
        $service = $servicesData[$serviceName];

        $client = new Client();
        $client->configureTransportMode($service['protocol']);
        $client->setHost($service['host'] . ':' . $service['port']);
        $client->setAuthentication(new Basic(
            $service['identifier'],
            $service['secret']
        ));
        return $client;
    }
}
