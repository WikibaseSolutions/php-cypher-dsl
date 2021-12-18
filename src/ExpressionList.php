<?php

/*
 * Cypher DSL
 * Copyright (C) 2021  Wikibase Solutions
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace WikibaseSolutions\CypherDSL;

use TypeError;
use WikibaseSolutions\CypherDSL\Traits\EscapeTrait;
use WikibaseSolutions\CypherDSL\Types\AnyType;
use WikibaseSolutions\CypherDSL\Types\CompositeTypes\ListType;

/**
 * This class represents a list of expressions. For example, this class can represent the following
 * construct:
 *
 * ['a', 2, n.property]
 *
 * @see PropertyMap for a construct that takes keys into account
 */
class ExpressionList implements ListType
{
    use EscapeTrait;

    /**
     * @var array The list of expressions
     */
    private array $expressions;

    /**
     * ExpressionList constructor.
     *
     * @param AnyType[] $expressions The list of expressions, should be homogeneous
     */
    public function __construct(array $expressions)
    {
        foreach ($expressions as $expression) {
            if (!($expression instanceof AnyType)) {
                throw new TypeError("\$expressions must be an array of only AnyType objects");
            }
        }

        $this->expressions = $expressions;
    }

    /**
     * @inheritDoc
     */
    public function toQuery(): string
    {
        $expressions = array_map(
            fn(AnyType $expression): string => $expression->toQuery(),
            $this->expressions
        );

        return sprintf("[%s]", implode(", ", $expressions));
    }
}