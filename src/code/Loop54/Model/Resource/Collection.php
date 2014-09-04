<?php

class Made_Loop54_Model_Resource_Collection
    extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Collection
{
    /**
     * Store search query text
     *
     * @var string
     */
    protected $_searchQueryText = '';

    /**
     * Store search engine instance
     *
     * @var object
     */
    protected $_engine = null;

    /**
     * Set search engine
     *
     * @param object $engine
     * @return Made_Loop54_Model_Resource_Collection
     */
    public function setEngine($engine)
    {
        $this->_engine = $engine;
        return $this;
    }

    /**
     * Add search query filter
     * Set search query
     *
     * @param   string $query
     *
     * @return  Enterprise_Search_Model_Resource_Collection
     */
    public function addSearchFilter($query)
    {
        $this->_searchQueryText = $query;

        return $this;
    }

    /**
     * Search documents by query
     * Set found ids and number of found results
     *
     * @return Enterprise_Search_Model_Resource_Collection
     */
    protected function _beforeLoad()
    {
        $ids = array();
        if ($this->_engine) {
            $query = $this->_searchQueryText;

            if ($this->_pageSize !== false) {
                $page              = ($this->_curPage  > 0) ? (int) $this->_curPage  : 1;
                $rowCount          = ($this->_pageSize > 0) ? (int) $this->_pageSize : 1;
                $params['offset']  = $rowCount * ($page - 1);
                $params['DirectResults_MaxEntities']   = $rowCount;
            }

            $result = $this->_engine->getIdsByQuery($query, $params);
            $ids    = (array) $result['ids'];
        }

        $this->_searchedEntityIds = &$ids;
        $this->getSelect()->where('e.entity_id IN (?)', $this->_searchedEntityIds);

        /**
         * To prevent limitations to the collection, because of new data logic
         */
        $this->_pageSize = false;

        return parent::_beforeLoad();
    }

    public function getSize()
    {
        return 1;
    }
}