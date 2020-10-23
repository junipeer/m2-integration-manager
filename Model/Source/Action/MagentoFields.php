<?php
namespace Junipeer\IntegrationManager\Model\Source\Action;

use Magento\Framework\Registry;

class MagentoFields implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Core registry
     *
     * @var Registry
     */
    protected $coreRegistry;

    public function __construct(
        Registry $registry
    )
    {
        $this->coreRegistry = $registry;
    }

    /**
     * Quick search actions
     *
     * @var int[]
     * @since 100.1.0
     */
    protected $fields = [
        'Order' => [
            'order_id' => 'Order ID',
        ],
        'Customer'  =>[
            'customer_id' => 'Customer ID',
            'email' => 'E-mail',
        ],
        /*
        'Product' => [
            'product_id' => 'Product ID',
            'sku' => 'SKU',
        ]
        */
    ];

    /**
     * Retrieve actions as options array
     *
     * @return array
     */
    public function getOptions()
    {
        $type = $this->coreRegistry->registry("action_entity_type");
        $type = $type ? $type : '';
        if (!in_array($type, ['Order', 'Customer','Product'])) {
           return [];
        }

        $res = [];
        $fields = $this->fields[$type];
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
