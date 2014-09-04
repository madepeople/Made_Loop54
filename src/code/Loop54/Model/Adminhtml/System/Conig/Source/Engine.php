<?php

/**
 * Adds Loop54 to the search engine selector
 *
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Model_Adminhtml_System_Config_Source_Engine
{
    public function toOptionArray()
    {
        $engines = array(
            'catalogsearch/fulltext_engine' => Mage::helper('adminhtml')->__('MySql Fulltext'),
            'made_loop54/engine' => Mage::helper('adminhtml')->__('Loop54')
        );

        if (Mage::getConfig()->getModuleConfig('Enterprise_Search')) {
            $engines['enterprise_search/engine'] = Mage::helper('adminhtml')->__('Solr');
        }

        $options = array();
        foreach ($engines as $k => $v) {
            $options[] = array(
                'value' => $k,
                'label' => $v
            );
        }
        return $options;
    }
}
