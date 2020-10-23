<?php
namespace Junipeer\IntegrationManager\Model\Source\Action;

use Junipeer\IntegrationManager\Model\Manager;
use Magento\Framework\Registry;

class JunipeerFields implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * Manager
     *
     * @var Manager
     */
    protected $manager;

    public function __construct(
        Registry $registry,
        Manager $manager
    ) {
        $this->manager = $manager;
        $this->coreRegistry = $registry;
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
        $action = $this->coreRegistry->registry("junipeer_action");
        $userIntegrationId = $this->coreRegistry->registry("junipeer_user_integration_id");
        if (!$userIntegrationId) {
            return [];
        }

        try {
            $fields = $this->manager->getIntegrationFields($userIntegrationId, $action);
        } catch (\Exception $e) {
            $fields = [];
        }

        $res = [];
        foreach ($fields as $key => $value) {
            $res[] = ['value' => $key, 'label' => $value];
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
