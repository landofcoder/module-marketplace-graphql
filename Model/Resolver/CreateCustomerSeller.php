<?php
declare(strict_types=1);

namespace Lof\MarketplaceGraphQl\Model\Resolver;

use Magento\Customer\Api\Data\AddressInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\ResourceModel\Customer\Collection as CustomerCollection;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Catalog\Model\Product\Url;


/**
 * Class CreateCustomerSeller
 * @package Lof\MarketplaceGraphQl\Model\Resolver
 */
class CreateCustomerSeller implements ResolverInterface
{

    /**
     * @var GetCustomer
     */
    private $getCustomer;
    /**
     * @var DataProvider\CreateSeller
     */
    private $_createSeller;
    /**
     * @var Url
     */
    private $url;
    /**
     * @var CustomerInterface
     */
    private $customerInterface;
    /**
     * @var AddressInterface
     */
    private $addressInterface;
    /**
     * @var CustomerCollection
     */
    private $customerCollection;

    public function __construct(
        DataProvider\CreateSeller $createSeller,
        GetCustomer $getCustomer,
        CustomerInterface $customerInterface,
        AddressInterface $addressInterface,
        CustomerCollection $customerCollection,
        Url $url
    ) {
        $this->_createSeller = $createSeller;
        $this->getCustomer = $getCustomer;
        $this->customerInterface = $customerInterface;
        $this->addressInterface = $addressInterface;
        $this->customerCollection = $customerCollection;
        $this->url = $url;
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
    ) {
        if (!($args['input']) || !isset($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }
        $args = $args['input'];
        $customer = $args['customer'];
        $data = $args['seller'];
        $password = $args['password'];
        $address = $customer['address'];

        $customerCollection = $this->customerCollection->addFieldToFilter('email', $customer['email']);
        if ($customerCollection->getData()){
            throw new GraphQlInputException(__('A customer with the same email address already exists in an associated website.'));
        }

        $data['url_key'] = $this->url->formatUrlKey($data['url_key']);
        $data['group'] = $data['group_id'];
        $addressInterface = $this->addressInterface;
        $addressInterface->setCountryId($address['country_id'])
            ->setCity($address['city']);
        if (isset($address['region_id'])) {
            $addressInterface->setRegionId($address['region_id']);
        }
        $addressArr[] = $addressInterface;
        $customerInterface = $this->customerInterface;
        $customerInterface->setFirstname($customer['firstname'])
            ->setLastname($customer['lastname'])
            ->setEmail($customer['email'])
            ->setAddresses($addressArr);
        return $this->_createSeller->registerSeller($customerInterface, $data, $password);
    }


}
