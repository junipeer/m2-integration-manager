<?php

// declare(strict_types=1);

namespace Junipeer\IntegrationManager\Ui\Component\Listing;

use Junipeer\IntegrationManager\Api\EventRepositoryInterface;
use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupFactory;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Framework\UrlInterface;


/**
 * Class ActionsProvider
 * @package Junipeer\IntegrationManager\Ui\Component\Listing
 */
class EventsProvider extends DataProvider
{

    protected $urlBuilder;

    /**
     * @var ActionRepositoryInterface $actionRepository
     */
    protected $actionRepository;

    /**
     * @var EventRepositoryInterface $eventRepository
     */
    protected $eventRepository;

    /** @var FilterGroupFactory $filterGroupFactory */
    protected $filterGroupFactory;

    /**
     * Data repository
     *
     * @var IntegrationRepositoryInterface
     */
    protected $integrationRepository;

    protected $_cachedIntegrationNames = [];
    protected $_cachedActions = [];

    /**
     * ActionsProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ActionRepositoryInterface $actionRepository
     * @param EventRepositoryInterface $eventRepository
     * @param IntegrationRepositoryInterface $integrationRepository
     * @param RequestInterface $request
     * @param FilterBuilder $filterBuilder
     * @param FilterGroupFactory $filterGroupFactory
     * @param UrlInterface $urlBuilder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ActionRepositoryInterface $actionRepository,
        EventRepositoryInterface $eventRepository,
        IntegrationRepositoryInterface $integrationRepository,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        FilterGroupFactory $filterGroupFactory,
        UrlInterface $urlBuilder,
        array $meta = [],
        array $data = []
    ) {
        $this->request = $request;
        $this->filterBuilder = $filterBuilder;
        $this->name = $name;
        $this->primaryFieldName = $primaryFieldName;
        $this->requestFieldName = $requestFieldName;
        $this->filterGroupFactory = $filterGroupFactory;
        $this->reporting = $reporting;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->meta = $meta;
        $this->data = $data;

        $this->integrationRepository = $integrationRepository;
        $this->actionRepository = $actionRepository;
        $this->eventRepository = $eventRepository;

        $this->urlBuilder = $urlBuilder;


        $this->prepareUpdateUrl();

        return parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }


    public function getData()
    {
        //$filterGroup = $this->filterGroupFactory->create();
        //$filterGroup->setFilters([]);

        $search = $this->searchCriteriaBuilder->create();



        //$search->setFilterGroups([$filterGroup]);
        try {
            $items = $this->eventRepository->getList($search);
        } catch (LocalizedException $e) {
            return [];
        }


        $ret = [];
        foreach ($items->getItems() as $item) {
            if (!isset($this->_cachedActions[$item->getActionId()])) {
                $act = $this->actionRepository->getById($item->getActionId());
                if ($act->getId()) {
                    $this->_cachedActions[$item->getActionId()] = $act;
                }
            }
        }

        foreach ($this->_cachedActions as $action) {
            if (!isset($this->_cachedIntegrationNames[$action->getIntegrationId()])) {
                $int = $this->integrationRepository->getById($action->getIntegrationId());
                if ($int->getId()) {
                    $this->_cachedIntegrationNames[$action->getIntegrationId()] = $int->getName();
                } else {
                    $this->_cachedIntegrationNames[$action->getIntegrationId()] = '';
                }
            }
        }

        foreach ($items->getItems() as $item) {
           $actionName = "";
           $integrationName = "";

           if (isset($this->_cachedActions[$item->getActionId()])) {
               $action = $this->_cachedActions[$item->getActionId()];
               $actionName = $action->getName();

               if (isset($this->_cachedIntegrationNames[$action->getIntegrationId()])) {
                   $integrationName = $this->_cachedIntegrationNames[$action->getIntegrationId()];
               }
           }

            $ret[] = [
                'id' => $item->getId(),
                'event' => $item->getevent(),
                'integration' => $integrationName,
                'action' => $actionName,
                'created_at' => $item->getCreatedAt(),
                'updated_at' => $item->getUpdatedAt(),
            ];
        }

        return [
            'items' => $ret,
            'totalRecords' => count($ret)
        ];
    }
}
