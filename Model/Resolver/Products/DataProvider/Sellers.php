<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Lof\MarketplaceGraphQl\Model\Resolver\Products\DataProvider;

use Lof\MarketPlace\Api\Data\SellerInterface;
use Lof\MarketPlace\Api\Data\SellersSearchResultsInterface;
use Lof\MarketPlace\Api\Data\SellersSearchResultsInterfaceFactory;
use Lof\MarketPlace\Model\ResourceModel\Seller\CollectionFactory;
use Magento\CatalogGraphQl\DataProvider\Product\SearchCriteriaBuilder;
use Magento\CatalogGraphQl\Model\Resolver\Products\SearchResult;
use Magento\CatalogGraphQl\Model\Resolver\Products\SearchResultFactory;
use Magento\Framework\Api\Search\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\GraphQl\Model\Query\ContextInterface;
use Magento\Search\Api\SearchInterface;
use Magento\Search\Model\Search\PageSizeProvider;
use Lof\MarketplaceGraphQl\Model\Resolver\Products\Query\SellerQueryInterface;
use Lof\MarketplaceGraphQl\Model\Resolver\Products\Query\FieldSelection;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Full text search for catalog using given search criteria.
 */
class Sellers implements SellerQueryInterface
{
    /**
     * @var SearchInterface
     */
    private $search;

    /**
     * @var SearchResultFactory
     */
    private $searchResultFactory;

    /**
     * @var PageSizeProvider
     */
    private $pageSizeProvider;

    /**
     * @var FieldSelection
     */
    private $fieldSelection;

    /**
     * @var ProductSearch
     */
    private $productsProvider;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var CollectionFactory
     */
    private $_collection;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var SellersSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;
    /**
     * @var StoreManagerInterface
     */
    private $_storeManager;

    /**
     * @param SearchInterface $search
     * @param SearchResultFactory $searchResultFactory
     * @param PageSizeProvider $pageSize
     * @param FieldSelection $fieldSelection
     * @param ProductSearch $productsProvider
     * @param CollectionFactory $collection
     * @param StoreManagerInterface $storeManager
     * @param SellersSearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        SearchInterface $search,
        SearchResultFactory $searchResultFactory,
        PageSizeProvider $pageSize,
        FieldSelection $fieldSelection,
        ProductSearch $productsProvider,
        CollectionFactory $collection,
        StoreManagerInterface $storeManager,
        SellersSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->search = $search;
        $this->searchResultFactory = $searchResultFactory;
        $this->pageSizeProvider = $pageSize;
        $this->fieldSelection = $fieldSelection;
        $this->_collection = $collection;
        $this->productsProvider = $productsProvider;
        $this->_storeManager = $storeManager;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @param array $args
     * @param ResolveInfo $info
     * @param ContextInterface $context
     * @return SellerInterface|SellersSearchResultsInterface
     * @throws InputException|\Magento\Framework\Exception\NoSuchEntityException
     */
    public function getListSellers
    (
        SearchCriteriaInterface $criteria,
        array $args,
        ResolveInfo $info,
        ContextInterface $context
    ) {
        $collection = $this->_collection->create();
        $this->collectionProcessor->process($criteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $items = [];
        foreach ($collection as $val) {
            $data = $val->getData();
            if(isset($data['image']) && $data['image']){
                $data["image"] = $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . $data["image"];

                $data["thumbnail"] = $this->_storeManager->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . $data["thumbnail"];
            }
            if(isset($data['store_id']) && $data['store_id']) {
                $data['store_id'] = implode(',',$data['store_id']);
            }
            $args['seller_id'] = $val->getData('seller_id');
            $data['products'] = $this->getResult( $args, $info, $context);
            $items[] = $data;
        }
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Return product search results using Search API
     *
     * @param array $args
     * @param ResolveInfo $info
     * @param ContextInterface $context
     * @return array
     */
    public function getResult(
        array $args,
        ResolveInfo $info,
        ContextInterface $context
    ) {
        $queryFields = $this->fieldSelection->getProductsFieldSelection($info);
        $searchCriteria = $this->buildSearchCriteria($args, $info);

        $realPageSize = $searchCriteria->getPageSize();
        $realCurrentPage = $searchCriteria->getCurrentPage();
        //Because of limitations of sort and pagination on search API we will query all IDS
        $pageSize = $this->pageSizeProvider->getMaxPageSize();
        $searchCriteria->setPageSize($pageSize);
        $searchCriteria->setCurrentPage(0);
        $itemsResults = $this->search->search($searchCriteria);

        //Address limitations of sort and pagination on search API apply original pagination from GQL query
        $searchCriteria->setPageSize($realPageSize);
        $searchCriteria->setCurrentPage($realCurrentPage);
        if(isset($args['seller_id'])) {
            $searchResults = $this->productsProvider->getList(
                $searchCriteria,
                $itemsResults,
                $queryFields,
                $context,
                (int)$args['seller_id']
            );
        } else {
            $searchResults = $this->productsProvider->getList(
                $searchCriteria,
                $itemsResults,
                $queryFields,
                $context
            );
        }

        $productArray = [];
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($searchResults->getItems() as $product) {
            $productArray[$product->getId()] = $product->getData();
            $productArray[$product->getId()]['model'] = $product;
        }

        return
            [
                'total_count' => $searchResults->getTotalCount(),
                'items' => $productArray
            ];
    }

    /**
     * Build search criteria from query input args
     *
     * @param array $args
     * @param ResolveInfo $info
     * @return SearchCriteriaInterface
     */
    private function buildSearchCriteria(array $args, ResolveInfo $info): SearchCriteriaInterface
    {
        $productFields = (array)$info->getFieldSelection(1);
        $includeAggregations = isset($productFields['filters']) || isset($productFields['aggregations']);
        $searchCriteria = $this->searchCriteriaBuilder->build($args, $includeAggregations);

        return $searchCriteria;
    }
}
