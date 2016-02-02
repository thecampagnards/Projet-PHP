<?php

if(!isset($_SESSION['langue'])){
	$_SESSION['langue']=='fr';
}

if(isset($_GET['lang'])){
	$_SESSION['langue']=$_GET['lang'];
}

function top_panel(){
	if($_SESSION['langue']=='ru'){
		echo'<div id="cssmenu"><ul>';
		//si l'utilisteur est un admin
		echo '<li><a href="index.php"> домой </a></li>';
		if(isset($_SESSION['admin'])&&$_SESSION['admin']!='0'){
			echo '<li><a href="livre.php"> добавить книгу </a></li>';
		}
		//utilisateur normal
		else if(!isset($_SESSION['user'])){
			echo '<li><a href="inscription.php">Создать аккаунт</a></li>';
		}
		if(isset($_SESSION['user'])){
		echo '
			<li><a href="compte.php"> Управление Аккаунтом </a></li>
			<li><p id="user" style="color:#34495e;"> '.$_SESSION['user'].'<p></li>
			<li class="last"><form method="post" action="index.php">
				<button type="submit" value="Deconnexion" name="deconnect" class="btn btn-1 btn-1a btn-deco">выйти</button>
			</form></li>
			';
			
		}
		else{
			/*echo '<li  class="last"><form method="post" action="connexion.php" >
				<input class="co silver-flat-button" type="submit" name="connect" value="Connexion">
			</form></li>';*/
			echo '<li><form style="margin-left:40px;" method="post" action="checklogin.php">
				<input required name="myusername" type="text" placeholder="Войти">
				<input required  name="mypassword" type="password" placeholder="пароль">
				<button type="submit" type="submit" name="Submit" class="btn btn-1 btn-1a btn-deco">связь</button>
			</form></li>';	
		}
	}
	else{
		echo'<div id="cssmenu"><ul>';
		//si l'utilisteur est un admin
		echo '<li><a href="index.php"> Accueil </a></li>';
		if(isset($_SESSION['admin'])&&$_SESSION['admin']!='0'){
			echo '<li><a href="livre.php"> Ajouter un livre </a></li>';
		}
		//utilisateur normal
		else if(!isset($_SESSION['user'])){
			echo '<li><a href="inscription.php">Créer un compte</a></li>';
		}
		if(isset($_SESSION['user'])){
		echo '
			<li><a href="compte.php"> Gestion du compte </a></li>
			<li><p id="user" style="color:#34495e;"> '.$_SESSION['user'].'<p></li>
			<li class="last"><form method="post" action="index.php">
				<button type="submit" value="Deconnexion" name="deconnect" class="btn btn-1 btn-1a btn-deco">Deconnexion</button>
			</form></li>
			';
			
		}
		else{
			/*echo '<li  class="last"><form method="post" action="connexion.php" >
				<input class="co silver-flat-button" type="submit" name="connect" value="Connexion">
			</form></li>';*/
			echo '<li><form style="margin-left:40px;" method="post" action="checklogin.php">
				<input required name="myusername" type="text" placeholder="Login">
				<input required  name="mypassword" type="password" placeholder="Mot de passe">
				<button type="submit" type="submit" name="Submit" class="btn btn-1 btn-1a btn-deco">Connexion</button>
			</form></li>';	
		}
		
	}
	if($_SESSION['langue']=='ru'){
			echo'<li><nav class="cl-effect-4">
					<a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"].'?lang=fr">FR</a>
				</nav></li>';
		}
		else{
			echo '<li><nav class="cl-effect-4">
					<a href="http://'.$_SERVER['HTTP_HOST'].$_SERVER["PHP_SELF"].'?lang=ru">RU</a>
				</nav></li>';
		}
		echo '</ul></div>';
}
?>