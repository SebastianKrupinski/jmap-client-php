<?php

declare(strict_types=1);

/**
 * SPDX-FileCopyrightText: 2026 Sebastian Krupinski <krupinski01@gmail.com>
 * SPDX-License-Identifier: AGPL-3.0-only
 */

namespace JmapClient\Enums\Contacts;

/**
 * Address component kinds (RFC 9553 Section 2.5.1), servers may use other values
 */
enum ContactAddressComponentKind: string
{
    case Room = 'room';
    case Apartment = 'apartment';
    case Floor = 'floor';
    case Building = 'building';
    case Number = 'number';
    case Name = 'name';
    case Block = 'block';
    case Subdistrict = 'subdistrict';
    case District = 'district';
    case Locality = 'locality';
    case Region = 'region';
    case Postcode = 'postcode';
    case Country = 'country';
    case Direction = 'direction';
    case Landmark = 'landmark';
    case PostOfficeBox = 'postOfficeBox';
    case Separator = 'separator';
}
