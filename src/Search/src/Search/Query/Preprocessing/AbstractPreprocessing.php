<?php
declare(strict_types=1);

namespace Search\Search\Query\Preprocessing;

use Search\SearchIndexInterface;
use Search\Exception\UnsupportedMethodCallException;
use Search\Search\Query;

/**
 * It's an internal abstract class intended to finalize ase a query processing after query parsing.
 * This type of query is not actually involved into query execution.
 */
abstract class AbstractPreprocessing extends Query\AbstractQuery
{
    /**
     * Matched terms.
     *
     * Matched terms list.
     * It's filled during rewrite operation and may be used for search result highlighting
     *
     * Array of Search_Index_Term objects
     *
     * @var array
     */
    protected $_matches = null;

    /**
     * Optimize query in the context of specified index
     *
     * @param \Search\SearchIndexInterface $index
     * @throws \Search\Exception\UnsupportedMethodCallException
     * @return \Search\Search\Query\AbstractQuery
     */
    public function optimize(SearchIndexInterface $index)
    {
        throw new UnsupportedMethodCallException('This query is not intended to be executed.');
    }

    /**
     * Constructs an appropriate Weight implementation for this query.
     *
     * @param \Search\SearchIndexInterface $reader
     * @throws \Search\Exception\UnsupportedMethodCallException
     */
    public function createWeight(SearchIndexInterface $reader)
    {
        throw new UnsupportedMethodCallException('This query is not intended to be executed.');
    }

    /**
     * Execute query in context of index reader
     * It also initializes necessary internal structures
     *
     * @param \Search\SearchIndexInterface $reader
     * @param \Search\Index\DocsFilter|null $docsFilter
     * @throws \Search\Exception\UnsupportedMethodCallException
     */
    public function execute(SearchIndexInterface $reader, $docsFilter = null)
    {
        throw new UnsupportedMethodCallException('This query is not intended to be executed.');
    }

    /**
     * Get document ids likely matching the query
     *
     * It's an array with document ids as keys (performance considerations)
     *
     * @throws \Search\Exception\UnsupportedMethodCallException
     * @return array
     */
    public function matchedDocs()
    {
        throw new UnsupportedMethodCallException('This query is not intended to be executed.');
    }

    /**
     * Score specified document
     *
     * @param integer $docId
     * @param \Search\SearchIndexInterface $reader
     * @throws \Search\Exception\UnsupportedMethodCallException
     * @return float
     */
    public function score($docId, SearchIndexInterface $reader)
    {
        throw new UnsupportedMethodCallException('This query is not intended to be executed.');
    }

    /**
     * Return query terms
     *
     * @throws \Search\Exception\UnsupportedMethodCallException
     * @return array
     */
    public function getQueryTerms()
    {
        throw new UnsupportedMethodCallException('Rewrite operation has to be done before retrieving query terms.');
    }
}
