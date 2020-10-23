<?php
namespace Junipeer\IntegrationManager\Model\Source\Action;

use Junipeer\IntegrationManager\Model\Manager;
use Magento\Framework\Registry;

class ActionNames implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Manager
     *
     * @var Manager
     */
    protected $manager;

    /**
     * @var Registry $registry
     */
    protected $coreRegistry;

    /**
     * ActionNames constructor.
     * @param Registry $registry
     * @param Manager $manager
     */
    public function __construct(
        Registry $registry,
        Manager $manager
    ) {
        $this->coreRegistry = $registry;
        $this->manager = $manager;
    }


    /**
     * Retrieve actions as options array
     *
     * @return array
     */
    public function getOptions()
    {
        $userIntegrationId = $this->coreRegistry->registry("junipeer_user_integration_id");
        if (!$userIntegrationId) {
            return [];
        }
        try {
            $data = $this->manager->loadIntegrationActions($userIntegrationId);
        } catch (\Exception $e) {
            $data = [];
        }
        $res = [];
        foreach ($data as $key => $label) {
            $res[] = ['value' => $key, 'label' => $label];
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
