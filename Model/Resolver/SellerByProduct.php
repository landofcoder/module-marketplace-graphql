<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/terms
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category   Landofcoder
 * @package    Lof_MarketplaceGraphQl
 * @copyright  Copyright (c) 2021 Landofcoder (https://www.landofcoder.com/)
 * @license    https://landofcoder.com/terms
 */

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

        $sellerData = $this->_sellerRepository->getSellersbyProductID($productId);

        if($sellerData){
            $products = $sellerData->getProducts();
            if($items = $products->getItems()){
                $productArray = [];
                /** @var \Magento\Catalog\Model\Product $product */
                foreach ($items as $product) {
                    $productArray[$product->getId()] = $product->load($product->getId())->getData();
                    $productArray[$product->getId()]['model'] = $product;
                }

                $newProducts =[
                                'total_count' => $products->getTotalCount(),
                                'items' => $productArray
                                ];
                $sellerData->setProducts($newProducts);
            }
        }
        return $sellerData?$sellerData->__toArray():[];
    }
}
