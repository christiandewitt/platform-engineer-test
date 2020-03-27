<?php declare(strict_types=1);

namespace App\Services;

use App\Contracts\ParserInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Response as StatusCode;

abstract class AbstractService
{
    /**
     * Reference to the HTTP client.
     *
     * @var Client
     */
    private $client;

    /**
     * Reference to the response parser
     *
     * @var ParserInterface
     */
    private $responseParser = null;

    /**
     * Initializes the service.
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config = [])
    {
        $config = array_merge([
            'handler' => RetryMiddleware::create(
                $config['max_retries'] ?? 0,
                $config['retry_delay'] ?? 0
            )
        ], $config);

        $this->client = new Client($config);
    }

    /**
     * Perform request.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $options
     * @return Request
     */
    private function request(string $method, string $endpoint, array $options = []): Request
    {
        return new Request($method, $endpoint, $options);
    }

    /**
     * Performs GET.
     *
     * @param mixed $endpoint
     * @param array $options
     * @return array
     */
    public function get($endpoint, array $options = []): array
    {
        $parameters = $options['request'] ?? [];
        $request = $this->request('GET', $endpoint, $parameters);

        $response = $this->client->send($request);

        return $this->response($response, $parameters);
    }

    /**
     * Handles the response and invokes the response
     * parser if specified.
     *
     * @param Response $response
     * @param array $parameters
     * @return array
     */
    private function response(Response $response, array $parameters): array
    {
        $data = [];

        if ($response instanceof Response && $response->getStatusCode() == StatusCode::HTTP_OK) {
            $contentType = current($response->getHeader('Content-Type'));
            $responseBody = (string) $response->getBody();

            switch ($contentType) {
                case 'application/json':
                    $data = json_decode($responseBody, true);
                    break;
                default:
                    $data = $responseBody;
            }
        }

        if ($this->responseParser !== null) {
            $data = $this->responseParser
                ->setPayload([
                    'parameters' => $parameters,
                    'data' => $data
                ])
                ->parse()
                ->asArray();
        }

        return $data;
    }

    /**
     * Set reference to the response parser
     *
     * @param ParserInterface $responseParser  Reference to the response parser
     *
     * @return self
     */ 
    public function setResponseParser(ParserInterface $responseParser)
    {
        $this->responseParser = $responseParser;

        return $this;
    }
}
