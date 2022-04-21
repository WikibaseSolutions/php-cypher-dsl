<?php

namespace WikibaseSolutions\CypherDSL;

use InvalidArgumentException;
use WikibaseSolutions\CypherDSL\Types\AnyType;
use function in_array;
use function strtoupper;

/**
 * Defines the order of an expression. Can only be used in an ORDER BY clause.
 *
 * @see https://neo4j.com/docs/cypher-manual/current/clauses/order-by/
 * @note While the documentation online does not mention this, ORDER BY supports multiple directions in the same clause:
 *      - ORDER BY a ASC, b DESC
 *      is considered valid.
 *      This means it is impossible for the OrderBy clause to order all expressions individually, necessitating this class.
 */
class Order implements QueryConvertable
{
    private AnyType $expression;
    /** @var string|null */
    private ?string $ordering;

    /**
     * Order constructor.
     *
     * @param AnyType $expression The expression to order by.
     * @param string|null $ordering The order modifier. Must be null or a valid modifier ('ASC', 'ASCENDING', 'DESC', 'DESCENDING')
     */
    public function __construct(AnyType $expression, ?string $ordering = null)
    {
        $this->expression = $expression;
        $this->setOrdering($ordering);
    }

    /**
     * Returns the expression being ordered.
     *
     * @return AnyType
     */
    public function getExpression(): AnyType
    {
        return $this->expression;
    }

    /**
     * @return string|null
     */
    public function getOrdering(): ?string
    {
        return $this->ordering;
    }

    public function setOrdering(?string $ordering): self
    {
        if ($ordering !== null) {
            $ordering = strtoupper($ordering);
            if (!in_array($ordering, ['ASC', 'DESC', 'ASCENDING', 'DESCENDING']))  {
                throw new InvalidArgumentException('Ordering must be null, "ASC", "DESC", "ASCENDING" or "DESCENDING"');
            }

            $this->ordering = $ordering;
        } else {
            $this->ordering = null;
        }

        return $this;
    }

    public function toQuery(): string
    {
        $cypher = $this->getExpression()->toQuery();
        if ($this->ordering) {
            $cypher .= ' ' . $this->ordering;
        }

        return $cypher;
    }
}