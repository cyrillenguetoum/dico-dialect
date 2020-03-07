<?php
declare(strict_types=1);

namespace Search\Analysis\Analyzer;

/**
 * An AnalyzerInterface is used to analyze text.
 */
interface AnalyzerInterface
{
    /**
     * Tokenize text to terms
     * Returns array of Search\Analysis\Token objects
     *
     * Tokens are returned in UTF-8 (internal Zend_Search_Lucene encoding)
     *
     * @param string $data
     * @return array
     */
    public function tokenize($data, $encoding = '');

    /**
     * Tokenization stream API
     * Set input
     *
     * @param string $data
     */
    public function setInput($data, $encoding = '');

    /**
     * Reset token stream
     */
    public function reset();

    /**
     * Tokenization stream API
     * Get next token
     * Returns null at the end of stream
     *
     * Tokens are returned in UTF-8 (internal Zend_Search_Lucene encoding)
     *
     * @return \Search\Analysis\Token|null
     */
    public function nextToken();
}
