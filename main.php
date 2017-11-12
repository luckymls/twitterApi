<?php

//Richiesta

	
$context = stream_context_create(['http'=> ['header' => 'accept-language:it-IT,it;q=0.8,en-US;q=0.6,en;q=0.4']]);
$result = file_get_contents('https://twitter.com/'.$argv[1], 0, $context);


//Estrapolo
function get($start, $end, $end_char = 0){
global $result;
if($end_char) $end_char = '<';
else $end_char = ' ';
$start = strpos(' '.$result, $start);
$lim = substr($result, $start, $start+300);
$end = explode($end, $lim)[1];
return explode($end_char ,$end)[0];
}

//Nome
$nome = explode('</', explode('ProfileHeaderCard-nameLink u-textInheritColor js-nav">',$result)[1])[0];
if(!$nome) $nome = false;

//Tweet
$tweet = get('<span class="ProfileNav-label" aria-hidden="true">Tweet</span>', '<span class="ProfileNav-value"  data-count=');
if(!$tweet) $tweet = 0;

//Following
$following = get('<span class="ProfileNav-label" aria-hidden="true">Following</span>','<span class="ProfileNav-value" data-count=');
if(!$following) $following = 0;

//Followers
$followers = get('<span class="ProfileNav-label" aria-hidden="true">Follower</span>', '<span class="ProfileNav-value" data-count=');
if(!$followers) $followers = 0;

//Likes
$likes = get('<span class="ProfileNav-label" aria-hidden="true">Mi piace</span>', '<span class="ProfileNav-value" data-count=');
if(!$likes) $likes = 0;

//Momenti
$momenti = get('<span class="ProfileNav-label" aria-hidden="true">Momenti</span>', '<span class="ProfileNav-value" data-is-compact="false">', 1);
if(!$momenti) $momenti = 0;

//Biografia
$bio = get('<h2 class="ProfileHeaderCard-screenname u-inlineBlock u-dir" dir="ltr">', 'ProfileHeaderCard-bio u-dir" dir="ltr">', 1);
if(!$bio) $bio = false;

//Avatar
$avatar = explode('"', explode('<img class="ProfileAvatar-image " src="',$result)[1])[0];
if(!$avatar) $avatar = false;

//Privato
if(!$tweet && !$following && $followers && stripos(' '.$result, 'I Tweet di questo account sono protetti.')) $privato = true; else $privato = false;

//NoTweet
if(!$tweet && stripos(' '.$result,'Quando lo farà, i suoi Tweet verranno mostrati qui.')) $notweet = true; else $notweet = false;

//Sospeso
if(stripos(' '.$result, '<title>Twitter / Account sospeso</title>')) $sospeso = true; else $sospeso = false;

//Inesistente
if(!$result) $inesistente = true; else $inesistente = false;

//Settings
if($privato) $tweet = $following = $likes = $momenti = $bio = $avatar = 'account_private';



$data =
['tweet' => $tweet,
 'following' => $following,
 'followers' => $followers,
 'likes' => $likes,
 'moments' => $momenti,
 'bio' => $bio,
 'avatar' => $avatar,
 'account_name' => $nome,
 'account_link' => 'https://twitter.com/'.$argv[1],
 'account_private' => $privato,
 'account_never_tweet' => $notweet,
 'account_suspended' => $sospeso,
 'account_not_exists' => $inesistente];
 
 
 echo json_encode($data, JSON_PRETTY_PRINT);
 
