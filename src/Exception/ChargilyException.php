<?php
/**
 * Created by PhpStorm.
 * User: mohamed
 * Date: 12/4/18
 * Time: 12:32 PM
 */

namespace Kiakaha\ChargilyPlugin\Exception;

use Payum\Core\Exception\Http\HttpException;

final class ChargilyException extends HttpException
{
    const LABEL = 'ChargilyException';
    public static function newInstance($status)
    {
        //var_dump($status);

        $parts = [self::LABEL];
        if (property_exists($status, 'response_code')) {
            $parts[] = '[response code] ' . $status->response_code;
        }
        if (property_exists($status, 'response_message')) {
            $parts[] = '[response message] ' . $status->response_message;
        }
        if (property_exists($status, 'status')) {
            $parts[] = '[reason status] ' . $status->status;
        }
        $message = implode(PHP_EOL, $parts);
        return new static($message);
    }
}
