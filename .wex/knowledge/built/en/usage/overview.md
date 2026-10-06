`wexample/symfony-geo` is a Symfony bundle that ships `Continent` and `Country` Doctrine entities populated from the `rinvex/countries` dataset, each carrying ISO codes and a relationship to the currency entity from `wexample/symfony-money`. It is aimed at Symfony developers who need a ready-made geographic reference layer — with repositories, seed services, and API import endpoints — without writing the boilerplate themselves.

## Addresses

`PostalAddressInterface` is a postal address whose country is a relation to `Country`: no ISO code is stored outside the country table. Entities get the columns from `HasPostalAddressTrait`; `AbstractAddress` adds an addressee for address books (a concrete entity extends it, like `CartAddress`). `PostalAddressHelper::toLines()` gives the lines as printed on an envelope, the country by its name.
