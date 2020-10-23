<?php
namespace Junipeer\IntegrationManager\Model\Source\Event;

use Junipeer\IntegrationManager\Api\ActionRepositoryInterface;
use Junipeer\IntegrationManager\Api\IntegrationRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;

class Actions implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var ActionRepositoryInterface
     */
    protected $actionRepositoryInterface;

    /**
     * @var IntegrationRepositoryInterface
     */
    protected $integrationRepositoryInterface;

    /**
     * @var array
     */
    protected $_cachedIntegrationNames = [];


    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;


    /**
     * Actions constructor.
     * @param ActionRepositoryInterface $actionRepositoryInterface
     * @param IntegrationRepositoryInterface $integrationRepositoryInterface
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        ActionRepositoryInterface $actionRepositoryInterface,
        IntegrationRepositoryInterface $integrationRepositoryInterface,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->actionRepositoryInterface = $actionRepositoryInterface;
        $this->integrationRepositoryInterface = $integrationRepositoryInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }


    /**
     * Quick search actions
     *
     * @var int[]
     * @since 100.1.0
     */
    protected $fields = [];

    /**
     * Retrieve actions as options array
     *
     * @return array
     */
    public function getOptions()
    {
        try {
           $items = $this->actionRepositoryInterface->getList($this->searchCriteriaBuilder->create()->setPageSize(200));
        } catch (LocalizedException $e) {
            return [];
        }

        $res = [];
        foreach ($items->getItems() as $item) {
            if (!isset($this->_cachedIntegrationNames[$item->getIntegrationId()])) {
                $int = $this->integrationRepositoryInterface->getById($item->getIntegrationId());
                if ($int->getId()) {
                    $this->_cachedIntegrationNames[$item->getIntegrationId()] = $int->getName();
                } else {
                    $this->_cachedIntegrationNames[$item->getIntegrationId()] = '';
                }
            }

            $res[] = ['value' => $item->getId(), 'label' => $this->_cachedIntegrationNames[$item->getIntegrationId()] . " - " . $item->getName()];
        }
        return $res;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     * @since 100.1.0
     */
    public function toOptionArray()
    {
        return $this->getOptions();
    }
}
