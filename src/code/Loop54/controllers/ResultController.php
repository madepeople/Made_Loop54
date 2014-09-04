<?php

/**
 * Class Made_Loop54_AjaxController
 */
class Made_Loop54_ResultController extends Mage_Core_Controller_Front_Action
{

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

            // This instantiates the layer instance which handles the search results
            $layout->createBlock('made_loop54/catalogsearch_layer');

            $output = $layout->createBlock('catalogsearch/result')
                ->setTemplate('catalogsearch/result.phtml')
                ->toHtml();
        }

        $this->getResponse()
            ->setHeader('Content-type', 'application/json')
            ->setBody($output);
    }

}