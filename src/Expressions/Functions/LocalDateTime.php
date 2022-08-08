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

use WikibaseSolutions\CypherDSL\Traits\TypeTraits\PropertyTypeTraits\LocalDateTimeTypeTrait;
use WikibaseSolutions\CypherDSL\Types\AnyType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\LocalDateTimeType;

/**
 * This class represents the "localdatetime()" function.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/functions/temporal/#functions-localdatetime
 * @see Func::localdatetime()
 *
 * @internal This class is not covered by the backwards compatibility promise of php-cypher-dsl
 */
final class LocalDateTime extends Func implements LocalDateTimeType
{
    use LocalDateTimeTypeTrait;

    /**
     * @var AnyType|null The input to the localdatetime function, from which to construct the localdatetime
     */
    private ?AnyType $value;

    /**
     * The signature of the "localdatetime()" function is:
     *
     * localdatetime(input = DEFAULT_TEMPORAL_ARGUMENT :: ANY?) :: (LOCALDATETIME?)
     *
     * @param AnyType|null $value The input to the localdatetime function, from which to construct the localdatetime
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
        return $this->value ? "localdatetime(%s)" : "localdatetime()";
    }

    /**
     * @inheritDoc
     */
    protected function getParameters(): array
    {
        return $this->value ? [$this->value] : [];
    }
}
