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

use WikibaseSolutions\CypherDSL\Patterns\Node;
use WikibaseSolutions\CypherDSL\Patterns\Path;
use WikibaseSolutions\CypherDSL\Query;
use WikibaseSolutions\CypherDSL\Traits\HelperTraits\ErrorTrait;

/**
 * This class represents a CREATE clause. The CREATE clause is used to create graph elements.
 *
 * @see https://s3.amazonaws.com/artifacts.opencypher.org/openCypher9.pdf (page 99)
 * @see https://neo4j.com/docs/cypher-manual/current/clauses/create/
 * @see Query::create() for a more convenient method to construct this class
 */
class CreateClause extends Clause
{
    use ErrorTrait;

    /**
     * @var Path[]|Node[] The patterns to create
     */
    private array $patterns = [];

    /**
     * Sets the pattern to create. This overwrites any previously added patterns.
     *
     * @param Path[]|Node[] $patterns The patterns to put in the CREATE clause
     * @return $this
     */
    public function setPatterns(array $patterns): self
    {
        foreach ($patterns as $pattern) {
            $this->assertClass('patterns', [Path::class, Node::class], $pattern);
        }

        $this->patterns = $patterns;

        return $this;
    }

    /**
     * Add a pattern to create.
     *
     * @param Path|Node $pattern The pattern to add to the CREATE clause
     * @return $this
     */
    public function addPattern($pattern): self
    {
        $this->assertClass('pattern', [Path::class, Node::class], $pattern);
        $this->patterns[] = $pattern;

        return $this;
    }

    /**
     * Returns the patterns of the CREATE clause.
     *
     * @return Path[]|Node[]
     */
    public function getPatterns(): array
    {
        return $this->patterns;
    }

    /**
     * @inheritDoc
     */
    protected function getClause(): string
    {
        return "CREATE";
    }

    /**
     * @inheritDoc
     */
    protected function getSubject(): string
    {
        return implode(", ", array_map(fn ($pattern): string => $pattern->toQuery(), $this->patterns));
    }
}
