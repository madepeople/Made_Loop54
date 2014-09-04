<?php

/**
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Block_Catalogsearch_Layer extends Mage_CatalogSearch_Block_Layer
{

    /**
     * Get layer object, this is the only place we can put a custom search
     * engine thing without rewriting anything. This layer model is what
     * returns all resource models etc that manipulate the collection and
     * fetches information correctly back up to the search result list block.
     *
     * @return Mage_Catalog_Model_Layer
     */
    public function getLayer()
    {
        $helper = Mage::helper('made_loop54');
        if ($helper->isActiveEngine()) {
            return Mage::getSingleton('made_loop54/search_layer');
        }

        return parent::getLayer();
    }

}