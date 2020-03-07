<?php
declare(strict_types=1);

namespace Search\Analysis\Analyzer\Common;

use Search\Analysis;
use Search\Analysis\Analyzer\AnalyzerInterface;
use Search\Analysis\TokenFilter\TokenFilterInterface;

/**
 * AbstractCommon implementation of the analyzerfunctionality.
 *
 * There are several standard standard subclasses provided
 * by Analysis subpackage.
 */
abstract class AbstractCommon extends Analysis\Analyzer\AbstractAnalyzer
{
    /**
     * The set of Token filters applied to the Token stream.
     * Array of Search\Analysis\TokenFilter\TokenFilterInterface objects.
     *
     * @var array
     */
    private $_filters = array();

    /**
     * Add Token filter to the AnalyzerInterface
     *
     * @param Search\Analysis\TokenFilter\TokenFilterInterface $filter
     */
    public function addFilter(TokenFilterInterface $filter)
    {
        $this->_filters[] = $filter;
    }

    /**
     * Apply filters to the token. Can return null when the token was removed.
     *
     * @param Search\Analysis\Token $token
     * @return Search\Analysis\Token
     */
    public function normalize(Analysis\Token $token)
    {
        foreach ($this->_filters as $filter) {
            $token = $filter->normalize($token);

            // resulting token can be null if the filter removes it
            if ($token === null) {
                return null;
            }
        }

        return $token;
    }
}
