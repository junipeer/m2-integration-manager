<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Action;

use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;
use Junipeer\IntegrationManager\Model\Action;

class Edit extends Integration
{
    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {

        $actionId = $this->getRequest()->getParam('id');
        $act = null;
        if ($actionId !== null) {
            /** @var Action $act */
            $act = $this->actionRepositoryInterface->getById($actionId);
            $this->coreRegistry->register("action_entity_type", $act->getEntityType());
            $this->coreRegistry->register('action_id', $actionId);

            $this->coreRegistry->register("junipeer_action", $act->getAction());

            $integration = $this->integrationRepository->getById($act->getIntegrationId());
            if ($integration->getId()) {
                $this->coreRegistry->register("junipeer_user_integration_id", $integration->getUserIntegrationId());
            }

            $this->coreRegistry->register("integration_id", $act->getIntegrationId());
        }

        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Junipeer_IntegrationManager::integration')
            ->addBreadcrumb(__('Integration'), __('Integration'))
            ->addBreadcrumb(__('Manage Integrations'), __('Manage Integrations'));


        $resultPage->addBreadcrumb(__('Edit Action'), __('Edit Action'));
        $resultPage->getConfig()->getTitle()->prepend(
            $act->getName()
        );




        return $resultPage;
    }
}
