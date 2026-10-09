<?php

namespace Wexample\SymfonyGeo\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wexample\SymfonyGeo\Entity\AbstractAddress;
use Wexample\SymfonyGeo\Entity\AddressSnapshot;
use Wexample\SymfonyGeo\Entity\Country;

/**
 * An address written down once: what it read then, kept whatever becomes of
 * the address and of its country.
 */
class AddressSnapshotTest extends TestCase
{
    public function testItKeepsWhatTheAddressReadThen(): void
    {
        $country = (new Country())->setIsoAlpha2Code('FR')->setName('France');
        $address = (new class () extends AbstractAddress {
        })
            ->setFirstName('Ada')
            ->setLastName('Lovelace')
            ->setPostalAddress("12 rue des Lilas\nBâtiment B")
            ->setPostCode('75011')
            ->setCity('Paris')
            ->setCountry($country);

        $snapshot = AddressSnapshot::fromAddress($address);

        $address->setCity('Lyon');
        $country->setName('République française');

        $this->assertSame('Ada Lovelace, 12 rue des Lilas, Bâtiment B, 75011 Paris, France', (string) $snapshot);
        $this->assertSame('FR', $snapshot->getCountryCode());
        $this->assertSame(['12 rue des Lilas', 'Bâtiment B', '75011 Paris'], array_slice($snapshot->toLines(false), 1));
    }

    public function testAnEmptySnapshotSaysSo(): void
    {
        $this->assertTrue((new AddressSnapshot())->isEmpty());
    }
}
