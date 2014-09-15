<?php

/**
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Model_Resource_Engine
{
    protected $_adapter;

    /**
     * We only have one adapter right now
     */
    public function __construct()
    {
        $this->_adapter = Mage::getSingleton('made_loop54/adapter_loop54');
    }

    /**
     * Define if selected adapter is available
     *
     * @return bool
     */
    public function test()
    {
        return $this->_adapter->ping();
    }

    /**
     * Retrieve search result data collection
     *
     * @return Made_Loop54_Model_Resource_Collection
     */
    public function getResultCollection()
    {
        return Mage::getResourceModel('made_loop54/collection')->setEngine($this);
    }

    /**
     * Define if Layered Navigation is allowed
     *
     * @deprecated after 1.9.1
     * @see $this->isLayeredNavigationAllowed()
     *
     * @return bool
     */
    public function isLeyeredNavigationAllowed()
    {
        $this->isLayeredNavigationAllowed();
    }

    /**
     * Define if Layered Navigation is allowed
     *
     * @return bool
     */
    public function isLayeredNavigationAllowed()
    {
        return true;
    }

    /**
     * Perform a search query
     *
     * @param $query
     * @param array $params
     * @return array
     */
    public function search($query, $params = array())
    {
        return $this->_adapter->search($query, $params);
    }

    /**
     * We don't control the index at this point, so we just return $this
     *
     * @param null $storeIds
     * @param null $entityIds
     * @param string $entityType
     * @return $this
     */
    public function cleanIndex($storeIds = null, $entityIds = null, $entityType = 'product')
    {
        return $this;
    }

    /**
     * Define if current search engine supports advanced index
     *
     * @return bool
     */
    public function allowAdvancedIndex()
    {
        // Don't really know what this is yet
        return false;
    }

    /**
     * Retrieve allowed visibility values for current engine
     *
     * @return array
     */
    public function getAllowedVisibility()
    {
        return Mage::getSingleton('catalog/product_visibility')->getVisibleInSiteIds();
    }

    /**
     * Prepare index array
     *
     * @param array $index
     * @param string|null $separator
     * @return array
     */
    public function prepareEntityIndex($index, $separator = null)
    {
        return $index;
    }

    /**
     * Add entity data to search index
     *
     * @param int $entityId
     * @param int $storeId
     * @param array $index
     * @param string $entityType 'product'|'cms'
     *
     * @return Enterprise_Search_Model_Resource_Engine
     */
    public function saveEntityIndex($entityId, $storeId, $index, $entityType = 'product')
    {
        return $this;
    }

    /**
     * Add entities data to search index
     *
     * @param int $storeId
     * @param array $entityIndexes
     * @param string $entityType 'product'|'cms'
     *
     * @return Enterprise_Search_Model_Resource_Engine
     */
    public function saveEntityIndexes($storeId, $entityIndexes, $entityType = 'product')
    {
        return $this;
    }

}