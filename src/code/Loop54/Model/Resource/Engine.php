<?php

class Made_Loop54_Model_Resource_Engine
{
    protected $_adapter;

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
     * @return Enterprise_Search_Model_Resource_Collection
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
}