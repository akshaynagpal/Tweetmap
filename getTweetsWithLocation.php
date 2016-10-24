<?php
	function getTweetsWithLocation($keyword){
		$ci = curl_init();
		curl_setopt($ci, CURLOPT_URL, "https://search-tweet-db-jar6kiubxgklicyhdocs6p37he.us-east-1.es.amazonaws.com/".$keyword."/_search?size=10000");
  		curl_setopt($ci, CURLOPT_HTTPHEADER, array('Accept: application/json'));
  		curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
  		$get_output = curl_exec($ci);
  		$json_output = json_decode($get_output,true);
  		curl_close($ci);
  		$tweets = $json_output['hits']['hits'];
  		// var_dump($tweets);
  		$num_tweets =  sizeof($tweets);
  		$geoArray = array();
  		for ($i=0; $i < $num_tweets; $i++) {
    		$geoArray[$i]["lat"] = $tweets[$i]['_source']['latitude']; 
    		$geoArray[$i]["long"] = $tweets[$i]['_source']['longtitude'];
    		// echo "lat".$tweets[$i]['_source']['latitude']." lang".$tweets[$i]['_source']['longtitude']."<br>";
  		}
  		return $geoArray;
	}
?>