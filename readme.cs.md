# Drago Generator

Malý nástroj pro příkazovou řádku, který z databázového schématu generuje PHP třídy entit a datové třídy.
Drago Generator je postavený na Nette, Dibi a Symfony Console a vytváří pro vaše tabulky hotové PHP třídy s nastavitelnými konstantami, referencemi, příponami a jmennými prostory.

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://github.com/drago-ex/generator/blob/main/license)
[![PHP version](https://badge.fury.io/ph/drago-ex%2Fgenerator.svg)](https://badge.fury.io/ph/drago-ex%2Fgenerator)
[![Tests](https://github.com/drago-ex/generator/actions/workflows/tests.yml/badge.svg)](https://github.com/drago-ex/generator/actions/workflows/tests.yml)
[![Coding Style](https://github.com/drago-ex/generator/actions/workflows/coding-style.yml/badge.svg)](https://github.com/drago-ex/generator/actions/workflows/coding-style.yml)

## Požadavky

- PHP >= 8.3, < 9
- Nette Framework
- Symfony Console
- Dibi
- Composer

## Instalace

```
composer require drago-ex/generator --dev
```

## Příklady

Generování spouštějte spustitelným souborem nainstalovaným Composerem:
```bash
# Generate entity classes
php vendor/bin/generator app:entity <table>

# Generate data classes
php vendor/bin/generator app:dataClass <table>
```

```bash
# Generate all entities
php vendor/bin/generator app:entity

# Generate entity for a specific table
php vendor/bin/generator app:entity users

# Generate all data classes
php vendor/bin/generator app:dataClass

# Generate data class for a specific table
php vendor/bin/generator app:dataClass orders
```

## Registrace rozšíření v Nette

```neon
extensions:
	generator: Drago\Generator\DI\GeneratorExtension(%consoleMode%)
	console: Contributte\Console\DI\ConsoleExtension(%consoleMode%)

generator:
	path: %appDir%/Entity
	pathDataClass: %appDir%/Values

console:
	name: Symfony Console
	version: 1.0
```

Generátor potřebuje v kontejneru také službu `Dibi\Connection`.

## Volby

| Volba | Výchozí | Popis |
|---|---|---|
| `lower` | `false` | Převede názvy sloupců na malá písmena (typické pro Oracle). |
| `path` | `` | Adresář pro třídy entit. |
| `namespace` | `App\Entity` | Jmenný prostor tříd entit. |
| `suffix` | `Entity` | Přípona názvů tříd entit. |
| `extendsOn` | `true` | Zda vygenerované třídy dědí z rodičovské třídy. |
| `extends` | `Drago\Database\Entity` | Rodičovská třída entit. |
| `final` | `false` | Generuje finální třídy entit. |
| `tableName` | `Table` | Název konstanty s názvem tabulky. |
| `primaryKey` | `Id` | Název konstanty se sloupcem auto-increment. |
| `constant` | `true` | Generuje konstantu pro každý sloupec. |
| `constantPrefix` | žádná | Předpona konstant sloupců. |
| `constantSize` | `false` | Generuje konstanty `<Column>Size` s velikostmi sloupců. |
| `columnInfo` | `false` | Přidá komentáře s primárním klíčem, výchozí hodnotou a velikostí. |
| `references` | `false` | Přidá vlastnosti pro reference cizích klíčů, pojmenované podle tabulky (podle sloupce klíče bez `_id`, když se na tabulku odkazuje víckrát). |
| `pathDataClass` | `` | Adresář pro datové třídy. |
| `namespaceDataClass` | `App\Values` | Jmenný prostor datových tříd. |
| `suffixDataClass` | `Values` | Přípona názvů datových tříd. |
| `extendsDataClass` | `Drago\Utils\ExtraArrayHash` | Rodičovská třída datových tříd. |
| `finalDataClass` | `false` | Generuje finální datové třídy. |
| `constantDataClass` | `true` | Generuje konstantu pro každý sloupec. |
| `constantDataPrefix` | žádná | Předpona konstant sloupců v datových třídách. |
| `constantSizeDataClass` | `true` | Generuje konstanty `<Column>Size`. |
| `referencesDataClass` | `false` | Přidá vlastnosti pro reference cizích klíčů, pojmenované jako v entitách; u klíče, který může být NULL, jsou nullable. |

Datová třída drží hodnoty formuláře (`$form->getValues(OrdersValues::class)`), proto výchozí název končí na
`Values`, stejně jako třídy hodnot formulářů v balíčcích drago-ex (např. `ArticleValues`).

Balíček `drago-ex/database` ve svých příkladech používá pro konstantu primárního klíče název `PrimaryKey`.
Pro stejný název nastavte `primaryKey: PrimaryKey`.

Typy sloupců se převádějí na `int`, `float`, `bool`, `string` a `\DateTimeImmutable`. Binární sloupce
(BLOB, BINARY) se generují jako `string`, což pro ně vrací Dibi. Názvy sloupců se závorkami
(například z pohledu) se odmítnou; použijte alias.

## Funkce

- Generování tříd entit a datových tříd z databázových tabulek
- Nastavitelné konstanty a konstanty velikostí sloupců
- Podpora referencí cizích klíčů
- Vlastní přípony, jmenné prostory a finální třídy
- Integrace se Symfony Console pro přehledný výstup

## Požadavky pro příkazovou řádku

`vendor/bin/generator` se spouští z kořene projektu. Načte `app/Bootstrap.php` a zavolá
`App\Bootstrap::bootConsoleApplication()`, které musí vrátit DI kontejner se zaregistrovaným generátorem
a konzolí. Pro projekty s jinou strukturou napište vlastní krátký skript,
který vytvoří kontejner a spustí `Contributte\Console\Application`.

## Spuštění testů

Databázové testy běží proti MySQL. Naimportujte `tests/db.sql` a nastavte připojení:

```bash
GENERATOR_DB_HOST=127.0.0.1 GENERATOR_DB_PORT=3306 GENERATOR_DB_USER=root GENERATOR_DB_PASSWORD=root GENERATOR_DB_NAME=test composer tester
```

Bez `GENERATOR_DB_HOST` se databázové testy přeskočí.
