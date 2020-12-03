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
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
/**
 * Class SellerByProduct
 *
 * @package Lof\MarketplaceGraphQl\Model\Resolver
 */
class SellerByProduct extends AbstractSellerQuery implements ResolverInterface {
    /**
     * @var ProductRepository
     */
    private $product;
    /**
     * @var Collection
     */
    private $productCollection;

    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SellersFrontendRepositoryInterface $seller,
        SellerProductsRepositoryInterface $productSeller,
        ProductRepositoryInterface $productRepository,
        ProductRepository $product,
        Collection $collection
    )
    {
        $this->product = $product;
        $this->productCollection = $collection;
        parent::__construct($searchCriteriaBuilder, $seller, $productSeller, $productRepository);
    }

    /**
     * @inheritDoc
     */
    public function resolve( Field $field, $context, ResolveInfo $info, array $value = null, array $args = null )
    {
        if (!isset($args['product_sku'])) {
            throw new GraphQlInputException(
                __("'product_sku' input argument is required.")
            );
        }
        $products = $this->productCollection->addFieldToFilter('sku',$args['product_sku']);
        if (!count($products)) {
            throw new GraphQlInputException(
                __("product_sku does not math any product.")
            );
        }
        $productId = $this->product->get($args['product_sku'])->getId();

        return $this->_sellerRepository->getSellersbyProductID($productId);
    }
}
