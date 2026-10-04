<?php

namespace Wexample\SymfonyGeo\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wexample\SymfonyGeo\Class\PostalAddress;
use Wexample\SymfonyGeo\Entity\AbstractAddress;

class AddressTest extends TestCase
{
    private function address(): AbstractAddress
    {
        return new class() extends AbstractAddress {
        };
    }

    public function testRendering(): void
    {
        $address = $this->address()
            ->setPostalAddress("12 rue des Lilas\nBâtiment B")
            ->setPostCode('75011')
            ->setCity('Paris')
            ->setCountryCode('fr');

        $this->assertSame('FR', $address->getCountryCode());
        $this->assertSame('12 rue des Lilas, Bâtiment B, 75011 Paris, FR', (string) $address);

        $address->setCountryCode(null);
        $this->assertSame('12 rue des Lilas, Bâtiment B, 75011 Paris', $address->toStringInline());
        $this->assertFalse($this->address()->hasPostalAddress());
    }

    public function testCopyKeepsIdentity(): void
    {
        $source = $this->address()
            ->setFirstName('Ada')
            ->setLastName('Lovelace')
            ->setPostalAddress('1 Main Street')
            ->setCity('Bruxelles')
            ->setCountryCode('BE');
        $copy = $this->address()->copyFrom($source);

        $this->assertSame('Ada Lovelace', $copy->getFullName());
        $this->assertSame('Bruxelles', $copy->getCity());
        $this->assertNotEquals((string) $source->getId(), (string) $copy->getId());
    }

    public function testSnapshotRoundTrip(): void
    {
        $address = $this->address()->setPostalAddress('Rue Neuve 1')->setPostCode('1000')->setCity('Bruxelles')->setCountryCode('BE');
        $snapshot = PostalAddress::fromAddress($address);

        $this->assertEquals($snapshot, PostalAddress::fromArray(json_decode(json_encode($snapshot->toArray()), true)));
        $this->assertSame('BE', $snapshot->getCountryCode());
    }
}
