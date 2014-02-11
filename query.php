<?php
//Query voor de lijst met games
$queryGamelist = "SELECT * FROM gamesApi";

//Query voor een bepaalde id
$id = $_GET['id'];
$queryId = "SELECT * FROM gamesApi WHERE id=" . $id;

//Query voor een bepaalde publisher
$publisher = $_GET['publisher'];
$queryPublisher = "SELECT * FROM gamesApi WHERE uitgever LIKE '%" . $publisher . "%'";

//Query voor de lijst met gamenamen
$queryNames = "SELECT `naam` FROM gamesApi";

//Query voor de lijst met alle games met of zonder multiplayer
$multiplayer = $_GET['multiplayer'];
$queryMultiplayer = "SELECT * FROM gamesApi WHERE multiplayer LIKE '%" . $multiplayer . "%'";



?>
