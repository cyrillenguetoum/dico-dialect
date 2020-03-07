<?php
declare(strict_types=1);

namespace Search\Search\Query;

use Search\SearchIndexInterface;
use Search\Index;
use Search\Search\Highlighter\HighlighterInterface as Highlighter;
use Search\Search\Weight;

class Term extends AbstractQuery
{
    /**
     * Term to find.
     *
     * @var \Search\Index\Term
     */
    private $_term;

    /**
     * Documents vector.
     *
     * @var array
     */
    private $_docVector = null;

    /**
     * Term freqs vector.
     * array(docId => freq, ...)
     *
     * @var array
     */
    private $_termFreqs;


    /**
     * Search_Search_Query_Term constructor
     *
     * @param \Search\Index\Term $term
     * @param boolean $sign
     */
    public function __construct(Index\Term $term)
    {
        $this->_term = $term;
    }

    /**
     * Re-write query into primitive queries in the context of specified index
     *
     * @param \Search\SearchIndexInterface $index
     * @return \Search\Search\Query\AbstractQuery
     */
    public function rewrite(SearchIndexInterface $index)
    {
        if ($this->_term->field != null) {
            return $this;
        } else {
            $query = new MultiTerm();
            $query->setBoost($this->getBoost());

            foreach ($index->getFieldNames(true) as $fieldName) {
                $term = new Index\Term($this->_term->text, $fieldName);

                $query->addTerm($term);
            }

            return $query->rewrite($index);
        }
    }

    /**
     * Optimize query in the context of specified index
     *
     * @param \Search\SearchIndexInterface $index
     * @return \Search\Search\Query\AbstractQuery
     */
    public function optimize(SearchIndexInterface $index)
    {
        // Check, that index contains specified term
        if (!$index->hasTerm($this->_term)) {
            return new EmptyResult();
        }

        return $this;
    }


    /**
     * Constructs an appropriate Weight implementation for this query.
     *
     * @param \Search\SearchIndexInterface $reader
     * @return \Search\Search\Weight\Term
     */
    public function createWeight(SearchIndexInterface $reader)
    {
        $this->_weight = new Weight\Term($this->_term, $this, $reader);
        return $this->_weight;
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
        $this->_docVector = array_flip($reader->termDocs($this->_term, $docsFilter));
        $this->_termFreqs = $reader->termFreqs($this->_term, $docsFilter);

        // Initialize weight if it's not done yet
        $this->_initWeight($reader);
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
        return $this->_docVector;
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
        if (isset($this->_docVector[$docId])) {
            return $reader->getSimilarity()->tf($this->_termFreqs[$docId]) *
                   $this->_weight->getValue() *
                   $reader->norm($docId, $this->_term->field) *
                   $this->getBoost();
        } else {
            return 0;
        }
    }

    /**
     * Return query terms
     *
     * @return array
     */
    public function getQueryTerms()
    {
        return array($this->_term);
    }

    /**
     * Return query term
     *
     * @return \Search\Index\Term
     */
    public function getTerm()
    {
        return $this->_term;
    }

    /**
     * Query specific matches highlighting
     *
     * @param Highlighter $highlighter  Highlighter object (also contains doc for highlighting)
     */
    protected function _highlightMatches(Highlighter $highlighter)
    {
        $highlighter->highlight($this->_term->text);
    }

    /**
     * Print a query
     *
     * @return string
     */
    public function __toString()
    {
        // It's used only for query visualisation, so we don't care about characters escaping
        if ($this->_term->field !== null) {
            $query = $this->_term->field . ':';
        } else {
            $query = '';
        }

        $query .= $this->_term->text;

        if ($this->getBoost() != 1) {
            $query = $query . '^' . round($this->getBoost(), 4);
        }

        return $query;
    }
}
