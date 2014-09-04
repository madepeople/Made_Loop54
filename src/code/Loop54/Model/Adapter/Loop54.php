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
        // @TODO: Implementation, we need a ping call from loop54. Perhaps an
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

        $url = Mage::getStoreConfig('made_loop54/general/url');
        $response = Loop54_RequestHandling::getResponse($url, $request);

        return $response->getCollection('DirectResults');
    }
}