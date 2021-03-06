<?php
	session_start();        
?>
<html>
	<head>
		<title>Spot Me</title>
		<link href="/spotme/css/home.css?v=<?=time();?>" type="text/css" rel="stylesheet"/>
		<link rel="icon" type="image/ico" href="/spotme/favicon.ico"/>
		<script type="text/javascript" src="/spotme/js/jquery.js"></script>
	</head>
	<body>
		<div id="home">
		</div>
		<div id="header">
			<h1>Spot Me</h1>		
			<p>
				<?php

					require 'vendor/autoload.php';
					if (isset($_SESSION['token'])) { 
						$api = new SpotifyWebAPI\SpotifyWebAPI();
						$accessToken = $_SESSION['token'];
						$me = $_SESSION['user'];
						$song = $_SESSION['song'];
						$api->setAccessToken($accessToken);
					}
					$name = $_POST['name'];
					$danceability = $_POST['danceability'];
					$energy = $_POST['energy'];
					$loudness = $_POST['loudness'];
					
					$api->createUserPlaylist($me, [
						'name' => $name,
					]);
					
					$recommendations = $api->getRecommendations([
						'seed_tracks' => [$song], 'limit' => 50, 'danceability' => [$danceability], 'energy' => [$energy],
						'loudness' => [$loudness],
					]);
				
					$playlists = $api->getUserPlaylists($me, [
                                            'limit' => 5
                                        ]);
					$myPlaylist = $playlists -> items[0] -> id;
					$cutoff = count($recommendations -> tracks);
					
					
					for($i=0; $i<$cutoff; $i++){
						$addtrack[$i]= $recommendations -> tracks[$i] -> id;
					}

					$api->addUserPlaylistTracks($me, $myPlaylist, $addtrack);

				?>
			</p>
                        <p id="playsongs"> <a target="_blank" href="https://open.spotify.com/user/<?php echo $me ?>/playlist/<?php echo $myPlaylist ?>"> <?php echo $name ?> </a> has been created </p>
			<p id="clickHere"> <a href="authorize.php">Click Here To Create a New Playlist</a> </p>
             
	</body>
</html>	
