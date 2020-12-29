<?php
namespace Lof\MarketplaceGraphQl\Model\Resolver\Products\Query;

use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;

/**
 * Search for products by criteria
 */
interface SellerQueryInterface
{
    /**
     * Get product search result
     *
     * @param SearchCriteriaInterface $criteria
     * @param array $args
     * @param ResolveInfo $info
     * @param ContextInterface $context
     * @return mixed
     */
    public function getListSellers(SearchCriteriaInterface $criteria, array $args, ResolveInfo $info, ContextInterface $context);

}
