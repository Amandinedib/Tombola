<?php
session_start();


define('argentDepart', 500);
define('prixTicket', 2);
define('ticketMax', 100);
define('prix1', 100);
define('prix2', 50);
define('prix3', 20);

function ticketRestant ($ticketJoueur) {
	return array_diff(range(1, ticketMax), $ticketJoueur);
}

function ticketAchetable ($argent, $ticketRestant) {
	$maxAchetable = floor($argent / prixTicket);
	$maxTicket = count($ticketRestant);
	return min($maxAchetable, $maxTicket);
}

function acheterTicket ($ticketPris, $argent, $ticketRestant) {
	$nbTicket = min($ticketPris, ticketAchetable($argent, $ticketRestant));
	$argent -= $nbTicket * prixTicket;
	return tirage($nbTicket, $ticketRestant);
}
function tirage ($nbTicket, $ticketRestant) {
	$resultat = array();
	for ($i=0; $i < $nbTicket; $i++) {
		$cle = array_rand($ticketRestant);
		$resultat[] = $ticketRestant[$cle];
		unset($ticketRestant[$cle]);
	} return $resultat;
}

//VERIFICATION ARGENT PAR RAPPORT ARGENT DE DEPART
if (!isset($_SESSION['argent'])) {
	$_SESSION['argent'] = argentDepart;
}

if (!isset($_SESSION['ticketJoueur'])) {
	$_SESSION['ticketJoueur'] = array();
}
// TICKET JOUEURS 
$ticketRestant = ticketRestant($_SESSION['ticketJoueur']);

if (isset($_POST['nbTicket']) && is_numeric($_POST['nbTicket']) && $_POST['nbTicket'] > 0) {
	$ticket = acheterTicket($_POST['nbTicket'], $_SESSION['argent'], $ticketRestant);
	$_SESSION['ticketJoueur'] = array_merge($_SESSION['ticketJoueur'], $ticket);
}

// print_r($_SESSION);
// print_r($_POST);		

$nbTicket = count($_SESSION['ticketJoueur']);




?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style type="text/css">
	body{
		text-align: center;
		box-sizing: border-box;
	}
		form{
			background-color:#9bc9bd;
			width: 500px;
			height: 500px;
			border-radius: 10px;
			margin: 0px auto 6px auto;
		}
		p{
			font-family: tahoma;
			font-size: 20px;
			font-weight: bold;
			text-transform: uppercase;
			color: white;
			padding-top: 20px;
		}
		.saisie{
			padding: 10px;
			border: none;
			margin-top: 20px;
		}
		.bouton{
			padding: 10px 30px 10px 30px;
			margin-top: 30px;
			background-color: #85a39b;
			border: none;
			box-shadow: 5px 4px 2px #6e938a;
			color: white;
			cursor: pointer;
		}
		a{
    		background-color: #9bc9bd;
    		padding:6px;
    		color: white;
    		text-decoration: none;
    		font-family: tahoma;
    		font-weight: bold;
    		font-size: 14px;
    		border-radius: 0px 0px 5px 5px;

		}
		a:hover{
		-moz-transform: scale(1.1);
  		-webkit-transform: scale(1.1);
  		transform: scale(1.1);
		}
		.ticketPecule{
			text-align: center;
			font-family: tahoma;
			font-weight: bold;
			font-size: 14px;
			color: black;
			margin: 0px 40px 0px 40px;
			background-color: white;
			border: 15px #9bc9bd solid;
			padding: 10px;

		}
		
		.tomb p{

			color: white;
			padding: 10px;

		}
		.tomb{
			width: 215px;
    		height: 30px;
    		margin: 0px auto 0px auto;
    		background-color: #9bc9bd;
    		border-radius: 5px 5px 0px 0px ;
		}


	</style>
</head>
<body>
<div class="tomb"><p>Tombola</p></div>
<form class="tombola" method="POST">
<div class="ticketPecule">
<?php

if ($nbTicket >0) {
	echo "<br><br>Vos tickets : " . $_POST['nbTicket'];
}


echo "<br><br> Votre pécule : " . $_SESSION['argent'] . " euros !<br>";
echo '<br>';

$tickets = range(1, ticketMax);
$gagnants = tirage(3, $tickets);

$prix = array(prix1, prix2, prix3);
echo 'Vos tickets : ' . implode(', ', $_SESSION['ticketJoueur']) . '<br>';
echo 'Tickets gagnants : ' . implode(', ', $gagnants) . '<br>';

$gains = 0;
for($i = 0; $i < 3; $i++) {
    if(in_array($gagnants[$i], $_SESSION['ticketJoueur'])) {
        $gains += $prix[$i];
    }
}

if($gains != 0) {
    echo 'Vous avez gagné ' . $gains . '€!<br>';
} else {
	echo '<br>';
    echo 'Dommage! Vous n\'avez rien gagné!<br>';
}



unset($_SESSION['ticketJoueur']);
$_SESSION['argent'] += $gains


?>
</div>
<p>Combien de tickets voulez-vous ?</p>
	<input type="text" class="saisie" placeholder="Entrer un nombre de tickets" name="nbTicket">
	<br>
	<input type="submit" class="bouton" value="Valider">

</form>
	<a href="decotombola.php">Let's do the time warp again !</a>
<br>
<?php



?>



<br>
	
</body>
</html>