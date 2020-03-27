<?php declare(strict_types=1);

namespace App\Services\Parsers;

use App\Contracts\ParserInterface;

class ProductionServiceResponseParser extends AbstractParser implements ParserInterface
{
    /**
     * Parse the response.
     *
     * @return self
     */
    public function parse(): AbstractParser
    {
        $payload = $this->getPayload();
        $parameters = $payload['parameters'];
        $from = $parameters['from'];
        $to = $parameters['to'];

        $productions = $this
            ->sanitize()
            ->filter($from, $to)
            ->format()
            ->getPayload();

        $this->setBody([
            'count' => count($productions),
            'productions' => $productions
        ]);

        return $this;
    }

    /**
     * Sanitizes the data.
     *
     * @return AbstractParser
     */
    private function sanitize(): AbstractParser
    {
        $payload = $this->getPayload()['data']['features'];

        // Sanitize the data with unrequired keys / values
        $this->setPayload(array_map(function($production) {
            unset(
                $production['attributes']['OBJECTID'],
                $production['attributes']['IMDbLink'],
                $production['attributes']['Address'],
                $production['attributes']['OriginalDetails'],
                $production['geometry']
            );

            return [
                'title' => $production['attributes']['Title'],
                'type' => $production['attributes']['Type'],
                'site' => $production['attributes']['Site'],
                'shoot_date' => ((int) $production['attributes']['ShootDate']) / 1000
            ];
        }, $payload));

        return $this;
    }

    /**
     * Filters the data on date and removes duplicates
     *
     * @param string $from
     * @param string $to
     * @return AbstractParser
     */
    private function filter(string $from, string $to): AbstractParser
    {
        $collection = collect($this->getPayload());

        // Remove duplicates
        $unique = $collection->uniqueStrict();

        //Filter by date
        $filtered = $unique->whereBetween('shoot_date', [strtotime($from), strtotime($to)]);
        
        // Group by title, site and shoot date
        $grouped = $filtered->groupBy(['title', 'site', 'shoot_date']);
        
        $this->setPayload(
            $grouped->toArray()
        );

        return $this;
    }

    /**
     * Formats the data to the desired structure.
     *
     * @return AbstractParser
     */
    private function format(): AbstractParser
    {
        $productions = [];
        
        foreach ($this->getPayload() as $title => $sites) {
            $production = [
                'title' => $title
            ];

            foreach ($sites as $siteName => $shootDates) {
                $site = [
                    'name' => $siteName
                ];

                foreach ($shootDates as $shootDate => $productionData) {
                    $site['shoot_dates'][] = date('F j, Y', $shootDate);

                    if (!isset($production['type'])) {
                        $production['type'] = current($productionData)['type'];
                    }
                }

                $production['sites'][] = $site;
            }

            $productions[] = $production;
        }

        $this->setPayload($productions);

        return $this;
    }
}
