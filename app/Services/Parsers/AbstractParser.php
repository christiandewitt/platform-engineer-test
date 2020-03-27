<?php declare(strict_types=1);

namespace App\Services\Parsers;

use App\Contracts\ParserInterface;

abstract class AbstractParser implements ParserInterface
{
    /**
     * Reference to the payload.
     *
     * @var mixed
     */
    private $payload = null;

    /**
     * Reference to the body.
     *
     * @var array
     */
    private $body = [];

    /**
     * Parse the response.
     *
     * @return self
     */
    public function parse(): AbstractParser
    {
        $this->setBody($this->payload);

        return $this;
    }

    public function asArray(): array
    {
        return $this->body;
    }

    /**
     * Set reference to the payload.
     *
     * @param mixed $payload  Reference to the payload.
     *
     * @return self
     */
    public function setPayload($payload): AbstractParser
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Get reference to the payload.
     *
     * @return mixed
     */ 
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Set reference to the response.
     *
     * @param array $body  Reference to the response.
     *
     * @return self
     */ 
    public function setBody(array $body): AbstractParser
    {
        $this->body = $body;

        return $this;
    }
}
