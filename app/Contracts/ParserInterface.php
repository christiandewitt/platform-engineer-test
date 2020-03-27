<?php

/**
 * ParserInterface
 *
 * The interface for the request / response parser.
 */

namespace App\Contracts;

use App\Services\Parsers\AbstractParser;

interface ParserInterface
{
    public function parse(): AbstractParser;

    public function asArray(): array;

    public function getPayload();

    public function setPayload($payload): AbstractParser;

    public function setBody(array $body): AbstractParser;
}
