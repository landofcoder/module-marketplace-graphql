<?php
namespace Lof\MarketplaceGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class ProductBySellerId
 *
 * @package Lof\MarketplaceGraphQl\Model\Resolver
 */
class ProductBySellerId extends AbstractSellerQuery implements ResolverInterface {
    /**
     * @inheritDoc
     */
    public function resolve( Field $field, $context, ResolveInfo $info, array $value = null, array $args = null )
    {
        $this->_labelFlag = 1;
        $this->validateArgs( $args );

        return  $this->_productSeller->getSellerProducts( $args['seller_id']);
    }
}
