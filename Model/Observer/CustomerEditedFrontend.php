<?php
namespace Junipeer\IntegrationManager\Model\Observer;

use Junipeer\IntegrationManager\Model\IntegrationApiHandlerFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class CustomerEditedFrontend extends AbstractObserver implements ObserverInterface
{

    protected $entityType = "customer.updated.frontend";

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /** @var CustomerRepositoryInterface $orderRepository */
    protected $customerRepository;


    public function __construct(
        IntegrationApiHandlerFactory $integrationApiHandlerFactory,
        CustomerRepositoryInterface $customerRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;

        parent::__construct($integrationApiHandlerFactory);
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $handler = $this->integrationApiHandlerFactory->create();
        if (!$handler->isEnabled()) {
            return;
        }

        /** @var string $customerEmail */
        $customerEmail = $observer->getEmail();
        try {
            $store = $this->storeManager->getStore();
            $customer = $this->customerRepository->get($customerEmail, $store->getWebsiteId());

            $handler->handleCustomerEvent($this->entityType, $customer->getId(), $customer->getEmail());

        } catch(\Exception $e) {
            // do nothing
            return;
        }
    }

}
