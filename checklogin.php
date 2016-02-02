<?php session_start();

include('db.php');

$db=db_connect();
$tbl_name="members";

if(!isset($_POST['myusername'])||!isset($_POST['mypassword'])){
	$user="";
	$password="";
}
else{
	// recuparation des identifiants grace au form
	$user=$_POST['myusername'];
	$password=$_POST['mypassword'];
}

// protection aux injections mysql
$user = stripslashes($user);
$password = stripslashes($password);
$user = mysqli_real_escape_string($db,$user);
$password = mysqli_real_escape_string($db,$password);
$password=md5($password);

$requete=mysqli_query($db,"SELECT * FROM utilisateur WHERE pseudo='$user' and mot_de_passe='$password'");

// recherche si il y a un ligne de la table qui comporte le mdp et l'identifiant
$count=mysqli_num_rows($requete);
$requete=mysqli_fetch_array($requete);

// si il en a trouvé une 1 c'est ok il est connedcté
if($count==1){
	$_SESSION['user']= $user;
	$_SESSION['admin'] = $requete['niveau'];
	$language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$language = $language{0}.$language{1};
	if(!isset($_SESSION['langue'])){
		$_SESSION['langue'] = $language;
	}

	header('Location: index.php'); 
}
// mauvais identifiants avec redirection sur l'index
else {
	header('Refresh: 3; url=index.php'); 
	echo "Mauvais mot de passe ou identifiant.<br>Vous allez Ãªtre redirigÃ© vers la page d'accueil dans 3 secondes.";
}

?>