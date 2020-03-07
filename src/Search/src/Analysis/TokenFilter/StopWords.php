<?php
declare(strict_types=1);

namespace Search\Analysis\TokenFilter;

use Search;
use Search\Analysis\Token;
use Search\Exception\InvalidArgumentException;
use Search\Exception\RuntimeException;

/**
 * Token filter that removes stop words. These words must be provided as array (set), example:
 * $stopwords = array('the' => 1, 'an' => '1');
 *
 * We do recommend to provide all words in lowercase and concatenate this class after the lowercase filter.
 *
 * @category   Zend
 * @package    Zend_Search_Lucene
 * @subpackage Analysis
 */
class StopWords implements TokenFilterInterface
{
    /**
     * Stop Words
     * @var array
     */
    private $_stopSet;

    /**
     * Constructs new instance of this filter.
     *
     * @param array $stopwords array (set) of words that will be filtered out
     */
    public function __construct($stopwords = array())
    {
        $this->_stopSet = array_flip($stopwords);
    }

    /**
     * Normalize Token or remove it (if null is returned)
     *
     * @param \Search\Analysis\Token $srcToken
     * @return \Search\Analysis\Token
     */
    public function normalize(Token $srcToken)
    {
        if (array_key_exists($srcToken->getTermText(), $this->_stopSet)) {
            return null;
        } else {
            return $srcToken;
        }
    }

    /**
     * Fills stopwords set from a text file. Each line contains one stopword, lines with '#' in the first
     * column are ignored (as comments).
     *
     * You can call this method one or more times. New stopwords are always added to current set.
     *
     * @param string $filepath full path for text file with stopwords
     * @throws \Search\Exception\InvalidArgumentException
     * @throws \Search\Exception\RuntimeException
     */
    public function loadFromFile($filepath = null)
    {
        if (! $filepath || ! file_exists($filepath)) {
            throw new InvalidArgumentException('You have to provide valid file path');
        }
        $fd = fopen($filepath, "r");
        if (! $fd) {
            throw new RuntimeException('Cannot open file ' . $filepath);
        }
        while (!feof ($fd)) {
            $buffer = trim(fgets($fd));
            if (strlen($buffer) > 0 && $buffer[0] != '#') {
                $this->_stopSet[$buffer] = 1;
            }
        }
        if (!fclose($fd)) {
            throw new RuntimeException('Cannot close file ' . $filepath);
        }
    }
}
