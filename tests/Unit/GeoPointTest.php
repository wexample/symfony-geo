<?php

namespace Wexample\SymfonyGeo\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Wexample\SymfonyGeo\Class\GeoPoint;
use Wexample\SymfonyGeo\Entity\AbstractAddress;
use Wexample\SymfonyGeo\Interface\GeocoderInterface;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;
use Wexample\SymfonyGeo\Service\GeocodingService;

class GeoPointTest extends TestCase
{
    private function address(): AbstractAddress
    {
        return new class() extends AbstractAddress {
        };
    }

    private function geocoder(?GeoPoint $answer): GeocoderInterface
    {
        return new class($answer) implements GeocoderInterface {
            public int $calls = 0;

            public function __construct(private readonly ?GeoPoint $answer)
            {
            }

            public function geocode(PostalAddressInterface $address): ?GeoPoint
            {
                ++$this->calls;

                return $this->answer;
            }
        };
    }

    public function testDistance(): void
    {
        $brussels = new GeoPoint(50.8467, 4.3525);
        $paris = new GeoPoint(48.8566, 2.3522);

        // Grand-Place to Hôtel de Ville, about 264 km as the crow flies.
        $this->assertEqualsWithDelta(264_000, $brussels->distanceTo($paris), 2_000);
        $this->assertSame(0.0, $paris->distanceTo($paris));
        $this->assertSame([4.3525, 50.8467], $brussels->toLngLat());
    }

    public function testRejectsOutOfRange(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new GeoPoint(91, 0);
    }

    public function testAddressHoldsPointWhole(): void
    {
        $address = $this->address();
        $this->assertNull($address->getGeoPoint());

        $address->setGeoPoint(new GeoPoint(50.8467, 4.3525));
        $this->assertEquals(new GeoPoint(50.8467, 4.3525), $address->getGeoPoint());

        $copy = $this->address()->copyFrom($address);
        $this->assertEquals($address->getGeoPoint(), $copy->getGeoPoint());

        $address->setGeoPoint(null);
        $this->assertFalse($address->isGeoLocated());
    }

    public function testLocate(): void
    {
        $point = new GeoPoint(50.8467, 4.3525);
        $address = $this->address()->setCity('Bruxelles');

        $this->assertFalse((new GeocodingService())->locate($address));

        $geocoder = $this->geocoder($point);
        $service = new GeocodingService($geocoder);
        $this->assertTrue($service->locate($address));
        $this->assertEquals($point, $address->getGeoPoint());

        // Located already: the remote is not asked again unless forced.
        $service->locate($address);
        $this->assertSame(1, $geocoder->calls);

        // An unknown address keeps the point it had.
        $this->assertTrue((new GeocodingService($this->geocoder(null)))->locate($address, true));
        $this->assertEquals($point, $address->getGeoPoint());
    }
}
