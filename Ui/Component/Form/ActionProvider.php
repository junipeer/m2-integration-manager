<?php

namespace Junipeer\IntegrationManager\Ui\Component\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Junipeer\IntegrationManager\Model\ResourceModel\Action\CollectionFactory;
use Junipeer\IntegrationManager\Model\ResourceModel\Action\Collection;

class ActionProvider extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $pageCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $pageCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $pageCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }


    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        /** @var $items \Junipeer\IntegrationManager\Model\Action[] */
        $items = $this->collection->getItems();
        foreach ($items as $page) {
            $this->loadedData[$page->getId()] = $this->fix($page->getData());
        }
        $data = $this->dataPersistor->get('module_messages');
        if (!empty($data)) {

            /** @var $page \Junipeer\IntegrationManager\Model\Action  */
            $page = $this->collection->getNewEmptyItem();
            $page->setData($data);
            $this->loadedData[$page->getId()] = $this->fix($page->getData());
            $this->dataPersistor->clear('module_messages');
        }

        $this->loadedData = $this->fix($this->loadedData);
        return $this->loadedData;
    }

    /**
     * @param $item array
     * @return array
     */
    protected function fix($item)
    {

        if (isset($item['fields'])) {
            $fields = unserialize($item['fields']);
            $item['dynamic_rows'] = $fields;

            unset($item['fields']);
        }

        return $item;
    }


}
