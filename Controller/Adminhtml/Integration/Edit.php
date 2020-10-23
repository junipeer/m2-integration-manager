<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

class Edit extends Integration
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $integrationId = $this->getRequest()->getParam('id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Junipeer_IntegrationManager::integration')
            ->addBreadcrumb(__('Integration'), __('Integration'))
            ->addBreadcrumb(__('Manage Integrations'), __('Manage Integrations'));

        if ($integrationId === null) {
            $resultPage->addBreadcrumb(__('New Integration'), __('New Integration'));
            $resultPage->getConfig()->getTitle()->prepend(__('New Integration'));
        } else {
            $resultPage->addBreadcrumb(__('Edit Integration'), __('Edit Integration'));
            $resultPage->getConfig()->getTitle()->prepend(
                $this->integrationRepository->getById($integrationId)->getName()
            );
        }

        if ($integrationId) {
            $this->coreRegistry->register('integration_id', $integrationId);
        }

        return $resultPage;
    }
}
