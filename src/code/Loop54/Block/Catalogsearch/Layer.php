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

    /**
     * We needed a nifty place to inject javascript variables without the need
     * for extra templates, and this obviously works
     *
     * @return string|void
     */
    protected function _toHtml()
    {
        $ajaxResultBaseUrl = $this->getUrl('made_loop54/result/ajax');
        $seachFormPlaceholder = Mage::helper('catalogsearch')->__('Search entire store here...');

        $ajaxResultBaseUrl = Mage::helper('core')->jsonEncode($ajaxResultBaseUrl);
        $seachFormPlaceholder = Mage::helper('core')->jsonEncode($seachFormPlaceholder);

        $html = parent::_toHtml();
        $html .=<<<EOF
<script>
var _ajaxResultBaseUrl = $ajaxResultBaseUrl;
var _searchFormPlaceholder = $seachFormPlaceholder;
</script>
EOF;

        return $html;
    }

}