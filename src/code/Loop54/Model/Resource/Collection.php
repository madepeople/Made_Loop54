<?php

/**
 * @author jonathan@madepeople.se
 */
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
     * Holds the size of the search result
     *
     * @var int
     */
    protected $_size;

    /**
     * Contains the loop 54 response object after a successful search
     *
     * @var object
     */
    protected $_loop54Response;

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

            $response = $this->_engine->search($query, $params);

            // Put the response in the collection regardless, so we can do some
            // template magic
            $this->_loop54Response = $response;

            $result = $response->getCollection('DirectResults');
            $ids = array();
            foreach ($result as $item) {
                $ids[] = $item->entity->externalId;
            }

            $idField = Mage::helper('made_loop54')->getIdFieldName();
            $this->getSelect()->where('e.' . $idField . ' IN (?)', $ids);
        }

        return parent::_beforeLoad();
    }

    /**
     * Add attribute to sort order
     *
     * @param string $attribute
     * @param string $dir
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function addAttributeToSort($attribute, $dir = self::SORT_ORDER_ASC)
    {
        if ($attribute == 'relevance') {
            $response = $this->_loop54Response;
            if ($response) {
                $loop54result = $response->getCollection('DirectResults');
                Mage::helper('made_loop54')->addLoopRelevanceToCollection($this, $loop54result);
                $this->_select->order("loop54_relevance.relevance {$dir}");
                return $this;
            }
        }

        return parent::addAttributeToSort($attribute, $dir);
    }

    /**
     * Return the response for the future
     *
     * @return object
     */
    public function getLoop54Response()
    {
        return $this->_loop54Response;
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
                ->_renderFilters();

            $this->_select->columns(array('cnt' => new Zend_Db_Expr('COUNT(*)')));
            $row = $this->getConnection()->fetchRow($this->_select);
            $this->_size = $row['cnt'];
            $this->_select = $originalSelect;
        }
        return $this->_size;
    }
}