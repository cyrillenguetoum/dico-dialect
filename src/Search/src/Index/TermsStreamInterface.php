<?php
declare(strict_types=1);

namespace Search\Index;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Index
 */
interface TermsStreamInterface
{
    /**
     * Reset terms stream.
     */
    public function resetTermsStream();

    /**
     * Skip terms stream up to specified term preffix.
     *
     * Prefix contains fully specified field info and portion of searched term
     *
     * @param \Search\Index\Term $prefix
     */
    public function skipTo(Term $prefix);

    /**
     * Scans terms dictionary and returns next term
     *
     * @return \Search\Index\Term|null
     */
    public function nextTerm();

    /**
     * Returns term in current position
     *
     * @return \Search\Index\Term|null
     */
    public function currentTerm();

    /**
     * Close terms stream
     *
     * Should be used for resources clean up if stream is not read up to the end
     */
    public function closeTermsStream();
}
