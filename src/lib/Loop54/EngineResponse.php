<?php

class Loop54_EngineResponse
{
    public $success;
    public $errorCode;
    public $errorMessage;
    public $requestId;
    public $_data;

    function __construct($stringData, $questName)
    {
        try {
            $json = json_decode($stringData);
        } catch (Exception $ex) {
            trigger_error("Engine returned incorrectly formed JSON " . $ex . ": " . $stringData);
            return;
        }
        if ($json == null) {
            trigger_error("Engine returned incorrectly formed JSON: " . $stringData);
            return;
        }
        $responseObj = $json;
        if ((bool)$responseObj->{"Success"} != true) {
            $this->success = false;
            $this->errorCode = (int)$responseObj->{"Error_Code"};
            $this->errorMessage = (string)$responseObj->{"Error_Message"};
            $this->requestId = (string)$responseObj->{"RequestId"};
            return;
        }
        $data = $responseObj->{"Data"};
        $this->_data = $data->{$questName};
        $this->success = true;
    }
}