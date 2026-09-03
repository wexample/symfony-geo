# symfony_geo

Version: 3.0.1

`wexample/symfony-geo` is a Symfony bundle that ships `Continent` and `Country` Doctrine entities populated from the `rinvex/countries` dataset, each carrying ISO codes and a relationship to the currency entity from `wexample/symfony-money`. It is aimed at Symfony developers who need a ready-made geographic reference layer — with repositories, seed services, and API import endpoints — without writing the boilerplate themselves.

## Table of Contents

- [Architecture](#architecture)
- [Integration in the Suite](#integration-in-the-suite)
- [Dependencies](#dependencies)
- [Versioning & Compatibility Policy](#versioning--compatibility-policy)
- [License](#license)
- [About us](#about-us)
- [Migration Notes](#migration-notes)

## Architecture

The bundle follows a strict layered stack: static data → entity → repository → service → controller. Each layer has one job; nothing skips a layer.

### Bundle bootstrap

src/WexampleSymfonyGeoBundle.php extends `AbstractBundle` from `wexample/symfony-helpers`. src/DependencyInjection/WexampleSymfonyGeoExtension.php loads the two config files at container build time via `$this->loadConfig(__DIR__, $container)`.

src/Resources/config/services.yaml registers everything under `Wexample\SymfonyGeo\` in the `Controller`, `Repository`, and `Service` directories with `autowire: true` and `autoconfigure: true`. src/Resources/config/routes.yaml imports controller routes by PHP attributes.

### Layers

#### Static data

src/Data/ContinentData.php is the only hand-maintained geographic dataset in the bundle. It defines seven continents as constants (`CODE_AF` … `CODE_SA`) and returns them through `getAll()` as arrays carrying `isoAlpha2Code` and `name`. Country data is not stored here; `CountryService` pulls it at runtime from the `rinvex/countries` package via `countries()`.

#### Entities

src/Entity/Continent.php maps to the `continent` table and carries:
- `isoAlpha2Code` — two-character ISO code, unique
- `name` via `HasNameTrait`
- `secureId` via `HasSecureIdTrait`

src/Entity/Country.php maps to the `country` table and extends the continent shape with:
- `isoAlpha2Code` (2 chars), `isoAlpha3Code` (3 chars), `isoNumericCode` (3 chars) — all unique
- `nativeName` — nullable string
- `continent` — nullable `ManyToOne` to `Continent`
- `currency` — nullable `ManyToOne` to `Currency` from `wexample/symfony-money`

#### Repositories

src/Repository/ContinentRepository.php and src/Repository/CountryRepository.php both extend `AbstractRepository` from `wexample/symfony-helpers` and pull in their respective manipulator traits.

src/Entity/Traits/Manipulator/ContinentEntityManipulatorTrait.php and src/Entity/Traits/Manipulator/CountryEntityManipulatorTrait.php each implement `getEntityClassName()` and use `EntityManipulatorTrait` from `wexample/symfony-helpers`, which is what makes the `saveNew*` magic methods in the repository docblocks work.

Each repository exposes `findByIsoAlpha2Code()` for lookups and a typed `createNew*()` factory that sets all fields and generates a secure ID before returning the unsaved entity.

#### Services

src/Service/ContinentService.php owns the continent seed loop. It calls `ContinentData::getAll()`, then for each entry either updates the existing row (found by `isoAlpha2Code`) or delegates to `ContinentRepository::saveNewContinent()`.

src/Service/CountryService.php owns the country seed loop. It calls the `countries()` helper from `rinvex/countries`, resolves the matching `Continent` via `ContinentRepository::findByIsoAlpha2Code()` and the matching `Currency` via `CurrencyRepository::findByCode()` (from `wexample/symfony-money`), then either updates the existing row or delegates to `CountryRepository::saveNewCountry()`. Country names are normalised to lowercase with spaces, hyphens, and apostrophes replaced by underscores.

#### Controllers

src/Controller/ContinentController.php exposes `POST _geo/continent/import` and src/Controller/CountryController.php exposes `POST _geo/country/import`. Both extend `AbstractApiController` from `wexample/symfony-api`, accept no request body, delegate entirely to the corresponding service's `seed()` method, and return `self::apiResponseSuccess()` wrapped in an `ApiResponse`.

### Call path for a seed import

```
POST _geo/country/import
  → CountryController::import()
    → CountryService::seed()
      → countries()               # rinvex/countries dataset
      → ContinentRepository::findByIsoAlpha2Code()
      → CurrencyRepository::findByCode()
      → CountryRepository::findByIsoAlpha2Code()
        → (found)  CountryRepository::save()
        → (absent) CountryRepository::saveNewCountry()
  ← ApiResponse (success)
```

Continents follow the same path with `ContinentData::getAll()` substituted for `countries()` and no external repository lookups.

### External dependencies

| Package | What this bundle uses |
|---|---|
| `wexample/symfony-helpers` | `AbstractBundle`, `AbstractWexampleSymfonyExtension`, `AbstractRepository`, `EntityManipulatorTrait`, entity traits |
| `wexample/symfony-api` | `AbstractApiController`, `ApiResponse` |
| `wexample/symfony-money` | `Currency` entity, `CurrencyRepository` |
| `rinvex/countries` | `countries()` helper and country data objects |

## Integration in the Suite

This package is part of the Wexample Suite — a collection of high-quality, modular tools designed to work seamlessly together across multiple languages and environments.

### Related Packages

The suite includes packages for configuration management, file handling, prompts, and more. Each package can be used independently or as part of the integrated suite.

Visit the [Wexample Suite documentation](https://docs.wexample.com) for the complete package ecosystem.

## Dependencies

- php: >=8.2
- wexample/symfony-helpers: >=7.0.0
- wexample/symfony-api: >=4.0.0
- wexample/symfony-money: >=3.0.0
- rinvex/countries: ^8.0

## Versioning & Compatibility Policy

Wexample packages follow **Semantic Versioning** (SemVer):

- **MAJOR**: Breaking changes
- **MINOR**: New features, backward compatible
- **PATCH**: Bug fixes, backward compatible

We maintain backward compatibility within major versions and provide clear migration guides for breaking changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

Free to use in both personal and commercial projects.

## About us

[Wexample](https://wexample.com) stands as a cornerstone of the digital ecosystem — a collective of seasoned engineers, researchers, and creators driven by a relentless pursuit of technological excellence. More than a media platform, it has grown into a vibrant community where innovation meets craftsmanship, and where every line of code reflects a commitment to clarity, durability, and shared intelligence.

This packages suite embodies this spirit. Trusted by professionals and enthusiasts alike, it delivers a consistent, high-quality foundation for modern development — open, elegant, and battle-tested. Its reputation is built on years of collaboration, refinement, and rigorous attention to detail, making it a natural choice for those who demand both robustness and beauty in their tools.

Wexample cultivates a culture of mastery. Each package, each contribution carries the mark of a community that values precision, ethics, and innovation — a community proud to shape the future of digital craftsmanship.

## Migration Notes

When upgrading between major versions, refer to the migration guides in the documentation.

Breaking changes are clearly documented with upgrade paths and examples.
