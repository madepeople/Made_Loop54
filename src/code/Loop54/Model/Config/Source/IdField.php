<?php

/**
 * Used to select which field to use as externalId when connecting Loop54
 * results with the Magento database
 *
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Model_Config_Source_IdField
{
    public function toOptionArray()
    {
        return array(
            array(
                'value' => 'entity_id',
                'label' => 'Entity ID'
            ),
            array(
                'value' => 'sku',
                'label' => 'SKU'
            ),
        );
    }
}