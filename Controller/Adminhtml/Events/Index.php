<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Events;

use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

class Index extends Integration
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Junipeer::integrationmanager_integration');
        $resultPage->getConfig()->getTitle()->prepend(__('Events / Webhooks'));

        return $resultPage;
    }
}
