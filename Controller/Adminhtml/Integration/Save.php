<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\EventRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Message\Manager;
use Junipeer\IntegrationManager\Model\ManagerFactory;
use Magento\Framework\Api\DataObjectHelper;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Junipeer\IntegrationManager\Api\Data\IntegrationInterface;
use Junipeer\IntegrationManager\Api\Data\IntegrationInterfaceFactory;
use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;

class Save extends Integration
{
    /**
     * @var Manager
     */
    protected $messageManager;

    /**
     * @var IntegrationRepositoryInterface
     */
    protected $integrationRepository;

    /**
     * @var IntegrationInterfaceFactory
     */
    protected $integrationFactory;

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
        ActionRepositoryInterface $actionRepositoryInterface,
        EventRepositoryInterface $eventRepositoryInterface,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Manager $messageManager,
        ManagerFactory $integrationManagerFactory,

        IntegrationInterfaceFactory $integrationFactory,
        DataObjectHelper $dataObjectHelper,
        Context $context
    ) {
        $this->messageManager   = $messageManager;
        $this->integrationFactory      = $integrationFactory;
        $this->integrationRepository   = $integrationRepository;
        $this->dataObjectHelper  = $dataObjectHelper;
        $this->integrationManagerFactory = $integrationManagerFactory;
        parent::__construct($registry, $integrationRepository, $actionRepositoryInterface, $eventRepositoryInterface, $resultPageFactory, $resultForwardFactory, $context);
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
                $model = $this->integrationRepository->getById($id);
            } else {
                unset($data['id']);
                $model = $this->integrationFactory->create();
            }

            try {
                $data['name'] = $this->integrationManagerFactory->create()->getIntegrationName($data['user_integration_id']);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage("Could not load integration name from Junipeer, try again or validate your api keys.");
                $this->_getSession()->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }

            try {
                $this->dataObjectHelper->populateWithArray($model, $data, IntegrationInterface::class);
                $this->integrationRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved this integration.'));
                $this->_getSession()->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the integration.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
