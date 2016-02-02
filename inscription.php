<?php session_start();

include('db.php');
include('fonctions.php');

$db=db_connect();

if(isset($_POST['login'])&&isset($_POST['password'])){

// recuparation des identifiants grace au form
$user=$_POST['login'];
$password=$_POST['password'];

// protection aux injections mysql
$user = stripslashes($user);
$user = mysqli_real_escape_string($db,$user);

$requete=mysqli_query($db,"SELECT pseudo FROM utilisateur WHERE pseudo='$user'");
// recherche si il y a un ligne de la table qui comporte le mdp et l'identifiant

if(!$requete=mysqli_fetch_array($requete)){
	$password = stripslashes($password);
	$password = mysqli_real_escape_string($db,$password);
	$password=md5($password);
	$_SESSION['user']= $user;
	$_SESSION['admin']= '0';
	$requete=mysqli_query($db,"INSERT INTO utilisateur (pseudo,mot_de_passe) VALUES ('$user','$password')");
	header('Location: index.php'); 
}
else{
	 echo' <script>alert("Le pseudo '.$user.' existe déjà.")</script>';
}

}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Librairie</title>
		<link rel="stylesheet" href="css/style.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />

    </head>

    <body>

	<a href="index.php"><img class="image-header" src="image/header.jpg"/></a>
		<section>
			<div id="panel">
				<?php top_panel(); ?>
			</div>
			<br>
			<?php
			if($_SESSION['langue']=='ru'){
				echo '<h1 class="titre">Создать аккаунт</h1>';
			}
			else{
				echo '<h1 class="titre">Créer un compte</h1>';
			}
			?>
			<br>
			<br>
			<?php if($_SESSION['langue']=='ru') { ?>
				<form  id="form-creation" action="inscription.php" method="post">
					<p>пользователь : </p>
					<input required name="login" type="text" value="" placeholder="пользователь">
					<p>пароль : </p>
					<input required name="password" type="password" value="" placeholder="пароль">
					<br>
					<br>
					<button type="submit" type="submit" name="Submit" class="btn btn-1 btn-1a btn-livre">Создать</button>
				</form>
			<?php }else{?>
				<form  id="form-creation" action="inscription.php" method="post">
					<p>Pseudo : </p>
					<input required name="login" type="text" value="" placeholder="Login">
					<p>Mot de passe : </p>
					<input required name="password" type="password" value="" placeholder="Mot de passe">
					<br>
					<br>
					<button type="submit" type="submit" name="Submit" class="btn btn-1 btn-1a btn-livre">S'inscrire</button>

				</form>
			<?php }?>
		</section>
    </body>
</html> 