<?php

/**
 * Adapter that uses the integration library supplied by Loop54
 *
 * @author jonathan@madepeople.se
 */
class Made_Loop54_Model_Adapter_Loop54
{
    public function ping()
    {
        // @TODO: Implementation, we need a ping call from Loop54. Perhaps an
        // empty search query?
        return true;
    }

    /**
     * Queries the Loop54 engine for search results and returns them as some
     * kind of dataset
     *
     * @param $query
     * @param array $params
     * @return array
     */
    public function search($query, $params = array())
    {
        $request = new Loop54_Request('Search');
        $request->setValue('QueryString', $query);

        foreach ($params as $key => $value) {
            $request->setValue($key, $value);
        }

        $url = Mage::getStoreConfig('catalog/search/loop54_url');
        $response = Loop54_RequestHandling::getResponse($url, $request);

        return array(
            $response->getCollection('DirectResults'),
            $response->_data->DirectResults_TotalItems
        );
    }

    /**
     * Return products similar to the given ID. Useful for the upsell block
     *
     * @param int $productId
     * @return array
     * @see Made_Loop54_Block_Product_List_Similar
     */
    public function getSimilarProducts($productId)
    {
        $request = new Loop54_Request('SimilarProducts');
        $entity = new Loop54_Entity('Document', $productId);

        $request->setValue('RequestEntity', $entity);
        $request->setValue('SimilarProducts_MaxEntities', 100);

        $url = Mage::getStoreConfig('catalog/search/loop54_url');
        $response = Loop54_RequestHandling::getResponse($url, $request);
        if (empty($response)) {
            return array();
        }

        return $response->getCollection('SimilarProducts');
    }

    /**
     * Return autocomplete results suitable for input text quick search fields
     *
     * @param $query
     * @param array $params
     * @return array
     */
    public function getAutocompleteResults($query, $params = array())
    {
        $request = new Loop54_Request('AutoComplete');
        $request->setValue('QueryString', $query);

        foreach ($params as $key => $value) {
            $request->setValue($key, $value);
        }

        $url = Mage::getStoreConfig('catalog/search/loop54_url');
        $response = Loop54_RequestHandling::getResponse($url, $request);

        return $response->getCollection('AutoComplete');
    }
}