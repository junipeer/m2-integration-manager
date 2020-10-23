<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Action;

use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Delete extends Integration
{
    /**
     * Delete the data entity
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $actionId = $this->getRequest()->getParam('id');
        if ($actionId) {

            $loadAction = $this->actionRepositoryInterface->getById($actionId);
            $this->eventRepository->deleteByIdActionId($actionId);

            if (!$loadAction->getId()) {
                $this->messageManager->addErrorMessage("Action not found");
                return $resultRedirect->setPath('*/integration');
            }

            $parentId = $loadAction->getIntegrationId();

            try {
                $this->actionRepositoryInterface->deleteById($actionId);
                $this->messageManager->addSuccessMessage(__('The action has been deleted.'));
                $resultRedirect->setPath('junipeer_manager/integration/edit', ['id' => $parentId]);
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The action no longer exists.'));
                return $resultRedirect->setPath('junipeer_manager/integration/edit', ['id' => $parentId]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('junipeer_manager/integration/edit', ['id' => $parentId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the integration'));
                return $resultRedirect->setPath('junipeer_manager/integration/edit', ['id' => $parentId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the action to delete.'));
        $resultRedirect->setPath('junipeer_manager/integration/index');
        return $resultRedirect;
    }
}
