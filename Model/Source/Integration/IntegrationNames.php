<?php
namespace Junipeer\IntegrationManager\Model\Source\Integration;


use Junipeer\IntegrationManager\Model\Manager;

class IntegrationNames implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Manager
     *
     * @var Manager
     */
    protected $manager;

    public function __construct(
        Manager $manager
    )
    {
        $this->manager = $manager;
    }

    /**
     * Retrieve integrations as options array
     *
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->getValues() as $key => $value) {
            $res[] = ['value' => $key, 'label' => $value];
        }
        return $res;
    }

    /**
     * Retrieve search integrations array
     *
     * @return int[]
     */
    public function getValues()
    {
        try {
            return $this->manager->loadIntegrations();
        } catch (\Exception $e) {
            return [];
        }
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
