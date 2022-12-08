<?php declare(strict_types=1);
/*
 * This file is part of php-cypher-dsl.
 *
 * Copyright (C) Wikibase Solutions
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WikibaseSolutions\CypherDSL\Clauses;

use WikibaseSolutions\CypherDSL\Query;

/**
 * This class represents the UNION clause.
 *
 * The UNION clause is used to combine the results of multiple queries.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/clauses/union/
 * @see https://s3.amazonaws.com/artifacts.opencypher.org/openCypher9.pdf (page 128)
 * @see Query::union() for a more convenient method to construct this class
 */
final class UnionClause extends Clause
{
    /**
     * @var bool whether the union should include all results or remove the duplicates instead
     */
    private bool $all = false;

    /**
     * Combines two queries with a union.
     *
     * @param Query $left  the query preceding the union clause
     * @param Query $right the query after the union clause
     * @param bool  $all   Whether the union should include all results or remove the duplicates instead.
     *
     *                     TODO: Move this function somewhere else.
     */
    public static function union(Query $left, Query $right, bool $all = false): Query
    {
        $tbr = Query::new();

        foreach ($left->getClauses() as $clause) {
            $tbr->addClause($clause);
        }

        $unionClause = new self();
        $unionClause->setAll($all);

        $tbr->addClause($unionClause);

        foreach ($right->getClauses() as $clause) {
            $tbr->addClause($clause);
        }

        return $tbr;
    }

    /**
     * Sets that the union should include all results, instead of removing duplicates.
     *
     * @param bool $all Whether the union should include all results or remove the duplicates instead
     *
     * @return static
     */
    public function setAll(bool $all = true): self
    {
        $this->all = $all;

        return $this;
    }

    /**
     * Returns whether the union includes all results or removes the duplicates instead.
     */
    public function includesAll(): bool
    {
        return $this->all;
    }

    /**
     * @inheritDoc
     */
    public function canBeEmpty(): bool
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    protected function getClause(): string
    {
        return 'UNION';
    }

    /**
     * @inheritDoc
     */
    protected function getSubject(): string
    {
        return $this->all ? 'ALL' : '';
    }
}
