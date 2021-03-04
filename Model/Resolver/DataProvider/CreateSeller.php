<?php
declare(strict_types=1);

namespace Lof\MarketplaceGraphQl\Model\Resolver\DataProvider;



use Lof\MarketPlace\Api\SellersRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\State\InputMismatchException;

/**
 * Product field data provider, used for GraphQL resolver processing.
 */
class CreateSeller
{

    /**
     * @var SellersRepositoryInterface
     */
    private $sellerRepository;

    /**
     * CreateSeller constructor.
     * @param SellersRepositoryInterface $sellersRepository
     */
    public function __construct(
        SellersRepositoryInterface $sellersRepository
    ) {
        $this->sellerRepository = $sellersRepository;

    }

    /**
     * @param $data
     * @param $customerId
     * @return mixed
     * @throws LocalizedException
     */
    public function createSeller($data, $customerId){
        return $this->sellerRepository->saveSeller($data, $customerId);
    }

    /**
     * @param CustomerInterface $customer
     * @param $data
     * @param $password
     * @return CustomerInterface
     * @throws InputException
     * @throws LocalizedException
     * @throws InputMismatchException
     */
    public function registerSeller(CustomerInterface $customer, $data, $password){
        return $this->sellerRepository->registerNewSeller($customer, $data, $password);
    }
}
