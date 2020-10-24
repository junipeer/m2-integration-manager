<?php
namespace Junipeer\IntegrationManager\Model\Observer;

use Magento\Framework\Event\ObserverInterface;


class CustomerCreated extends AbstractObserver implements ObserverInterface
{

    protected $entityType = 'customer.created';

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $customer = $observer->getCustomer();
        if ($customer && $customer->getId()) {
            $handler = $this->integrationApiHandlerFactory->create();
            $handler->handleCustomerEvent($this->entityType, $customer->getId(), $customer->getEmail());
        }
    }

}
