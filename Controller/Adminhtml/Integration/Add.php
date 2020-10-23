<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

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
            ->addBreadcrumb(__('Manage Integrations'), __('Manage Integrations'))
            ->addBreadcrumb(__('New Integration'), __('New Integration'))
            ->getConfig()->getTitle()->prepend(__('New Integration'));

        return $resultPage;
    }
}
