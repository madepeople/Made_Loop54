<?php

/**
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Returns the field used for mapping Loop54 externalId with Magento products
     *
     * @return string
     */
    public function getIdFieldName()
    {
        $idField = Mage::getStoreConfig('catalog/search/loop54_external_id');
        return $idField;
    }

    /**
     * Retrieve information from search engine configuration
     *
     * @param string $field
     * @param int $storeId
     * @return string|int
     */
    public function getSearchConfigData($field, $storeId = null)
    {
        $path = 'catalog/search/' . $field;
        return Mage::getStoreConfig($path, $storeId);
    }

    /**
     * Check if Loop54 engine is available
     *
     * @return bool
     */
    public function isActiveEngine()
    {
        $engine = $this->getSearchConfigData('engine');

        if ($engine && Mage::getConfig()->getResourceModelClassName($engine)) {
            $model = Mage::getResourceSingleton($engine);
            if ($model && $model->test()) {
                return true;
            }
        }

        return false;
    }

    /**
     * The AJAX base url
     *
     * @return string|void
     */
    public function getAjaxResultUrl()
    {
        $ajaxResultBaseUrl = Mage::app()->getStore()->getUrl('made_loop54/result/ajax');
        return $ajaxResultBaseUrl;
    }

    /**
     * Adds loop 54 search relevance to a product collection, so we can order
     * using it
     *
     * @param $collection
     */
    public function addLoopRelevanceToCollection($collection, $loop54result)
    {
        $ids = array();
        foreach ($loop54result as $item) {
            $ids[] = array(
                'id' => $item->entity->externalId,
                'relevance' => $item->value
            );
        }

        $idField = $this->getIdFieldName();

        // 16:18 < Xgc> js_: It's a bad idea.  But sometimes we have to
        // do what we have to do.
        $unionTable = '';
        $connection = $collection->getConnection();
        foreach ($ids as $item) {
            $query = 'UNION SELECT ? `' . $idField . '`, '
                . $item['relevance'] . ' `relevance` ';
            $unionTable .= $connection->quoteInto($query, $item['id']);
        }

        $unionTable = preg_replace('#^UNION #', '', $unionTable);
        $unionTable = "(SELECT * FROM ($unionTable) loop54_relevance)";

        $collection->getSelect()->join(
            array('loop54_relevance' => new Zend_Db_Expr($unionTable)),
            'loop54_relevance.' . $idField . ' = e.' . $idField,
            array($idField, 'relevance')
        );

        return $collection;
    }
}