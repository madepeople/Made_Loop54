<?php

class Made_Loop54_Block_Catalogsearch_Autocomplete
    extends Mage_Core_Block_Template
{
    public function getResults()
    {
        $adapter = Mage::getModel('made_loop54/adapter_loop54');
        $query = Mage::helper('catalogsearch')->getQuery();
        $params = array(
            'AutoComplete_MaxEntities' => Mage::getStoreConfig('catalog/search/loop54_autocomplete_max_results')
        );
        list ($results, $size) = $adapter->getAutocompleteResults($query->getQueryText(), $params);
        return $results;
    }
}