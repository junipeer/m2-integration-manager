<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Events;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\ActionInterface;
use Junipeer\IntegrationManager\Api\Data\EventInterface;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Manager;
use Magento\Framework\Api\DataObjectHelper;
use Junipeer\IntegrationManager\Api\EventRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\EventInterfaceFactory;
use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

class Save extends Integration
{
    // TODO load from Events Source
    protected $validEvents = [
        'Order' => [
            'order.created',
        ],
        'Customer' => [
            'customer.created',
            'customer.created.admin',
            'customer.signUp',
            'customer.deleted',
        ],
        'Product' => [
            'product.created',
            'product.updated',
            'product.deleted',
        ],
    ];



    /**
     * @var Manager
     */
    protected $messageManager;

    /**
     * @var EventInterfaceFactory
     */
    protected $eventFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    public function __construct(
        Registry $registry,
        EventRepositoryInterface $eventRepository,
        ActionRepositoryInterface $actionRepositoryInterface,
        IntegrationRepositoryInterface $integrationRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Manager $messageManager,
        EventInterfaceFactory $eventFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context
    ) {
        $this->messageManager   = $messageManager;
        $this->eventFactory      = $eventFactory;
        $this->dataObjectHelper  = $dataObjectHelper;
        parent::__construct($registry, $integrationRepository, $actionRepositoryInterface, $eventRepository, $resultPageFactory, $resultForwardFactory, $context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model = $this->eventRepository->getById($id);
            } else {
                unset($data['id']);
                $model = $this->eventFactory->create();
            }

            if (empty($data['action_id'])) {
                $this->messageManager->addErrorMessage("action id not found");
                return $resultRedirect->setPath('*/*/');
            }

            /** @var ActionInterface $loadParent */
            $loadParent = $this->actionRepositoryInterface->getById($data['action_id']);
            if (!$loadParent->getId()) {
                $this->messageManager->addErrorMessage("action id not found");
                return $resultRedirect->setPath('*/*/');
            }

            if (empty($data['event']) || !is_string($data['event']) || !in_array($data['event'],$this->validEvents[$loadParent->getEntityType()])) {
                $this->messageManager->addErrorMessage("The select action can't use this Event. Make sure the event matches the action event type.");
                return $resultRedirect->setPath('*/*/');
            }

            $data['integration_id'] = $loadParent->getIntegrationId();

            try {
                $this->dataObjectHelper->populateWithArray($model, $data, EventInterface::class);
                $this->eventRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved this event.'));
                $this->_getSession()->setFormData(false);
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the event.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
