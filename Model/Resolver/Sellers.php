<?php

namespace Lof\MarketplaceGraphQl\Model\Resolver;

use Lof\MarketPlace\Api\SellerProductsRepositoryInterface;
use Lof\MarketPlace\Api\SellersFrontendRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class Sellers
 *
 * @package Lof\MarketplaceGraphQl\Model\Resolver
 */
class Sellers extends AbstractSellerQuery implements ResolverInterface {

    /**
     * @var SellersFrontendRepositoryInterface
     */
    private $sellers;

    /**
     * Sellers constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SellersFrontendRepositoryInterface $seller
     * @param SellerProductsRepositoryInterface $productSeller
     * @param ProductRepositoryInterface $productRepository
     * @param Products\Query\SellerQueryInterface $sellers
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SellersFrontendRepositoryInterface $seller,
        SellerProductsRepositoryInterface $productSeller,
        ProductRepositoryInterface $productRepository,
        Products\Query\SellerQueryInterface $sellers
    )
    {
        $this->sellers = $sellers;
        parent::__construct($searchCriteriaBuilder, $seller, $productSeller, $productRepository);
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        if ($args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }
        if ($args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
        $searchCriteria = $this->searchCriteriaBuilder->build( 'lof_marketplace_seller', $args );
        $searchCriteria->setCurrentPage( $args['currentPage'] );
        $searchCriteria->setPageSize( $args['pageSize'] );

        $searchResult = $this->sellers->getListSellers($searchCriteria, $args, $info, $context);

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items'       => $searchResult->getItems(),
        ];
    }
}
