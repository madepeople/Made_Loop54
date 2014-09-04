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
}