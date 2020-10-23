<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

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
        $integrationId = $this->getRequest()->getParam('id');
        if ($integrationId) {
            try {
                $this->integrationRepository->deleteById($integrationId);
                $this->actionRepositoryInterface->deleteByIdIntegrationId($integrationId);
                $this->eventRepository->deleteByIdIntegrationId($integrationId);

                $this->messageManager->addSuccessMessage(__('The integration has been deleted.'));
                $resultRedirect->setPath('junipeer_manager/integration/index');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The integration no longer exists.'));
                return $resultRedirect->setPath('junipeer_manager/integration/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('junipeer_manager/integration/index', ['id' => $integrationId]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the integration'));
                return $resultRedirect->setPath('junipeer_manager/integration/edit', ['id' => $integrationId]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the integration to delete.'));
        $resultRedirect->setPath('junipeer_manager/integration/index');
        return $resultRedirect;
    }
}
