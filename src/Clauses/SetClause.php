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

namespace WikibaseSolutions\CypherDSL\Clauses;

use TypeError;
use WikibaseSolutions\CypherDSL\Assignment;
use WikibaseSolutions\CypherDSL\Label;
use WikibaseSolutions\CypherDSL\QueryConvertable;

/**
 * This class represents a SET clause.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/clauses/set/
 */
class SetClause extends Clause
{
    /**
     * @var Assignment[]|Label[] $expressions The expressions to set
     */
    private array $expressions = [];

    /**
     * Add an assignment.
     *
     * @param Assignment|Label $expression The assignment to execute
     * @return SetClause
     */
    public function addAssignment($expression): self
    {
        if (!($expression instanceof Assignment) && !($expression instanceof Label)) {
            throw new TypeError("\$expression should be either an Assignment object or a Label object");
        }

        $this->expressions[] = $expression;

        return $this;
    }

    /**
     * @inheritDoc
     */
    protected function getClause(): string
    {
        return "SET";
    }

    /**
     * @inheritDoc
     */
    protected function getSubject(): string
    {
        return implode(
            ", ",
            array_map(fn(QueryConvertable $expression): string => $expression->toQuery(), $this->expressions)
        );
    }
}