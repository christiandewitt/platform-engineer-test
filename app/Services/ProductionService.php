<?php declare(strict_types=1);

namespace App\Services;

use App\Services\Parsers\ProductionServiceResponseParser;

final class ProductionService extends AbstractService
{
    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::initialize($config);
        
        $this->setResponseParser(new ProductionServiceResponseParser);
    }

    /**
     * Retrieves the productions from the given endpoint.
     *
     * @param string $from
     * @param string $to
     * @return void
     */
    public function getProductions(string $from, string $to)
    {
        $data = $this->get('/film-locations-json-all-records_03-19-2020.json', [
            'request' => [
                'from' => $from,
                'to' => $to
            ]
        ]);

        return $data;
    }
}