<?php

class Made_Loop54_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function isEnabled()
    {
        return Mage::getStoreConfigFlag('made_loop54/general/enabled');
    }

    /**
     * Check if Loop54 engine is available
     *
     * @return bool
     */
    public function isActiveEngine()
    {
        $model = Mage::getResourceSingleton('made_loop54');
        if ($model && $model->test()) {
            return true;
        }

        return false;
    }
}