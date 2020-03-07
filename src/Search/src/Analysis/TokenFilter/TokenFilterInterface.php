<?php
declare(strict_types=1);

namespace Search\Analysis\TokenFilter;

use Search\Analysis\Token;

/**
 * Token filter converts (normalizes) Token ore removes it from a token stream.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 */
interface TokenFilterInterface
{
    /**
     * Normalize Token or remove it (if null is returned)
     *
     * @param \Search\Analysis\Token $srcToken
     * @return \Search\Analysis\Token
     */
    public function normalize(Token $srcToken);
}
