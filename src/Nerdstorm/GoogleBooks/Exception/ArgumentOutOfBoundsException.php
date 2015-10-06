<?php

namespace Nerdstorm\GoogleBooks\Exception;

use Nerdstorm\GoogleBooks\GoogleBooksExceptionInterface;

class ArgumentOutOfBoundsException extends \OutOfRangeException implements GoogleBooksExceptionInterface
{

}