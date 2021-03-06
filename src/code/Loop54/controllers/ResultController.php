<?php

/**
 * Custom results controller, because we want an AJAX result for autocompletion
 * for instance
 *
 * @author jonathan@madepeople.se
 */
class Made_Loop54_ResultController extends Mage_Core_Controller_Front_Action
{

    /**
     * The action only renders a single root element that developers can
     * modify using layout XML
     */
    public function ajaxAction()
    {
        $query = Mage::helper('catalogsearch')->getQuery();
        /* @var $query Mage_CatalogSearch_Model_Query */

        $query->setStoreId(Mage::app()->getStore()->getId());

        $output = '';
        if ($query->getQueryText() != '') {
            if (Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->setId(0)
                    ->setIsActive(1)
                    ->setIsProcessed(1);
            }
            else {
                if ($query->getId()) {
                    $query->setPopularity($query->getPopularity()+1);
                }
                else {
                    $query->setPopularity(1);
                }

                if ($query->getRedirect()){
                    $query->save();
                    $this->getResponse()->setRedirect($query->getRedirect());
                    return;
                }
                else {
                    $query->prepare();
                }
            }

            Mage::helper('catalogsearch')->checkNotes();

            if (!Mage::helper('catalogsearch')->isMinQueryLength()) {
                $query->save();
            }

            $layout = $this->getLayout();
            $this->loadLayout(array(
                'made_loop54_result_ajax',
            ));

            $this->renderLayout();
        }
    }

}