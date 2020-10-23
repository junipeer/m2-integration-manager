<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Action;

use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

class Add extends Integration
{

    /**
     * @return \Magento\Framework\View\Result\Page|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultRedirect = $this->resultRedirectFactory->create();

        $integrationId = $this->getRequest()->getParam('integration_id');
        if (!$integrationId) {
            $resultRedirect->setPath('*/*/');
            return $resultRedirect;
        }

        $integration = $this->integrationRepository->getById($integrationId);
        if ($integration->getId()) {
            $this->coreRegistry->register("junipeer_user_integration_id", $integration->getUserIntegrationId());
        }


        $this->coreRegistry->register("integration_id", $integrationId);


        $resultPage
            ->setActiveMenu('Junipeer_IntegrationManager::integration')
            ->addBreadcrumb(__('Integration'), __('Integration'))
            ->addBreadcrumb(__('Manage Integrations'), __('Manage Integrations'))
            ->addBreadcrumb(__('Integration'), __('Integration'))
            ->getConfig()->getTitle()->set(__('New Action'));

        return $resultPage;
    }
}
