<?php declare(strict_types=1);
/*
 * This file is part of php-cypher-dsl.
 *
 * Copyright (C) 2021  Wikibase Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WikibaseSolutions\CypherDSL\Tests\Unit\Traits\TypeTraits;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use WikibaseSolutions\CypherDSL\Expressions\Operators\Equality;
use WikibaseSolutions\CypherDSL\Expressions\Operators\GreaterThan;
use WikibaseSolutions\CypherDSL\Expressions\Operators\GreaterThanOrEqual;
use WikibaseSolutions\CypherDSL\Expressions\Operators\Inequality;
use WikibaseSolutions\CypherDSL\Expressions\Operators\IsNotNull;
use WikibaseSolutions\CypherDSL\Expressions\Operators\IsNull;
use WikibaseSolutions\CypherDSL\Expressions\Operators\LessThan;
use WikibaseSolutions\CypherDSL\Expressions\Operators\LessThanOrEqual;
use WikibaseSolutions\CypherDSL\Query;
use WikibaseSolutions\CypherDSL\Syntax\Alias;
use WikibaseSolutions\CypherDSL\Traits\TypeTraits\AnyTypeTrait;
use WikibaseSolutions\CypherDSL\Types\AnyType;

/**
 * @covers \WikibaseSolutions\CypherDSL\Traits\TypeTraits\AnyTypeTrait
 */
final class AnyTypeTraitTest extends TestCase
{
    /**
     * @var MockObject|AnyType
     */
    private $a;

    /**
     * @var MockObject|AnyType
     */
    private $b;

    public function setUp(): void
    {
        $this->a = new class () implements AnyType {
            use AnyTypeTrait;

            public function toQuery(): string
            {
                return '10';
            }
        };
        $this->b = $this->createMock(AnyType::class);
        $this->b->method('toQuery')->willReturn("date({year: 2020, month: 12, day: 5})");
    }

    public function testGt(): void
    {
        $gt = $this->a->gt($this->b);

        $this->assertInstanceOf(GreaterThan::class, $gt);

        $this->assertTrue($gt->insertsParentheses());
        $this->assertEquals($this->a, $gt->getLeft());
        $this->assertEquals($this->b, $gt->getRight());
    }

    public function testGtLiteral(): void
    {
        $gt = $this->a->gt(10);

        $this->assertInstanceOf(GreaterThan::class, $gt);
    }

    public function testGtNoParentheses(): void
    {
        $gt = $this->a->gt($this->b, false);

        $this->assertInstanceOf(GreaterThan::class, $gt);

        $this->assertFalse($gt->insertsParentheses());
        $this->assertEquals($this->a, $gt->getLeft());
        $this->assertEquals($this->b, $gt->getRight());
    }

    public function testGte(): void
    {
        $gte = $this->a->gte($this->b);

        $this->assertInstanceOf(GreaterThanOrEqual::class, $gte);

        $this->assertTrue($gte->insertsParentheses());
        $this->assertEquals($this->a, $gte->getLeft());
        $this->assertEquals($this->b, $gte->getRight());
    }

    public function testGteLiteral(): void
    {
        $gte = $this->a->gte(10);

        $this->assertInstanceOf(GreaterThanOrEqual::class, $gte);
    }

    public function testGteNoParentheses(): void
    {
        $gte = $this->a->gte($this->b, false);

        $this->assertInstanceOf(GreaterThanOrEqual::class, $gte);

        $this->assertFalse($gte->insertsParentheses());
        $this->assertEquals($this->a, $gte->getLeft());
        $this->assertEquals($this->b, $gte->getRight());
    }

    public function testLt(): void
    {
        $lt = $this->a->lt($this->b);

        $this->assertInstanceOf(LessThan::class, $lt);

        $this->assertTrue($lt->insertsParentheses());
        $this->assertEquals($this->a, $lt->getLeft());
        $this->assertEquals($this->b, $lt->getRight());
    }

    public function testLtLiteral(): void
    {
        $lt = $this->a->lt(10);

        $this->assertInstanceOf(LessThan::class, $lt);
    }

    public function testLtNoParentheses(): void
    {
        $lt = $this->a->lt($this->b, false);

        $this->assertInstanceOf(LessThan::class, $lt);

        $this->assertFalse($lt->insertsParentheses());
        $this->assertEquals($this->a, $lt->getLeft());
        $this->assertEquals($this->b, $lt->getRight());
    }

    public function testLte(): void
    {
        $lte = $this->a->lte($this->b);

        $this->assertInstanceOf(LessThanOrEqual::class, $lte);

        $this->assertTrue($lte->insertsParentheses());
        $this->assertEquals($this->a, $lte->getLeft());
        $this->assertEquals($this->b, $lte->getRight());
    }

    public function testLteLiteral(): void
    {
        $lte = $this->a->lte(10);

        $this->assertInstanceOf(LessThanOrEqual::class, $lte);
    }

    public function testLteNoParentheses(): void
    {
        $lte = $this->a->lte($this->b, false);

        $this->assertInstanceOf(LessThanOrEqual::class, $lte);

        $this->assertFalse($lte->insertsParentheses());
        $this->assertEquals($this->a, $lte->getLeft());
        $this->assertEquals($this->b, $lte->getRight());
    }

    public function testAlias(): void
    {
        $alias = $this->a->alias(Query::variable('foo'));

        $this->assertInstanceOf(Alias::class, $alias);
    }

    public function testAliasLiteral(): void
    {
        $alias = $this->a->alias('foo');

        $this->assertInstanceOf(Alias::class, $alias);
    }

    public function testEquals(): void
    {
        $equals = $this->a->equals($this->b);

        $this->assertInstanceOf(Equality::class, $equals);

        $this->assertTrue($equals->insertsParentheses());
        $this->assertEquals($this->a, $equals->getLeft());
        $this->assertEquals($this->b, $equals->getRight());
    }

    public function testEqualsLiteral(): void
    {
        $equals = $this->a->equals(10);

        $this->assertInstanceOf(Equality::class, $equals);
    }

    public function testEqualsNoParentheses(): void
    {
        $equals = $this->a->equals($this->b, false);

        $this->assertInstanceOf(Equality::class, $equals);

        $this->assertFalse($equals->insertsParentheses());
        $this->assertEquals($this->a, $equals->getLeft());
        $this->assertEquals($this->b, $equals->getRight());
    }

    public function testNotEquals(): void
    {
        $notEquals = $this->a->notEquals($this->b);

        $this->assertInstanceOf(Inequality::class, $notEquals);

        $this->assertTrue($notEquals->insertsParentheses());
        $this->assertEquals($this->a, $notEquals->getLeft());
        $this->assertEquals($this->b, $notEquals->getRight());
    }

    public function testNotEqualsLiteral(): void
    {
        $notEquals = $this->a->notEquals(10);

        $this->assertInstanceOf(Inequality::class, $notEquals);
    }

    public function testNotEqualsNoParentheses(): void
    {
        $notEquals = $this->a->notEquals($this->b, false);

        $this->assertInstanceOf(Inequality::class, $notEquals);

        $this->assertFalse($notEquals->insertsParentheses());
        $this->assertEquals($this->a, $notEquals->getLeft());
        $this->assertEquals($this->b, $notEquals->getRight());
    }

    public function testIsNull(): void
    {
        $isNull = $this->a->isNull();

        $this->assertInstanceOf(IsNull::class, $isNull);

        $this->assertTrue($isNull->insertsParentheses());
        $this->assertEquals($this->a, $isNull->getExpression());
    }

    public function testIsNullNoParentheses(): void
    {
        $isNull = $this->a->isNull(false);

        $this->assertInstanceOf(IsNull::class, $isNull);

        $this->assertFalse($isNull->insertsParentheses());
        $this->assertEquals($this->a, $isNull->getExpression());
    }

    public function testIsNotNull(): void
    {
        $isNotNull = $this->a->isNotNull();

        $this->assertInstanceOf(IsNotNull::class, $isNotNull);

        $this->assertTrue($isNotNull->insertsParentheses());
        $this->assertEquals($this->a, $isNotNull->getExpression());
    }

    public function testIsNotNullNoParentheses(): void
    {
        $isNotNull = $this->a->isNotNull(false);

        $this->assertInstanceOf(IsNotNull::class, $isNotNull);

        $this->assertFalse($isNotNull->insertsParentheses());
        $this->assertEquals($this->a, $isNotNull->getExpression());
    }
}
