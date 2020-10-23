<?php

namespace Junipeer\IntegrationManager\Block\Adminhtml\Integration\Edit\Buttons;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class AddNewAction extends Generic implements ButtonProviderInterface
{

    /**
     * Get button attributes
     *
     * @return array
     */
    public function getButtonData()
    {
        if ($this->getDataId()) {
            return [
                'label' => __('Add new Action'),
                'on_click' => sprintf("location.href = '%s';", $this->getAddUrl()),
                'class' => 'action-secondary',
                'sort_order' => 100
            ];
        }

        return [];
    }

    /**
     * Get URL for back button
     *
     * @return string
     */
    public function getAddUrl()
    {
        return $this->getUrl('*/action/add', ['integration_id' => $this->getDataId()]);
    }
}
