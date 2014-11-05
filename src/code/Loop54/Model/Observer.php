<?php

/**
 * Register various events at Loop54
 *
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Model_Observer
{

    /**
     * Register all viewed product pages
     *
     * @param Varien_Event_Observer $observer
     */
    public function registerCatalogProductView(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag('catalog/search/loop54_register_events')) {
            return;
        }

        $productId = $observer->getProduct()
            ->getId();

        $event = new Loop54_Event;
        $event->type = 'Click';
        $event->entity = new Loop54_Entity('Product', $productId);

        $adapter = Mage::getModel('made_loop54/adapter_loop54');
        $adapter->registerEvent($event);
    }

    /**
     * Register products added to cart
     *
     * @param Varien_Event_Observer $observer
     */
    public function registerAddToCart(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag('catalog/search/loop54_register_events')) {
            return;
        }

        $productId = $observer->getQuoteItem()
            ->getProductId();

        $event = new Loop54_Event;
        $event->type = 'AddToCart';
        $event->entity = new Loop54_Entity('Product', $productId);

        $adapter = Mage::getModel('made_loop54/adapter_loop54');
        $adapter->registerEvent($event);
    }

    /**
     * Register ordered (purchased) products
     *
     * @param Varien_Event_Observer $observer
     */
    public function registerPurchase(Varien_Event_Observer $observer)
    {
        if (!Mage::getStoreConfigFlag('catalog/search/loop54_register_events')) {
            return;
        }

        $order = $observer->getOrder();
        $events = array();
        foreach ($order->getAllItems() as $item) {
            $event = new Loop54_Event();
            $event->type = 'Purchase';
            $event->orderId = $order->getIncrementId();
            $event->entity = new Loop54_Entity('Product', $item->getProductId());
            $event->quantity = (int)$item->getQtyOrdered();
            $events[] = $event;
        }

        $adapter = Mage::getModel('made_loop54/adapter_loop54');
        $adapter->registerEvent($events);
    }
}