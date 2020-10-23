<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Action;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\IntegrationInterface;
use Junipeer\IntegrationManager\Api\EventRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\ActionInterfaceFactory;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Manager;
use Junipeer\IntegrationManager\Model\ManagerFactory;

use Magento\Framework\Api\DataObjectHelper;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\ActionInterface;
use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

class Save extends Integration
{
    /**
     * @var Manager
     */
    protected $messageManager;


    /**
     * @var ActionInterfaceFactory
     */
    protected $actionInterfaceFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var ManagerFactory $integrationManagerFactory
     */
    protected $integrationManagerFactory;

    public function __construct(
        Registry $registry,
        IntegrationRepositoryInterface $integrationRepository,
        ActionRepositoryInterface $actionRepository,
        EventRepositoryInterface $eventRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Manager $messageManager,
        ManagerFactory $integrationManagerFactory,
        ActionInterfaceFactory $actionInterfaceFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context
    ) {
        $this->messageManager   = $messageManager;
        $this->actionInterfaceFactory = $actionInterfaceFactory;
        $this->dataObjectHelper  = $dataObjectHelper;
        $this->integrationManagerFactory = $integrationManagerFactory;

        parent::__construct($registry, $integrationRepository, $actionRepository, $eventRepository, $resultPageFactory, $resultForwardFactory, $context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();


        if (!isset($data['dynamic_rows']) || !is_array($data['dynamic_rows'])) {
            $data['dynamic_rows'] = [];
        }

        $fields = $this->fixDynamicRows($data['dynamic_rows']);
        unset($data['dynamic_rows']);
        $data['fields'] = $fields;

        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $id = $this->getRequest()->getParam('id');
            if ($id) {
                $model = $this->actionRepositoryInterface->getById($id);
            } else {
                unset($data['id']);
                $model = $this->actionInterfaceFactory->create();
            }

            if (empty($data['integration_id'])) {
                $this->messageManager->addErrorMessage("Integration id not found");
                return $resultRedirect->setPath('*/integration');
            }

            /** @var IntegrationInterface $loadParent */
            $loadParent = $this->integrationRepository->getById($data['integration_id']);
            if (!$loadParent->getId()) {
                $this->messageManager->addErrorMessage("Integration id not found");
                return $resultRedirect->setPath('*/integration');
            }

            // load name!
            try {
                $data['name'] = $this->integrationManagerFactory->create()->getIntegrationActionName($loadParent->getUserIntegrationId(), $data['action']);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage("Could not load integration action name from Junipeer, try again or update your api keys.");
                $this->_getSession()->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }

            try {
                $this->dataObjectHelper->populateWithArray($model, $data, ActionInterface::class);
                $this->actionRepositoryInterface->save($model);
                $this->messageManager->addSuccessMessage(__('You saved this action.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the action.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/integration');
    }

    /**
     * @param array $rows
     * @return string
     */
    protected function fixDynamicRows(array $rows) {
        $ret = [];
        foreach ($rows as $row) {
            if (empty($row['junipeer_field']) || empty($row['map_type'])) {
                continue;
            }

            if (!in_array($row['map_type'], ['from_entity','bool','custom'])) {
                continue;
            }

            if ($row['map_type'] === 'from_entity') {
                $row['bool_value'] = "";
                $row['custom_value'] = "";

                if (empty($row['map_value'])) {
                    continue;
                }
            }

            if ($row['map_type'] === 'bool') {
                $row['map_value'] = "";
                $row['custom_value'] = "";

                if (!isset($row['bool_value'])) {
                    $row['bool_value'] = 0;
                }

                $row['bool_value'] = (int) $row['bool_value'];
            }

            if ($row['map_type'] === 'custom') {
                $row['map_value'] = "";
                $row['bool_value'] = "";

                if (!isset($row['custom_value'])) {
                    $row['custom_value'] = "";
                }

                $row['custom_value'] = (string) $row['custom_value'];
            }

            $ret[] = $row;
        }

        return serialize($ret);
    }
}
