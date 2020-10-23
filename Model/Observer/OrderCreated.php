<?php
namespace Junipeer\IntegrationManager\Model\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderCreated extends AbstractObserver implements ObserverInterface
{
    protected $entityType = "order.created";


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $orderIds = $observer->getOrderIds();
        $orderIds = is_array($orderIds) ? $orderIds : [];

        if (count($orderIds) === 0) {
            $orderId = $observer->getOrderId(); // ??? does this work
            if ($orderId) {
                $orderIds = [$orderId];
            }
        }

        $handler = $this->integrationApiHandlerFactory->create();
        foreach ($orderIds as $id) {
            $handler->handleOrderEvent($this->entityType, $id);
        }
    }
}
