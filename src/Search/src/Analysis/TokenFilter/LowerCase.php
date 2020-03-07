<?php
declare(strict_types=1);

namespace Search\Analysis\TokenFilter;

use Search\Analysis\Token;

/**
 * Lower case Token filter.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 */
class LowerCase implements TokenFilterInterface
{
    /**
     * Normalize Token or remove it (if null is returned)
     *
     * @param \Search\Analysis\Token $srcToken
     * @return \Search\Analysis\Token
     */
    public function normalize(Token $srcToken)
    {
        $newToken = new Token(strtolower( $srcToken->getTermText() ),
                                       $srcToken->getStartOffset(),
                                       $srcToken->getEndOffset());

        $newToken->setPositionIncrement($srcToken->getPositionIncrement());

        return $newToken;
    }
}
