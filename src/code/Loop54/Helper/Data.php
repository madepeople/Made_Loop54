<?php

/**
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Helper_Data extends Mage_Core_Helper_Abstract
{

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
}