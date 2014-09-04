<?php

class Made_Loop54_Model_Search_Layer extends Mage_CatalogSearch_Model_Layer
{
    /**
     * Retrieve current layer product collection
     *
     * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Collection
     */
    public function getProductCollection()
    {
        if (isset($this->_productCollections[$this->getCurrentCategory()->getId()])) {
            $collection = $this->_productCollections[$this->getCurrentCategory()->getId()];
        } else {
            $collection = Mage::helper('catalogsearch')->getEngine()->getResultCollection();
            $collection->setStoreId($this->getCurrentCategory()->getStoreId());
            $this->prepareProductCollection($collection);
            $this->_productCollections[$this->getCurrentCategory()->getId()] = $collection;
        }

        return $collection;

    }