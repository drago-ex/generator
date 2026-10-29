<?php declare(strict_types=1);

namespace Drago\Generator;

use Drago\Database\Entity;
use Drago\Utils\ExtraArrayHash;


class Options
{
	/** Allow converting uppercase to lowercase keys in arrays (typical of Oracle). */
	public bool $lower = false;

	public string $path = '';

	/** Name of the constant holding the table name. */
	public string $tableName = 'Table';

	/** Name of the constant holding the auto-increment column. */
	public string $primaryKey = 'Id';

	public bool $constant = true;

	public bool $columnInfo = false;

	public ?string $constantPrefix = null;

	public bool $constantSize = false;

	public bool $references = false;

	public string $suffix = 'Entity';

	public bool $extendsOn = true;

	public string $extends = Entity::class;

	public bool $final = false;

	public string $namespace = 'App\Entity';

	public string $pathDataClass = '';

	public bool $constantDataClass = true;

	public bool $constantSizeDataClass = true;

	public ?string $constantDataPrefix = null;

	public bool $referencesDataClass = false;

	/** Data classes hold form values, so they are named like the *Values classes of the drago-ex packages. */
	public string $suffixDataClass = 'Values';

	public string $extendsDataClass = ExtraArrayHash::class;

	public bool $finalDataClass = false;

	public string $namespaceDataClass = 'App\Values';
}
