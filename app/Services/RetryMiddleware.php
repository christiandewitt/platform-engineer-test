<?php declare(strict_types=1);

namespace App\Services;

use GuzzleHttp\Exception\
{
    ConnectException,
    RequestException
};
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Response as StatusCode;

class RetryMiddleware
{
    /**
     * Creates the retry middleware stack.
     *
     * @param integer $maxRetries
     * @param integer $delay
     * @return HandlerStack
     */
    public static function create(int $maxRetries, int $delay): HandlerStack
    {
        $stack = HandlerStack::create(new CurlHandler());
        $stack->push(Middleware::retry(self::retryDecider($maxRetries), self::retryDelay($delay)));

        return $stack;
    }

    /**
     * Determines the retry strategy.
     *
     * @param int $maxRetries
     * @return callable
     */
    private static function retryDecider(int $maxRetries): callable
    {
        return function (
            $retries,
            Request $request,
            Response $response = null,
            RequestException $exception = null
        ) use ($maxRetries) {
            // Limit the number of retries to the max set
            if ($retries >= $maxRetries) {
                return false;
            }

            // Retry connection exceptions
            if ($exception instanceof ConnectException) {
                return true;
            }

            if ($response) {
                // Retry on server errors
                if ($response->getStatusCode() >= StatusCode::HTTP_INTERNAL_SERVER_ERROR ) {
                    return true;
                }
            }

            return false;
        };
    }

    /**
     * Determines the delay strategy.
     *
     * @param integer $delay
     * @return callable
     */
    private static function retryDelay(int $delay): callable
    {
        return function($numberOfRetries) use ($delay) {
            return $delay * $numberOfRetries;
        };
    }
}