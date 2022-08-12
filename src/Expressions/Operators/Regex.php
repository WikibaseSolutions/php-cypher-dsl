<?php declare(strict_types=1);
/*
 * This file is part of php-cypher-dsl.
 *
 * Copyright (C) 2021  Wikibase Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WikibaseSolutions\CypherDSL\Expressions\Operators;

/**
 * Represents the application of the regex operator.
 *
 * @note This expression is not part of the openCypher standard (version 9)
 * @see https://neo4j.com/docs/cypher-manual/current/syntax/operators/#query-operators-string
 */
final class Regex extends StringSpecificComparisonBinaryOperator
{
    /**
     * @inheritDoc
     */
    protected function getOperator(): string
    {
        return "=~";
    }
}
