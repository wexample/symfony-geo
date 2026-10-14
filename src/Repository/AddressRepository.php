<?php

namespace Wexample\SymfonyGeo\Repository;

use Wexample\SymfonyGeo\Entity\Address;
use Wexample\SymfonyGeo\Entity\Country;
use Wexample\SymfonyGeo\Entity\Traits\Manipulator\AddressEntityManipulatorTrait;
use Wexample\SymfonyGeo\Interface\PostalAddressInterface;
use Wexample\SymfonyHelpers\Repository\AbstractRepository;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address      saveNewAddress(?string $postalAddress = null, ?string $postCode = null, ?string $city = null, ?Country $country = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends AbstractRepository
{
    use AddressEntityManipulatorTrait;

    public function createNewAddress(
        ?string $postalAddress = null,
        ?string $postCode = null,
        ?string $city = null,
        ?Country $country = null,
    ): Address {
        $address = new Address();
        $address
            ->setPostalAddress($postalAddress)
            ->setPostCode($postCode)
            ->setCity($city)
            ->setCountry($country);

        return $address;
    }

    /**
     * Another address, located where `$source` is: a copy, not a second owner
     * of the same row, so moving one leaves the other where it was.
     */
    public function createNewAddressFromAddress(PostalAddressInterface $source): Address
    {
        return (new Address())->copyAddressFrom($source);
    }
}
