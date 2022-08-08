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

use WikibaseSolutions\CypherDSL\Traits\ErrorTrait;
use WikibaseSolutions\CypherDSL\Traits\TypeTraits\PropertyTypeTraits\BooleanTypeTrait;
use WikibaseSolutions\CypherDSL\Types\AnyType;
use WikibaseSolutions\CypherDSL\Types\CompositeTypes\ListType;
use WikibaseSolutions\CypherDSL\Types\CompositeTypes\MapType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\BooleanType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\StringType;

/**
 * This class represents the "isEmpty()" function.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/functions/predicate/#functions-isempty
 * @see Func::isEmpty()
 *
 * @internal This class is not covered by the backwards compatibility promise of php-cypher-dsl
 */
final class IsEmpty extends Func implements BooleanType
{
    use BooleanTypeTrait;
    use ErrorTrait;

    /**
     * @var ListType|MapType|StringType An expression that returns a list
     */
    private AnyType $list;

    /**
     * The signature of the "isEmpty()" function is:
     *
     * isEmpty(input :: LIST? OF ANY?) :: (BOOLEAN?) - to check whether a list is empty
     * isEmpty(input :: MAP?) :: (BOOLEAN?) - to check whether a map is empty
     * isEmpty(input :: STRING?) :: (BOOLEAN?) - to check whether a string is empty
     *
     * @param ListType|MapType|StringType $list An expression that returns a list
     */
    public function __construct(AnyType $list)
    {
        self::assertClass('list', [ListType::class, MapType::class, StringType::class], $list);

        $this->list = $list;
    }

    /**
     * @inheritDoc
     */
    protected function getSignature(): string
    {
        return "isEmpty(%s)";
    }

    /**
     * @inheritDoc
     */
    protected function getParameters(): array
    {
        return [$this->list];
    }
}
