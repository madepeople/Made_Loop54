<?php

/**
 * Fetches similar products from the current product using the Loop54 API.
 *
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Block_Product_List_Similar
    extends Mage_Catalog_Block_Product_Abstract
{
    protected $_productCollection;

    public function getItemCollection()
    {
        if (null === $this->_productCollection) {
            $product = Mage::registry('product');
            $adapter = new Made_Loop54_Model_Adapter_Loop54;
            $similarProducts = $adapter->getSimilarProducts($product->getId());

            $ids = array();
            foreach ($similarProducts as $similarProduct) {
                $ids[] = $similarProduct->entity->externalId;
            }

            $collection = Mage::getModel('catalog/product')
                ->getCollection()
                ->addStoreFilter()
                ->addIdFilter($ids);

            Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($collection,
                Mage::getSingleton('checkout/session')->getQuoteId()
            );

            $this->_addProductAttributesAndPrices($collection);

            Mage::getSingleton('catalog/product_visibility')
                ->addVisibleInCatalogFilterToCollection($collection);

            Mage::dispatchEvent('made_loop54_related_collection_init',
                array(
                    'collection' => $collection
                ));

            $collection->load();

            foreach ($collection as $product) {
                $product->setDoNotUseCategoryId(true);
            }

            $this->_productCollection = $collection;
        }

        return $this->_productCollection;
    }
}