<?php

namespace Lof\MarketplaceGraphQl\Model\Resolver;

use Lof\MarketPlace\Api\SellersFrontendRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class to resolve custom attribute_set_name field in product GraphQL query
 */
class ProductAttributeSetSellerResolver implements ResolverInterface
{

    /**
     * @var SellersFrontendRepositoryInterface
     */
    private $_sellerRepository;

    /**
     * ProductAttributeSetSellerResolver constructor.
     * @param SellersFrontendRepositoryInterface $sellerRepository
     */
    public function __construct(
        SellersFrontendRepositoryInterface $sellerRepository
)
    {
        $this->_sellerRepository = $sellerRepository;
    }

    /**
     * @param Field $field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array|\Magento\Framework\GraphQl\Query\Resolver\Value|mixed|string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (isset($value['entity_id']) && $value['entity_id']) {
            return $this->_sellerRepository->getSellersbyProductID($value['entity_id']);
        } else {
            return [];
        }
    }
}
