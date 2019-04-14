<?php

namespace Lyx\Micro\Tools;

use Phalcon\Exception;

/**
 * 自定义异常
 * Class CustomException
 * @package App\Common
 */
class CustomException extends Exception
{
    /**
     * CustomException constructor.
     * @param int $code
     * @param string $msg
     */
    public function __construct(int $code, string $msg)
    {
        parent::__construct($msg, $code);
    }
}
