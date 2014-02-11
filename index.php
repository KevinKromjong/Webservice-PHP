<?php
include "connect.php";
include "query.php";


if ($_SERVER['REQUEST_METHOD'] == "GET") {
    if (isset($_GET['format'])) {
        $format = $_GET['format'];

        if ($format == "json") {
            header('Content-type: application/json');

            if (isset($_GET['id'])) {
                $result = mysqli_query($dbConnect, $queryId);
            }
            if (isset($_GET['publisher'])) {
                $result = mysqli_query($dbConnect, $queryPublisher);
            }
            if (isset($_GET['list']) == true) {
                $result = mysqli_query($dbConnect, $queryGamelist);
            }
            if (isset($_GET['games']) == true) {
                $resultGames = mysqli_query($dbConnect, $queryNames);
            }

            if (isset($_GET['multiplayer'])) {
                $result = mysqli_query($dbConnect, $queryMultiplayer);
            }

            if ($resultGames) {
                while ($row = mysqli_fetch_assoc($resultGames)) {
                    $json_output[] = $row['naam'];
                }
                echo json_encode($json_output);
            } else {
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $json_output[] = $row;
                    }
                    header("HTTP/1.0 200 OK");
                    echo json_encode($json_output);
                } else {
                    header("HTTP/1.0 400 Bad Request");
                    $response['status'] = array(
                        'type' => 'Error',
                        'bericht' => 'Er is niks gevonden met deze zoekopdracht',
                    );

                    echo json_encode($response);

                }
            }
        }

        if ($format == "xml") {
            header("Content-Type:text/xml");

            if (isset($_GET['list']) == true) {
                $result = mysqli_query($dbConnect, $queryGamelist);
            }
            if (isset($_GET['id'])) {
                $result = mysqli_query($dbConnect, $queryId);
            }
            if (isset($_GET['games']) == true) {
                $resultGames = mysqli_query($dbConnect, $queryNames);
            }
            if (isset($_GET['multiplayer'])) {
                $result = mysqli_query($dbConnect, $queryMultiplayer);
            }
            if (isset($_GET['publisher'])) {
                $result = mysqli_query($dbConnect, $queryPublisher);
            }


            if ($resultGames) {
                echo "<xml version='1.0'>";
                echo "<games>";
                while ($row = mysqli_fetch_assoc($resultGames)) {
                    echo "<naam>" . $row['naam'] . "</naam>";

                }
                echo "</games>";

            } else {
                if (mysqli_num_rows($result)) {
                    echo "<xml version='1.0'>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<game>";
                        echo "<id>" . $row['id'] . "</id>";
                        echo "<naam>" . $row['naam'] . "</naam>";
                        echo "<uitgever>" . $row['uitgever'] . "</uitgever>";
                        echo "<genre>" . $row['genre'] . "</genre>";
                        echo "<console>" . $row['console'] . "</console>";
                        echo "<multiplayer>" . $row['multiplayer'] . "</multiplayer>";
                        echo "<omschrijving>" . $row['omschrijving'] . "</omschrijving>";
                        echo "<rate>" . $row['rating'] . "</rate>";
                        echo "<jaar>" . $row['jaar'] . "</jaar>";
                        echo "</game>";
                    }
                } else {
                    header("HTTP/1.0 400 Bad Request");
                    echo "<xml version='1.0'>";
                    echo "<type>Error</type>";
                    echo "<bericht>Er is niks gevonden met deze zoekopdracht</bericht>";
                }
            }
            echo "</xml>";
        }
    }
    if ($format != "json" && $format != "xml") {
        header("HTTP/1.0 404 Not Found");
        echo "De URL is niet goed gedefinieerd. Je kan alleen xml en json gebruiken. Controleer de documentatie voor meer hulp";
    }

}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_GET['format']) == "json") {

        $postData = file_get_contents("php://input");

        $jsonData = json_decode($postData, true);

        $naam = $jsonData['naam'];
        $uitgever = $jsonData['uitgever'];
        $genre = $jsonData['genre'];
        $console = $jsonData['console'];
        $multiplayer = $jsonData['multiplayer'];
        $omschrijving = $jsonData['omschrijving'];
        $rating = $jsonData['rating'];
        $jaar = $jsonData['jaar'];

        if (isset($naam) && isset($uitgever) && isset($genre) && isset($console) && isset($multiplayer) && isset($omschrijving) && isset($rating) && isset($jaar)) {
            $queryInsertNew = "INSERT INTO `gamesApi`(`id`, `naam`, `uitgever`, `genre`, `console`, `multiplayer`, `omschrijving`, `rating`, `jaar`) VALUES ('', '$naam', '$uitgever', '$genre', '$console', '$multiplayer', '$omschrijving', '$rating', '$jaar')";
            $result = mysqli_query($dbConnect, $queryInsertNew);
            header("HTTP/1.0 201 Created");
            echo "Het spel is succesvol aangemaakt!";
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo "Niet alles is goed ingevuld of de JSON code klopt niet (let op komma's) Controleer de documentatie voor meer hulp ";
        }

    }
}

if ($_SERVER['REQUEST_METHOD'] == "PUT") {
    if (isset($_GET['format']) == "json" && isset($_GET['id'])) {

        $putData = file_get_contents("php://input");

        $jsonData = json_decode($putData, true);

        $id = $jsonData['id'];
        $naam = $jsonData['naam'];
        $uitgever = $jsonData['uitgever'];
        $genre = $jsonData['genre'];
        $console = $jsonData['console'];
        $multiplayer = $jsonData['multiplayer'];
        $omschrijving = $jsonData['omschrijving'];
        $rating = $jsonData['rating'];
        $jaar = $jsonData['jaar'];

        if (isset($id) && isset($naam) && isset($uitgever) && isset($genre) && isset($console) && isset($multiplayer) && isset($omschrijving) && isset($rating) && isset($jaar)) {
            $queryUpdate = "UPDATE `gamesApi` SET `id`='$id',`naam`='$naam',`uitgever`='$uitgever',`genre`='$genre',`console`='$console',`multiplayer`='$multiplayer',`omschrijving`='$omschrijving',`rating`='$rating',`jaar`='$jaar' WHERE `id` = '$id'";
            $result = mysqli_query($dbConnect, $queryUpdate);
            if ($result) {
                header("HTTP/1.0 200 OK");
                echo "Het spel is succesvol aangepast";
            }
        } else {
            header("HTTP/1.0 400 Bad Request");
            echo "Niet alles is goed ingevuld. Let erop dat je een id moet meegeven! Controleer de documentatie voor meer hulp";
        }

    }
}

if ($_SERVER['REQUEST_METHOD'] == "DELETE") {
    if (isset($_GET['format']) == "json" && isset($_GET['id'])) {

        $deleteData = file_get_contents("php://input");

        $jsonData = json_decode($deleteData, true);


        $queryCheckId = "SELECT * FROM gamesApi WHERE `id` = '$id'";
        $result = mysqli_query($dbConnect, $queryCheckId);

        if (mysqli_num_rows($result) == 0) {
            header("HTTP/1.1 400 Bad Request");
            echo "Niet alles is goed ingevuld. Vergeet geen id mee te geven in de URI!";
        } else {
            $queryDelete = "DELETE FROM `gamesApi` WHERE `id` = '$id'";
            $result = mysqli_query($dbConnect, $queryDelete);
            header("HTTP/1.1 200 OK");
            echo "Het spel is succesvol verwijderd!";
        }


    }
}



?>
