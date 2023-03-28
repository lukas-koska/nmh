<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Product;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

class ElasticSearchService
{
    public function __construct(
        private PaginatedFinderInterface $finder
    )
    {
        // Nothing to do here
    }

    /**
     * Get results from elastic search
     * This method should be a more complex and should use paginator
     * @param array $data
     * @return ?array<Product>
     */
    public function getSearchResultFromElastic(?array $data) : ?array
    {
        // Next row is only for developing and test purposes
        $searchString = implode(' ', $data);
        $results = $this->finder->find($searchString);

        //dd($results);     // dump results for develop purposes and die
        return $results;
    }
}
