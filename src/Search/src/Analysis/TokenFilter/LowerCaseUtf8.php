<?php
declare(strict_types=1);

namespace Search\Analysis\TokenFilter;

use Search;
use Search\Analysis\Token;
use Search\Exception\ExtensionNotLoadedException;

/**
 * Lower case Token filter.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 */
class LowerCaseUtf8 implements TokenFilterInterface
{
    /**
     * Object constructor
     * @throws \Search\Exception\ExtensionNotLoadedException
     */
    public function __construct()
    {
        if (!function_exists('mb_strtolower')) {
            // mbstring extension is disabled
            throw new ExtensionNotLoadedException('Utf8 compatible lower case filter needs mbstring extension to be enabled.');
        }
    }

    /**
     * Normalize Token or remove it (if null is returned)
     *
     * @param \Search\Analysis\Token $srcToken
     * @return \Search\Analysis\Token
     */
    public function normalize(Token $srcToken)
    {
        $newToken = new Token(mb_strtolower($srcToken->getTermText(), 'UTF-8'),
                                       $srcToken->getStartOffset(),
                                       $srcToken->getEndOffset());

        $newToken->setPositionIncrement($srcToken->getPositionIncrement());

        return $newToken;
    }
}
