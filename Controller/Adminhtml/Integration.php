<?php

namespace Junipeer\IntegrationManager\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\EventRepositoryInterface;

abstract class Integration extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ACTION_RESOURCE = 'Junipeer_IntegrationManager::integration';

    /**
     * Data repository
     *
     * @var IntegrationRepositoryInterface
     */
    protected $integrationRepository;

    /**
     * Data repository
     *
     * @var ActionRepositoryInterface
     */
    protected $actionRepositoryInterface;

    /**
     * Data repository
     *
     * @var EventRepositoryInterface
     */
    protected $eventRepository;


    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Result Page Factory
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Result Forward Factory
     *
     * @var ForwardFactory
     */
    protected $resultForwardFactory;


    /**
     * Integration constructor.
     * @param Registry $registry
     * @param IntegrationRepositoryInterface $integrationRepository
     * @param ActionRepositoryInterface $actionRepositoryInterface
     * @param EventRepositoryInterface $eventRepository
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Context $context
     */
    public function __construct(
        Registry $registry,
        IntegrationRepositoryInterface $integrationRepository,
        ActionRepositoryInterface $actionRepositoryInterface,
        EventRepositoryInterface $eventRepository,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Context $context
    ) {
        $this->coreRegistry         = $registry;
        $this->integrationRepository       = $integrationRepository;
        $this->actionRepositoryInterface       = $actionRepositoryInterface;
        $this->eventRepository = $eventRepository;
        $this->resultPageFactory    = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        parent::__construct($context);
    }
}
