<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Events;

use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

class Add extends Integration
{

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Junipeer_IntegrationManager::integration')
            ->addBreadcrumb(__('Integration'), __('Integration'))
            ->addBreadcrumb(__('Manage Events / Webhooks'), __('Manage Events / Webhooks'))
            ->addBreadcrumb(__('New Event / Webhook'), __('New Event / Webhook'))
            ->getConfig()->getTitle()->prepend(__('New Event / Webhook'));

        return $resultPage;
    }
}
