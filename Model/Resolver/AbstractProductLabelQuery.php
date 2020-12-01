<?php
/**
 * Landofcoder
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Landofcoder.com license that is
 * available through the world-wide-web at this URL:
 * https://landofcoder.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Landofcoder
 * @package     Lof_MarketplaceGraphQl
 * @copyright   Copyright (c) Landofcoder (https://landofcoder.com/)
 * @license     https://landofcoder.com/LICENSE.txt
 */

declare(strict_types=1);

namespace Lof\MarketplaceGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Lof\ProductLabel\Api\LabelRepositoryInterface;

/**
 * Class AbstractProductLabelQuery
 *
 * @package Lof\MarketplaceGraphQl\Model\Resolver
 */
abstract class AbstractProductLabelQuery
{
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var LabelRepositoryInterface
     */
    protected $_labelRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * @var int
     */
    protected $_labelFlag;

    /**
     * AbstractGift constructor.
     * @param LabelRepositoryInterface $productLabel
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        LabelRepositoryInterface $productLabel,
        ProductRepositoryInterface $productRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_labelRepository = $productLabel;
        $this->_productRepository = $productRepository;
    }

    /**
     * @param array $args
     *
     * @throws GraphQlInputException
     */
    protected function validateArgs(array $args)
    {
        if ($this->_labelFlag && !isset($args['entity_id'])) {
            throw new GraphQlInputException(__('Label id is required.'));
        }
    }
}
