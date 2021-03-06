﻿<?php
class Loop54_Entity{
public $externalId;public $entityType;private $_attributes;function __construct($entityType, $externalId){
$this->entityType = $entityType;$this->externalId = $externalId;$this->_attributes = array();}
public function hasAttribute($key){
return isset($_attributes[$key]);}
public function getAttribute($key){
if(!array_key_exists($key,$this->_attributes))return null;return $this->_attributes[$key];}
public function getStringAttribute($key){
if(!array_key_exists($key,$this->_attributes))return "";$ret = "";foreach($this->_attributes[$key] as $value){
$ret .= $value . ", ";}
return rtrim(rtrim($ret,' '),',');}
public function addAttribute($key, $value){
if(!array_key_exists($key,$this->_attributes))$this->_attributes[$key] = array();$this->_attributes[$key][] = $value;}
public function setAttribute($key, $values){
if(is_array($values))$this->_attributes[$key] = $values;else{
$list = array();$list[] = $values;$this->_attributes[$key] = $list;}
}
public function removeAttribute($key){
unset($_attributes[$key]);}
public function serialize(){
$ret = "{";$ret .= "\"EntityType\":\"" . $this->entityType . "\",";$ret .= "\"ExternalId\":\"" . $this->externalId . "\",";$ret .= "\"Attributes\":{";foreach ($this->_attributes as $key=>$values){
$ret .= "\"" . $key . "\":[";foreach ($values as $value){
if($value==null)continue;if (is_string($value))$ret .= "\"" . Loop54_Utils::escape($value) . "\",";else if ($value instanceof DateTime)$ret .= "\"" . $value . "\",";else$ret .= $value . ",";}
$ret = rtrim($ret,',');$ret .= "],";}
$ret = rtrim($ret,',');$ret .= "}}";return $ret;}
}
class Loop54_Event{
public $entity=null;public $string=null;public $revenue=0;public $orderId;public $type;public $quantity=1;public function serialize(){
$str = "{"."\"OrderId\":\"" . $this->orderId . "\"".",\"Type\":\"" . $this->type . "\"".",\"Revenue\":" . $this->revenue;",\"Quantity\":" . $this->quantity;if ($this->string != null){
$str .= ",\"String\":" . $this->string . "\"";}
if ($this->entity != null){
$str .= ",\"Entity\":" . $this->entity->serialize();}
$str .= "}";return $str;}
}
class Loop54_Request{
public $IP=null;public $userId=null;private $_data=array();public $name=null;function __construct($requestName){
$this->name = $requestName;}
public function setValue($key,$value){
$this->_data[$key] = $value;}
public function serialize(){
if ($this->userId == null)$this->userId = Loop54_Utils::getUser();if ($this->IP == null)$this->IP = Loop54_Utils::getIP();$ret = "\"" . $this->name . "\":{";if ($this->IP != null)$ret .= "\"IP\":\"" . Loop54_Utils::escape($this->IP) . "\",";if ($this->userId != null)$ret .= "\"UserId\":\"" . Loop54_Utils::escape($this->userId) . "\",";foreach ($this->_data as $key=>$value){
if($value==null)continue;$ret .= "\"" . $key . "\":" . Loop54_Utils::serializeObject($value) . ",";}
$ret = rtrim($ret,',');$ret .= "}";return $ret;}
}
abstract class Loop54_RequestHandling{
public static function getResponse($engineUrl, Loop54_Request $request){
if (!is_string($engineUrl)) {
trigger_error("Argument engineUrl must be string.");return;}
$engineUrl = Loop54_Utils::fixEngineUrl($engineUrl);$data = "{" . $request->serialize() . "}";try {
$s = curl_init($engineUrl);curl_setopt($s,CURLOPT_POST,1); curl_setopt($s,CURLOPT_RETURNTRANSFER, 1 );curl_setopt($s,CURLOPT_POSTFIELDS,$data);curl_setopt($s,CURLOPT_TIMEOUT, 10);curl_setopt($s,CURLOPT_HTTPHEADER,array('Content-Type: text/plain; charset=UTF-8'));$response = curl_exec($s);if(curl_errno($s)){
trigger_error('Curl error: ' . curl_error($s));return;}
curl_close($s);}
catch(Exception $ex){
trigger_error("Could not retrieve a response from " . $engineUrl);return;}
$ret = new Loop54_Response($response,$request->name);return $ret;}
}
class Loop54_EngineResponse{
public $success;public $errorCode;public $errorMessage;public $requestId;public $_data;function __construct($stringData, $questName){
try {
$json = json_decode($stringData);}
catch(Exception $ex){
trigger_error("Engine returned incorrectly formed JSON " . $ex . ": " . $stringData);return;}
if($json==null){
trigger_error("Engine returned incorrectly formed JSON: " . $stringData);return;}
$responseObj = $json;if ((bool)$responseObj->{"Success"} != true){
$this->success = false;$this->errorCode = (int)$responseObj->{"Error_Code"};$this->errorMessage = (string)$responseObj->{"Error_Message"};$this->requestId = (string)$responseObj->{"RequestId"};return;}
$data = $responseObj->{"Data"};$this->_data = $data->{$questName};$this->success = true;}
}
class Loop54_Response extends Loop54_EngineResponse{
public function hasData($key){
return isset($this->_data->{$key});}
public function getValue($key){
if(is_array($key))trigger_error($key . " is a collection.");return $this->_data->{$key};}
public function getCollection($key){
$origVal = $this->_data->{$key};if(!is_array($origVal))trigger_error($key . " is not a collection.");$ret = array();foreach($origVal as $item){
if(is_object($item)){
$i = new Loop54_Item();if(isset($item->{"Entity"})){
$value = $item->{"Entity"};$entity = new Loop54_Entity($value->{"EntityType"},$value->{"ExternalId"});if(isset($value->{"Attributes"})){
if(is_object($value->{"Attributes"})){
foreach($value->{"Attributes"} as $attrName => $attrValue){
$entity->setAttribute($attrName,$attrValue);}
}
else if(is_array($value->{"Attributes"})){
foreach($value->{"Attributes"} as $obj){
$attrName = $obj->{"Key"};$attrValue = $obj->{"Value"};$entity->setAttribute($attrName,$attrValue);}
}
}
$i->entity = $entity;}
if(isset($item->{"String"})){
$i->string = $item->{"String"};}
$i->value = $item->{"Value"};$ret[]=$i;}
else{
$ret[] = $item;}
}
return $ret;}
}
class Loop54_Item {
public $entity;public $string;public $value;}
abstract class Loop54_Utils{
static function escape($val){
if (!is_string($val)) {
trigger_error("Argument val must be string.");return;}
$val = str_replace("\"","\\\"",$val);return $val;}
static function randomString($length){
if (!is_int($length)) {
trigger_error("Argument length must be int.");return;}
$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';$randomString = '';for ($i = 0; $i < $length; $i++) {
$randomString .= $characters[rand(0, strlen($characters) - 1)];}
return $randomString;}
static function getUser(){
$existingCookie = null;if(isset($_COOKIE{'Loop54User'}))$existingCookie = $_COOKIE{'Loop54User'};if($existingCookie!=null)return $existingCookie;$userId = Loop54_Utils::getIP() . "_" . Loop54_Utils::randomString(10);setcookie('Loop54User',$userId,time() + (86400 * 365),"/"); $_COOKIE{'Loop54User'} = $userId; return $userId;}
static function getIP(){
if(isset($_SERVER{'REMOTE_ADDR'}))return $_SERVER{'REMOTE_ADDR'};return "";}
static function fixEngineUrl($url){
if (!is_string($url)) {
trigger_error("Argument url must be string.");return;}
$url = trim($url);if($url==""){
trigger_error("Argument url cannot be empty.");return;}
$url = strtolower($url);if(!Loop54_Utils::stringBeginsWith($url,"http"))$url = "http://" . $url;if(!Loop54_Utils::stringEndsWith($url,"/"))$url = $url . "/";return $url;}
static function stringBeginsWith( $str, $sub ) {
return ( substr( $str, 0, strlen( $sub ) ) == $sub );}
static function stringEndsWith( $str, $sub ) {
return ( substr( $str, strlen( $str ) - strlen( $sub ) ) == $sub );}
static function serializeObject($data){
if (is_string($data))return "\"" . Loop54_Utils::escape($data) . "\"";else if ($data instanceof DateTime)return "\"" . $data . "\"";else if ($data instanceof Loop54_Entity)return $data->serialize();else if ($data instanceof Loop54_Event)return $data->serialize();else if (is_array($data)){
if(Loop54_Utils::isAssoc($data)){
$ret = "{";foreach ($data as $key => $value){
$ret .= "\"" . $key . "\":" . Loop54_Utils::serializeObject($value) . ",";}
$ret = rtrim($ret,',') . "}";return $ret;}
else{
$ret = "[";foreach($data as $dataVal){
$ret .= Loop54_Utils::serializeObject($dataVal) . ",";}
$ret = rtrim($ret,',') . "]";return $ret;}
}
else{
return $data;}
}
static function isAssoc($arr){
return array_keys($arr) !== range(0, count($arr) - 1);}
}

?>