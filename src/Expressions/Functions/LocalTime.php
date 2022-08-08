<?php declare(strict_types=1);
/*
 * This file is part of php-cypher-dsl.
 *
 * Copyright (C) 2021  Wikibase Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WikibaseSolutions\CypherDSL\Expressions\Functions;

use WikibaseSolutions\CypherDSL\Traits\TypeTraits\PropertyTypeTraits\LocalTimeTypeTrait;
use WikibaseSolutions\CypherDSL\Types\AnyType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\LocalTimeType;

/**
 * This class represents the "localtime()" function.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/functions/temporal/#functions-localtime
 * @see Func::localtime()
 *
 * @internal This class is not covered by the backwards compatibility promise of php-cypher-dsl
 */
final class LocalTime extends Func implements LocalTimeType
{
    use LocalTimeTypeTrait;

    /**
     * @var AnyType|null The input to the localtime function, from which to construct the localtime
     */
    private ?AnyType $value;

    /**
     * The signature of the "localtime()" function is:
     *
     * localtime(input = DEFAULT_TEMPORAL_ARGUMENT :: ANY?) :: (LOCALTIME?)
     *
     * @param AnyType|null $value The input to the localtime function, from which to construct the localtime
     */
    public function __construct(?AnyType $value = null)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    protected function getSignature(): string
    {
        return $this->value ? "localtime(%s)" : "localtime()";
    }

    /**
     * @inheritDoc
     */
    protected function getParameters(): array
    {
        return $this->value ? [$this->value] : [];
    }
}
