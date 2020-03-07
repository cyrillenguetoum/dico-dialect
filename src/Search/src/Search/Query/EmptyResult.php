<?php
declare(strict_types=1);

namespace Search\Search\Query;

use Search\SearchIndexInterface;
use Search\Search\Highlighter\HighlighterInterface as Highlighter;
use Search\Search\Weight;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 */
class EmptyResult extends AbstractQuery
{
    /**
     * Re-write query into primitive queries in the context of specified index
     *
     * @param \Search\SearchIndexInterface $index
     * @return \Search\Search\Query\AbstractQuery
     */
    public function rewrite(SearchIndexInterface $index)
    {
        return $this;
    }

    /**
     * Optimize query in the context of specified index
     *
     * @param \Search\SearchIndexInterface $index
     * @return \Search\Search\Query\AbstractQuery
     */
    public function optimize(SearchIndexInterface $index)
    {
        // "EmptyResult" query is a primitive query and don't need to be optimized
        return $this;
    }

    /**
     * Constructs an appropriate Weight implementation for this query.
     *
     * @param \Search\SearchIndexInterface $reader
     * @return \Search\Search\Weight\EmptyResultWeight
     */
    public function createWeight(SearchIndexInterface $reader)
    {
        return new Weight\EmptyResultWeight();
    }

    /**
     * Execute query in context of index reader
     * It also initializes necessary internal structures
     *
     * @param \Search\SearchIndexInterface $reader
     * @param \Search\Index\DocsFilter|null $docsFilter
     */
    public function execute(SearchIndexInterface $reader, $docsFilter = null)
    {
        // Do nothing
    }

    /**
     * Get document ids likely matching the query
     *
     * It's an array with document ids as keys (performance considerations)
     *
     * @return array
     */
    public function matchedDocs()
    {
        return array();
    }

    /**
     * Score specified document
     *
     * @param integer $docId
     * @param \Search\SearchIndexInterface $reader
     * @return float
     */
    public function score($docId, SearchIndexInterface $reader)
    {
        return 0;
    }

    /**
     * Return query terms
     *
     * @return array
     */
    public function getQueryTerms()
    {
        return array();
    }

    /**
     * Query specific matches highlighting
     *
     * @param Highlighter $highlighter  Highlighter object (also contains doc for highlighting)
     */
    protected function _highlightMatches(Highlighter $highlighter)
    {
        // Do nothing
    }

    /**
     * Print a query
     *
     * @return string
     */
    public function __toString()
    {
        return '<EmptyQuery>';
    }
}
