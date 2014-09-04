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

	div.result {
		width:218px;
		display:inline-block;
		border:1px solid gray;
		padding:10px;
		margin:5px;
		vertical-align:top;
	}
	
	div.result img, div.result a {
		font-weight:bold;
		text-decoration:none;
		display:block;
		margin-bottom:10px;
	}

	div.leveldivider {
		width:100%;
		border-bottom:2px solid gray;
		margin-top:20px;
		margin-bottom:20px;
		font-size:18px;
		font-weight:bold;
	}
	
	div.spellingsuggestions {
		margin-top:10px;
	}
	
	div.spellingsuggestions a {
		margin-left:5px;
		margin-right:5px;
	}
	
	div.error {
		font-size:16px;
		padding:10px;
		margin-top:10px;
		margin-bottom:0px;
		border:1px solid black;
		background-color:pink;
		width:978px;
	}
	
	
</style>
</head>
<body>

<?php

include "Loop54.php";

//get variables
	
$q = "";
if(isset($_GET{"query"}))
	$q = $_GET{"query"};
	
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
			<input name="query" value="<?php echo $q ?>" type="text" />
			
			<input type="submit" name="submit" value="SÖK" />
			
		</fieldset>
	</form>



<?php

//make query request if query is set
if($q!="")
{
	$request = new Loop54_Request("Search");

	$request->setValue("QueryString",$q);
	$request->setValue("DirectResults_MaxEntities",24);
	$request->setValue("RecommendedResults_MaxEntities",24);

	$response = Loop54_RequestHandling::getResponse($e,$request);
	
	if($response->hasData("MakesSense") && !$response->getValue("MakesSense"))
	{
		echo "<div class=\"error\">Vi förstod inte sökningen \"".$q."\".";
		
		if($response->hasData("SpellingSuggestions") && count($response->getCollection("SpellingSuggestions"))>0)
		{
			echo "<div class=\"spellingsuggestions\">Menade du ";
			foreach($response->getCollection("SpellingSuggestions") as $item)
			{
				echo "<a href=\"Search.php?engine=".$e."&query=".$item->string."\">" . $item->string . "</a>";
			}
			echo "</div>";
		}
		
		echo "</div>";
	}
	
	
	if($response->hasData("DirectResults"))
	{
		
		echo "<div class=\"leveldivider\">Träffar för \"".$q."\":</div>";

		foreach($response->getCollection("DirectResults") as $item)
		{
			echo "<div class=\"result\">";
			echo "<img src=\"" . $imagePrefix . $item->entity->getStringAttribute($imageAttribute) . "\" alt=\"\" />";
			echo "<a href=\"Entity.php?engine=".$e."&id=".$item->entity->externalId . "\">" . $item->entity->getStringAttribute($titleAttribute) . "</a>";
			echo $item->entity->getStringAttribute("Description");
			echo "</div>";
		}
	}
	
	if($response->hasData("RecommendedResults"))
	{
		echo "<div class=\"leveldivider\">Kolla även in:</div>";
		
		foreach($response->getCollection("RecommendedResults") as $item)
		{
			echo "<div class=\"result\">";
			echo "<img src=\"" . $imagePrefix . $item->entity->getStringAttribute($imageAttribute) . "\" alt=\"\" />";
			echo "<a href=\"Entity.php?engine=".$e."&id=".$item->entity->externalId . "\">" . $item->entity->getStringAttribute($titleAttribute) . "</a>";
			echo $item->entity->getStringAttribute("Description");
			echo "</div>";
		}
	}
	
	
}

?>
</div>
</body>
</html>