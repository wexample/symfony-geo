# Add a reusable postal Address and map network's Country data

Opened: 2026-09-24
Updated: 2026-09-24
Author: agent:archeology

## Read this first — status of this todo

> **This is a proposal for discussion, not an order to code.** It was written by the 2026-09 network archaeology pass. Read it, then discuss it with the owner: every design choice and recommendation below is to be challenged and validated **before** any code is written. Do not start implementing on your own.
>
> - Context: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/.wex/knowledge/readme/archeology/index.md.j2` (entry point, order between packages), then `sources.md.j2` (where the legacy code lives: archive repo, branch checkouts, GitLab issues) and the domain page linked below.
> - Pending owner decisions affecting this work are listed in `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/.wex/knowledge/readme/archeology/recap.md.j2`, section "Décisions qui t'attendent". Where this todo assumes an answer, treat it as an open question.
> - Safety: `NETWORK/local/network` runs on **production data** (real bookkeeping, real invoices in `var/`, a prod dump in `.wex/mysql/dumps/`) — read its code only, never run anything against it. Anonymize any fixture taken from network (bank exports, FEC, mails contain real names/accounts). Never copy secrets found in its history (Stripe keys, tokens, passwords, private keys).

## Goal

network's `Address` (postal address + country) is shared by organizations, users and cart billing/shipping. It is geographic, low-level and needed by `symfony-company`, `symfony-cart` and the app, so it belongs next to `Country` in `symfony-geo`. Also document how network's own `country` table (2026-07) maps to this package's `Country`.

## Read first

- Knowledge page: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/.wex/knowledge/readme/archeology/organization.md.j2` (sections "Address", "Country and country profiles", "Pitfalls").
- Consumer todo: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/PACKAGES/PHP/packages/wexample/symfony-company/.wex/journal/todo/extract-from-network.md`.

## Sources

- Best base (prod): `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/src/Wex/BaseBundle/Entity/Address.php` (postal_address, post_code, city, country → Country SET NULL, user owner CASCADE, `toStringInline()`).
- Person/type additions (dev branch): `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Entity/Address.php` (firstName/lastName, type `address-book|assigned`, `billing|shipping` constants, `cloneFrom()`).
- Forms: `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Form/Entity/Address/AddressLocationType.php`, `AddressPersonType.php`, `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/archeo/trees/develop-131-fos-user/src/Form/Entity/Country/CountryType.php` (default country).
- network Country (prod): `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/src/Entity/Country.php` + `/home/weeger/Desktop/WIP/WEB/WEXAMPLE/NETWORK/local/network/migrations/Version20260705000001.php` (ISO seed, backfill of free-text `address.country`: France→FR, Belgique→BE, England→GB).
- Do NOT use the dev-branch Country (`trees/develop-131-fos-user/src/Entity/Country.php`, migrations `Version20221107195250/52`): different schema and a migration that drops data.

## Steps

1. `Entity/AbstractAddress` mapped superclass (UUID via helpers): `postalAddress` (255, multi-line allowed), `postCode` (60), `city`, `country` → `Country` (nullable, SET NULL), optional `firstName`/`lastName` (person addresses), `toStringInline()`, `__toString()`, `copyFrom(AbstractAddress)` (from fos-user `cloneFrom`). No `user` relation in the package (owners are app/other packages). Tests: string rendering with/without country, copy.
2. Optional `Form/AddressType` (location fields + country choice with configurable default ISO code, e.g. `FR`) and `Form/AddressPersonType`. Tests: form maps to entity.
3. README section "Migrating from network": network `country.code` = `isoAlpha2Code`, `code3` = `isoAlpha3Code`, `name_fr` → translation (the package has `name` + `nativeName`; decide whether to add localized names), `address.post_code` → `postCode`, `address.postal_address` → `postalAddress`.

## Do not

- No billing/shipping business logic (cart), no `payments` relation, no `address-book` workflow (app/cart concern) — only the value holder.
- No integer country ids (`COUNTRY_ID_FRANCE = 37` in the dev branch).

## Acceptance criteria

- Tests for `AbstractAddress` behaviour and the optional form types; symfony-company can map its organization address to a class extending `AbstractAddress`.
