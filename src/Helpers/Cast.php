<?php declare(strict_types=1);
/*
 * This file is part of php-cypher-dsl.
 *
 * Copyright (C) Wikibase Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WikibaseSolutions\CypherDSL\Helpers;

use WikibaseSolutions\CypherDSL\Expressions\Literals\Literal;
use WikibaseSolutions\CypherDSL\Expressions\Variable;
use WikibaseSolutions\CypherDSL\Patterns\Pattern;
use WikibaseSolutions\CypherDSL\Traits\ErrorTrait;
use WikibaseSolutions\CypherDSL\Types\AnyType;
use WikibaseSolutions\CypherDSL\Types\CompositeTypes\ListType;
use WikibaseSolutions\CypherDSL\Types\CompositeTypes\MapType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\BooleanType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\IntegerType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\NumeralType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\PropertyType;
use WikibaseSolutions\CypherDSL\Types\PropertyTypes\StringType;
use WikibaseSolutions\CypherDSL\Types\StructuralTypes\StructuralType;

/**
 * Helper class for casting native PHP types to Cypher-DSL types. Casts are added to this class on an as-needed basis.
 *
 * @internal This class is not covered by the backwards compatibility guarantee of php-cypher-dsl
 */
abstract class Cast
{
    use ErrorTrait;

    /**
     * Casts the given value to a ListType.
     *
     * @param ListType|mixed[] $list
     */
    final public static function toListType($list): ListType
    {
        self::assertClass('list', [ListType::class, 'array'], $list);

        return $list instanceof ListType ? $list : Literal::list($list);
    }

    /**
     * Casts the given value to a MapType.
     *
     * @param MapType|mixed[] $map
     */
    final public static function toMapType($map): MapType
    {
        self::assertClass('map', [MapType::class, 'array'], $map);

        return $map instanceof MapType ? $map : Literal::map($map);
    }

    /**
     * Casts the given value to a StringType.
     *
     * @param string|StringType $string
     */
    final public static function toStringType($string): StringType
    {
        self::assertClass('string', [StringType::class, 'string'], $string);

        return $string instanceof StringType ? $string : Literal::string($string);
    }

    /**
     * Casts the given value to a NumeralType.
     *
     * @param float|int|NumeralType $numeral
     */
    final public static function toNumeralType($numeral): NumeralType
    {
        self::assertClass('numeral', [NumeralType::class, 'int', 'float'], $numeral);

        // @phpstan-ignore-next-line
        return $numeral instanceof NumeralType ? $numeral : Literal::number($numeral);
    }

    /**
     * Casts the given value to an IntegerType.
     *
     * @param int|IntegerType $integer
     */
    final public static function toIntegerType($integer): IntegerType
    {
        self::assertClass('integer', [IntegerType::class, 'int'], $integer);

        return $integer instanceof IntegerType ? $integer : Literal::integer($integer);
    }

    /**
     * Casts the given value to a BooleanType.
     *
     * @param bool|BooleanType $boolean
     */
    final public static function toBooleanType($boolean): BooleanType
    {
        self::assertClass('boolean', [BooleanType::class, 'bool'], $boolean);

        return $boolean instanceof BooleanType ? $boolean : Literal::boolean($boolean);
    }

    /**
     * Casts the given value to a PropertyType.
     *
     * @param bool|float|int|PropertyType|string $property
     */
    final public static function toPropertyType($property): PropertyType
    {
        self::assertClass('property', [PropertyType::class, 'bool', 'int', 'float', 'string'], $property);

        // @phpstan-ignore-next-line
        return $property instanceof PropertyType ? $property : Literal::literal($property);
    }

    /**
     * Casts the given value to a StructuralType.
     *
     * @param Pattern|StructuralType $structure
     */
    final public static function toStructuralType($structure): StructuralType
    {
        self::assertClass('structure', [Pattern::class, StructuralType::class], $structure);

        return $structure instanceof StructuralType ? $structure : $structure->getVariable();
    }

    /**
     * Casts the given value to a Variable.
     *
     * @param Pattern|string|Variable $variable
     *
     * @see Cast::toName() for a function that does not accept Pattern
     */
    final public static function toVariable($variable): Variable
    {
        self::assertClass('variable', [Variable::class, Pattern::class, 'string'], $variable);

        if ($variable instanceof Variable) {
            return $variable;
        }

        if ($variable instanceof Pattern) {
            return $variable->getVariable();
        }

        return new Variable($variable);
    }

    /**
     * Casts the given value to a name (as a variable).
     *
     * @param string|Variable $name
     *
     * @see Cast::toVariable() for a function that accepts Pattern
     */
    final public static function toName($name): Variable
    {
        self::assertClass('name', [Variable::class, 'string'], $name);

        return $name instanceof Variable ? $name : new Variable($name);
    }

    /**
     * Casts the given value to an AnyType.
     *
     * @param AnyType|bool|float|int|mixed[]|Pattern|string $value
     */
    final public static function toAnyType($value): AnyType
    {
        self::assertClass('value', [AnyType::class, Pattern::class, 'int', 'float', 'string', 'bool', 'array'], $value);

        if ($value instanceof Pattern) {
            return $value->getVariable();
        }

        if ($value instanceof AnyType) {
            return $value;
        }

        // @phpstan-ignore-next-line
        return Literal::literal($value);
    }
}
