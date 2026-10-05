`wexample/symfony-geo` is a Symfony bundle that ships `Continent` and `Country` Doctrine entities populated from the `rinvex/countries` dataset, each carrying ISO codes and a relationship to the currency entity from `wexample/symfony-money`. It is aimed at Symfony developers who need a ready-made geographic reference layer — with repositories, seed services, and API import endpoints — without writing the boilerplate themselves.

## Addresses

`PostalAddressInterface` is a postal address with an ISO alpha-2 country code. Entities get the columns from `HasPostalAddressTrait`; `AbstractAddress` adds an addressee for user and organization address books; `PostalAddress` is the immutable copy stored as JSON in snapshots (a paid cart's billing address, an emitted invoice's customer). `PostalAddressHelper::toLines()` gives the lines as printed on an envelope.
