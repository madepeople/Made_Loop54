<?php

/**
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Model_Resource_Collection
    extends Mage_Catalog_Model_Resource_Product_Collection
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
     * Holds the size of the search result
     *
     * @var int
     */
    protected $_size;

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
     * @return  Made_Loop54_Model_Resource_Collection
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
     * @return Made_Loop54_Model_Resource_Collection
     */
    protected function _beforeLoad()
    {
        if ($this->_engine) {
            $query = $this->_searchQueryText;
            $params = array();

            list($result, $totalSize) = $this->_engine->search($query, $params);

            $ids = array();
            foreach ($result as $item) {
                $ids[] = $item->entity->externalId;
            }

            $this->getSelect()->where('e.entity_id IN (?)', $ids);
        }

        return parent::_beforeLoad();
    }

    /**
     * Retrieve the total amount of items. As of now we have to make a query
     * on the whole result set
     *
     * @return int
     */
    public function getSize()
    {
        if ($this->_size === null) {
            // This is shit, but the chicken-egg issue we have prevents us
            // from doing it differently. If we count the items from Loop54,
            // we might count inactive/deleted/hidden products, so the result
            // needs to come from our MySQL to be valid
            $originalSelect = clone $this->_select;
            $this->_beforeLoad()
                ->_renderFilters()
                ->_renderOrders();

            $this->_select->columns(array('cnt' => new Zend_Db_Expr('COUNT(*)')));
            $row = $this->getConnection()->fetchRow($this->_select);
            $this->_size = $row['cnt'];
            $this->_select = $originalSelect;
        }
        return $this->_size;
    }
}