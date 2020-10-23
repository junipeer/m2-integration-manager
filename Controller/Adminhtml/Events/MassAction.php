<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml\Events;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\EventRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Ui\Component\MassAction\Filter;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Junipeer\IntegrationManager\Controller\Adminhtml\Integration;
use Junipeer\IntegrationManager\Model\Event as DataModel;
use Junipeer\IntegrationManager\Model\ResourceModel\Event\CollectionFactory;

abstract class MassAction extends Integration
{
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var string
     */
    protected $successMessage;

    /**
     * @var string
     */
    protected $errorMessage;


    /**
     * MassAction constructor.
     * @param Filter $filter
     * @param Registry $registry
     * @param IntegrationRepositoryInterface $integrationRepository
     * @param ActionRepositoryInterface $actionRepositoryInterface
     * @param EventRepositoryInterface $eventRepositoryInterface
     * @param PageFactory $resultPageFactory
     * @param Context $context
     * @param CollectionFactory $collectionFactory
     * @param ForwardFactory $resultForwardFactory
     * @param $successMessage
     * @param $errorMessage
     */
    public function __construct(
        Filter $filter,
        Registry $registry,
        IntegrationRepositoryInterface $integrationRepository,
        ActionRepositoryInterface $actionRepositoryInterface,
        EventRepositoryInterface $eventRepositoryInterface,
        PageFactory $resultPageFactory,
        Context $context,
        CollectionFactory $collectionFactory,
        ForwardFactory $resultForwardFactory,
        $successMessage,
        $errorMessage
    ) {
        $this->filter               = $filter;
        $this->collectionFactory    = $collectionFactory;
        $this->successMessage       = $successMessage;
        $this->errorMessage         = $errorMessage;
        parent::__construct($registry, $integrationRepository, $actionRepositoryInterface, $eventRepositoryInterface, $resultPageFactory, $resultForwardFactory, $context);
    }

    /**
     * @param DataModel $data
     * @return mixed
     */
    abstract protected function massAction(DataModel $data);

    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $data) {
                $this->massAction($data);
            }
            $this->messageManager->addSuccessMessage(__($this->successMessage, $collectionSize));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __($this->errorMessage));
        }
        $redirectResult = $this->resultRedirectFactory->create();
        $redirectResult->setPath('junipeer_manager/events/index');
        return $redirectResult;
    }
}
