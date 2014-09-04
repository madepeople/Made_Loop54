<?php

/**
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Block_Catalogsearch_Autocomplete
    extends Mage_Core_Block_Template
{

    /**
     * Use the autocompleter API instead of fetching full results in order to
     * save resources
     *
     * @return array
     */
    public function getResults()
    {
        $adapter = Mage::getModel('made_loop54/adapter_loop54');
        $query = Mage::helper('catalogsearch')->getQuery();
        $params = array(
            'AutoComplete_MaxEntities' => Mage::getStoreConfig('catalog/search/loop54_autocomplete_max_results')
        );
        $results = $adapter->getAutocompleteResults($query->getQueryText(), $params);
        return $results;
    }
}