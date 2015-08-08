<?php


/**
 * Twitter-words-generator-php: Generates words using what people are saying on twitter
 * 
 * PHP version 5.3.10
 * 
 * @category Awesomeness
 * @package  Twitter-words-generator-php
 * @author   Nawwar El Narsh
 * @license  MIT License
 * @link     http://github.com/sirnarsh/Twitter-words-generator-php
 * @depends on TwitterAPIExchange  http://github.com/j7mbo/twitter-api-php
 */
 

function generatewords($woeid=1,$twitterauth)
{
require_once('TwitterAPIExchange.php');
$url = 'https://api.twitter.com/1.1/trends/place.json';
$getfield = '?id='.$woeid;
$requestMethod = 'GET';
$twitter = new TwitterAPIExchange($settings);
$twitterreply_trends= $twitter->setGetfield($getfield)
             ->buildOauth($url, $requestMethod)
             ->performRequest();
			 
$decodedreply_trends =json_decode($twitterreply_trends,true);

$words=array();
$filter=array("RT","_","\n",".","#","/","\"","?","=","&","@","!",":",";","❤","🎉","😐",",","…",")","(","%","$","#","-","^","*","¡");

foreach($decodedreply_trends[0]['trends'] as $trend)
{
	$trendquery= $trend['query'];
	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	$getfield = '?q='.$trendquery;
	$requestMethod = 'GET';
	$twitterreply_search= $twitter->setGetfield($getfield)
	             ->buildOauth($url, $requestMethod)
	             ->performRequest();
				 
	$search_result =json_decode($twitterreply_search,true);
	//var_dump($search_result);
	foreach($search_result['statuses'] as $tweet)
	{

$tweettext=$tweet['text'];
//$tweettext=preg_replace('/\P{Xan}+/u', '',$tweettext );
//$tweettext = preg_replace( '/[^\p{L}\p{N}]+/u', '', $tweettext );
//$tweettext = preg_replace('@[^\x{0900}-\x{097F}]@u', '', $tweettext);

		$newwords=explode(" ",$tweettext);
foreach($newwords as $newword)
{
	if(strpos($newword,"http") === false)
	{
		$newword=	str_replace($filter,"",	$newword);

	if(!in_array($newword,$words) && strlen($newword)>1 && strlen($newword)<25)
	{
	$words[]=$newword;
	}
	}
}

}
}

return $words;
}
?>