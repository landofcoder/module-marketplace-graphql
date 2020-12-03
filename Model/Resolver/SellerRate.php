<?php
namespace Lof\MarketplaceGraphQl\Model\Resolver;

use Lof\MarketPlace\Api\SellerProductsRepositoryInterface;
use Lof\MarketPlace\Api\SellersFrontendRepositoryInterface;
use Lof\MarketPlace\Api\SellerRatingsRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
/**
 * Class SellerRate
 *
 * @package Lof\MarketplaceGraphQl\Model\Resolver
 */
class SellerRate extends AbstractSellerQuery implements ResolverInterface {
    /**
     * @var ProductRepository
     */
    private $product;
    /**
     * @var Collection
     */
    private $productCollection;
    /**
     * @var SellerRatingsRepositoryInterface
     */
    private $sellerRate;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SellersFrontendRepositoryInterface $seller,
        SellerProductsRepositoryInterface $productSeller,
        ProductRepositoryInterface $productRepository,
        SellerRatingsRepositoryInterface $sellerRate,
        ProductRepository $product,
        Collection $collection
    )
    {
        $this->product = $product;
        $this->productCollection = $collection;
        $this->sellerRate = $sellerRate;
        parent::__construct($searchCriteriaBuilder, $seller, $productSeller, $productRepository);
    }

    /**
     * @inheritDoc
     */
    public function resolve( Field $field, $context, ResolveInfo $info, array $value = null, array $args = null )
    {
        if (!isset($args['seller_id'])) {
            throw new GraphQlInputException(
                __("'seller_id' input argument is required.")
            );
        }

        return $this->sellerRate->getSellerRatings($args['seller_id']);
    }
}
