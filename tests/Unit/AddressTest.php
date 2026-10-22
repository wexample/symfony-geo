<?php

namespace Wexample\SymfonyGeo\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Wexample\SymfonyGeo\Entity\AbstractAddress;
use Wexample\SymfonyGeo\Entity\Country;

class AddressTest extends TestCase
{
    private function address(): AbstractAddress
    {
        return new class () extends AbstractAddress {
        };
    }

    private function country(string $code, string $name): Country
    {
        return (new Country())->setIsoAlpha2Code($code)->setName($name);
    }

    public function testRendering(): void
    {
        $address = $this->address()
            ->setPostalAddress("12 rue des Lilas\nBâtiment B")
            ->setPostCode('75011')
            ->setCity('Paris')
            ->setCountry($this->country('FR', 'France'));

        $this->assertSame('12 rue des Lilas, Bâtiment B, 75011 Paris, France', (string) $address);

        $address->setCountry(null);
        $this->assertSame('12 rue des Lilas, Bâtiment B, 75011 Paris', $address->toStringInline());
        $this->assertFalse($this->address()->hasPostalAddress());
    }

    public function testCopyKeepsIdentity(): void
    {
        $belgium = $this->country('BE', 'Belgium');
        $source = $this->address()
            ->setFirstName('Ada')
            ->setLastName('Lovelace')
            ->setPostalAddress('1 Main Street')
            ->setCity('Bruxelles')
            ->setCountry($belgium);
        $copy = $this->address()->copyFrom($source);

        $this->assertSame('Ada Lovelace', $copy->getFullName());
        $this->assertSame($belgium, $copy->getCountry());
        $this->assertNotEquals((string) $source->getId(), (string) $copy->getId());
    }
}
