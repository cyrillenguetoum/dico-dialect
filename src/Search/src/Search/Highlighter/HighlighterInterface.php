<?php
declare(strict_types=1);

namespace Search\Search\Highlighter;

use Search\Document;

/**
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Search
 */
interface HighlighterInterface
{
    /**
     * Set document for highlighting.
     *
     * @param \Search\Document\HTML $document
     */
    public function setDocument(Document\HTML $document);

    /**
     * Get document for highlighting.
     *
     * @return \Search\Document\HTML $document
     */
    public function getDocument();

    /**
     * Highlight specified words (method is invoked once per subquery)
     *
     * @param string|array $words  Words to highlight. They could be organized using the array or string.
     */
    public function highlight($words);
}
