<?php
namespace Junipeer\IntegrationManager\Model\Source\Event;

class Events implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * Quick search actions
     *
     * @var int[]
     * @since 100.1.0
     */
    protected $fields = [
        'order.created' => 'Order Created',
        'customer.created' => 'Customer Created',
        'customer.created.admin' => 'Customer Created (In Admin)',
        'customer.updated.frontend' => 'Customer Updated (In Frontend)',
      //  'customer.deleted' => 'Customer Deleted',
     //   'product.created' => 'Product Created',
     //   'product.updated' => 'Product Updated',
     //   'product.deleted' => 'Product Deleted',
    ];

    /**
     * Retrieve actions as options array
     *
     * @return array
     */
    public function getOptions()
    {
        $res = [];
        foreach ($this->fields as $key => $value) {
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
