<html>
<head>
<style type="text/css">

	* {
		border:0;
		margin:0;
		padding:0;
	}

	body {
		font-family:arial;
		color:333333;
		font-size:13px;
	}
	
	input {
		border:1px solid black;
		padding:5px;
	}
	
	div.everything {
		width:1000px;
		margin:0 auto;
	}

</style>
</head>
<body>



<?php

include "Loop54.php";

//find variables
$id = "";
if(isset($_GET{"id"}))
	$id = $_GET{"id"};
	
$e = "";
if(isset($_GET{"engine"}))
	$e = $_GET{"engine"};
	
	
$titleAttribute = "name";
$imagePrefix = "http://www.bloomify.se/product_thumb.php?w=218&h=218&img=/images/";
$imageAttribute = "images";

	?>
	
	<div class="everything">

	<form action="Search.php" method="GET">
		<fieldset>
			<input name="engine" value="<?php echo $e ?>" type="hidden" />
			<input name="query" value="" type="text" />
			
			<input type="submit" name="submit" value="SÖK" />
			
		</fieldset>
	</form>
	
	<br /><br />
	
	<?php
	
echo "<b>" . $id . "</b><br /><br />";

//construct document
$entity = new Loop54_Entity("Product",$id);

//track conversion
$event = new Loop54_Event();
$event->type="click";
$event->entity = $entity;

$request = new Loop54_Request("CreateEvents");
$request->setValue("Events",array($event));
$response = Loop54_RequestHandling::getResponse($e,$request);


//get similar and stuff
$request = new Loop54_Request("SimilarProducts");
$request->setValue("RequestEntity",$entity);

$response = Loop54_RequestHandling::getResponse($e,$request);

//if no success, display error message
if(!$response->success)
	echo $response->errorMessage;

//print similar docs
if($response->hasData("SimilarProducts"))
{
	echo "<div><b>Liknande:</b><br />";
	
	foreach($response->getCollection("SimilarProducts") as $result)
	{

		echo "<div class=\"result\">";

		echo "<b><a href=\"Entity.php?id=".$result->entity->externalId."&engine=".$e."\">" . $result->entity->getStringAttribute($titleAttribute) . "</a></b><br />";
		
		echo "</div>";
	}
	
	echo "</div>";
	
}


?>

</div>

</body>