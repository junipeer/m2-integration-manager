<?php
namespace Junipeer\IntegrationManager\Model\Source\Action;

class ActionEntity implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Quick search actions
     *
     * @var int[]
     * @since 100.1.0
     */
    protected $actions = ['Order', 'Customer', /*'Product'*/];

    /**
     * Retrieve actions as options array
     *
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->actions as $value) {
            $res[] = ['value' => $value, 'label' => $value];
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
