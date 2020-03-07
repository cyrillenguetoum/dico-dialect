<?php
declare(strict_types=1);

namespace Search\Exception;

class UnsupportedMethodCallException
    extends \BadMethodCallException
    implements ExceptionInterface
{}
