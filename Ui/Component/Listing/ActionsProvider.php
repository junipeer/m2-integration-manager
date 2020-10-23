<?php

// declare(strict_types=1);

namespace Junipeer\IntegrationManager\Ui\Component\Listing;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\FilterGroupFactory;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider;
use Magento\Framework\UrlInterface;


/**
 * Class ActionsProvider
 * @package Junipeer\IntegrationManager\Ui\Component\Listing
 */
class ActionsProvider extends DataProvider
{

    protected $urlBuilder;

    /**
     * @var ActionRepositoryInterface $actionRepository
     */
    protected $actionRepository;

    /** @var FilterGroupFactory $filterGroupFactory */
    protected $filterGroupFactory;

    /**
     * Data repository
     *
     * @var IntegrationRepositoryInterface
     */
    protected $integrationRepository;

    protected $_cachedIntegrationNames = [];

    /**
     * ActionsProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param ReportingInterface $reporting
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ActionRepositoryInterface $actionRepository
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
        $id = $this->request->getParam("id");
        if (!$id) {
            return [];
        }

        $filters = [$this->filterBuilder->create()->setField("integration_id")->setValue($id)];
        $filterGroup = $this->filterGroupFactory->create();
        $filterGroup->setFilters($filters);

        $search = $this->searchCriteriaBuilder->create();
        $search->setFilterGroups([$filterGroup]);
        $items = $this->actionRepository->getList($search);
        $ret = [];
        foreach ($items->getItems() as $item) {
            if (!isset($this->_cachedIntegrationNames[$item->getIntegrationId()])) {
               $int = $this->integrationRepository->getById($item->getIntegrationId());
               if ($int->getId()) {
                   $this->_cachedIntegrationNames[$item->getIntegrationId()] = $int->getName();
               } else {
                   $this->_cachedIntegrationNames[$item->getIntegrationId()] = '';
               }
            }

            $ret[] = [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'entity_type' => $item->getEntityType(),
                'integration' => $this->_cachedIntegrationNames[$item->getIntegrationId()]
            ];
        }

        return [
            'items' => $ret,
            'totalRecords' => count($ret)
        ];
    }
}
