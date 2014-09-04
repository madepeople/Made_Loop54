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

    public function getAutocompleteResults($query, $params = array())
    {
        $request = new Loop54_Request('AutoComplete');
        $request->setValue('QueryString', $query);

        foreach ($params as $key => $value) {
            $request->setValue($key, $value);
        }

        $url = Mage::getStoreConfig('catalog/search/loop54_url');
        $response = Loop54_RequestHandling::getResponse($url, $request);

        return array(
            $response->getCollection('AutoComplete'),
            $response->_data->AutoComplete_TotalItems
        );
    }
}