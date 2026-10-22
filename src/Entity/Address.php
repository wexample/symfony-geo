<?php

namespace Wexample\SymfonyGeo\Entity;

use Doctrine\ORM\Mapping as ORM;
use Wexample\SymfonyGeo\Repository\AddressRepository;

/**
 * An address of the `address` table, pointed at by whoever has one — an
 * organization, a user. It is one row read and changed in one place: a move
 * is written once and every owner of that address reads the new one.
 *
 * What must not move with it is not pointed at but frozen: an invoice, an
 * order, a shipment keep an AddressSnapshot of the address as it read the day
 * they were issued.
 *
 * An owner needing columns of its own on the address itself (an addressee per
 * order, a label) extends AbstractAddress into its own table instead.
 */
#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ORM\Table(name: 'address')]
class Address extends AbstractAddress
{
}
