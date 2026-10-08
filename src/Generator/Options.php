<?php

declare(strict_types=1);

namespace Drago\Generator;

use Dibi\Row;
use Nette\Utils\ArrayHash;


class Options
{
	/** Allow converting uppercase to lowercase keys in arrays (typical of Oracle). */
	public bool $lower = false;

	public string $path = '';

	public ?string $tableName = null;

	public ?string $primaryKey = null;

	public bool $constant = true;

	public bool $columnInfo = false;

	public ?string $constantPrefix = null;

	public bool $constantSize = false;

	public bool $references = false;

	public string $suffix = 'Entity';

	public bool $extendsOn = true;

	public string $extends = Row::class;

	public bool $final = false;

	public string $namespace = 'App\Entity';

	public string $pathDataClass = '';

	public bool $constantDataClass = true;

	public bool $constantSizeDataClass = true;

	public ?string $constantDataPrefix = null;

	public bool $referencesDataClass = false;

	public string $suffixDataClass = 'Data';

	public string $extendsDataClass = ArrayHash::class;

	public bool $finalDataClass = false;

	public string $namespaceDataClass = 'App\Data';
}
