<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Events;

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
        $eventId = $this->getRequest()->getParam('id');
        if ($eventId) {
            try {
                $this->eventRepository->deleteById($eventId);

                $this->messageManager->addSuccessMessage(__('The event has been deleted.'));
                $resultRedirect->setPath('junipeer_manager/events/index');
                return $resultRedirect;
            } catch (NoSuchEntityException $e) {
                $this->messageManager->addErrorMessage(__('The event no longer exists.'));
                return $resultRedirect->setPath('junipeer_manager/events/index');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('junipeer_manager/events/index');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was a problem deleting the event'));
                return $resultRedirect->setPath('junipeer_manager/events/index');
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find the event to delete.'));
        $resultRedirect->setPath('junipeer_manager/events/index');
        return $resultRedirect;
    }
}
