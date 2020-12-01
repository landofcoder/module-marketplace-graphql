<?php
/**
 * {
    lofProductLabelById(entity_id: 1)
    {
        entity_id
        name
        status
        product_image
        }
    }
 */

namespace Lof\MarketplaceGraphQl\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * Class LabelById
 *
 * @package Lof\MarketplaceGraphQl\Model\Resolver
 */
class LabelById extends AbstractProductLabelQuery implements ResolverInterface {
    /**
     * @inheritDoc
     */
    public function resolve( Field $field, $context, ResolveInfo $info, array $value = null, array $args = null )
    {
        $this->_labelFlag = 1;
        $this->validateArgs( $args );

        return $this->_labelRepository->getById( $this->_labelFlag );
    }
}
