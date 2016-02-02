<?php session_start();
if(!isset($_SESSION['user'])){
	header('Location: index.php'); 
}
include('db.php');
include('fonctions.php');

$db=db_connect();
if(isset($_POST['demande'])){
		$name=$_POST['pseudo'];
		$name = stripslashes($name);
		$name = mysqli_real_escape_string($db,$name);
		$num_exemplaire=$_POST['num_exemplaire'];
		$num_exemplaire = stripslashes($num_exemplaire);
		$num_exemplaire = mysqli_real_escape_string($db,$num_exemplaire);
		$requete=mysqli_query($db,"UPDATE exemplaire SET disponible=0 WHERE `num_exemplaire`='$num_exemplaire'");

		$requete=mysqli_query($db,"INSERT INTO emprunt (pseudo, num_exemplaire,date_fin_emprunt) VALUES ('$name', '$num_exemplaire', CONVERT(CURRENT_DATE() + 21, DATETIME))");
		//$requete=mysqli_query($db,"UPDATE emprunt SET date_fin_emprunt + 21 DAYS WHERE `num_exemplaire`='$num_exemplaire'");
		$requete=mysqli_query($db,"DELETE FROM `demande` WHERE `num_exemplaire`='$num_exemplaire'");
}
if(isset($_POST['supprimer_demande'])){
		$num_exemplaire=$_POST['num_exemplaire'];
		$num_exemplaire = stripslashes($num_exemplaire);
		$num_exemplaire = mysqli_real_escape_string($db,$num_exemplaire);
		
		$requete=mysqli_query($db,"DELETE FROM `demande` WHERE `num_exemplaire`='$num_exemplaire'");
		$requete=mysqli_query($db,"UPDATE exemplaire SET disponible=1 WHERE `num_exemplaire`='$num_exemplaire'");
}
if(isset($_POST['rendu'])){

		$num_exemplaire=$_POST['num_exemplaire'];
		$num_exemplaire = stripslashes($num_exemplaire);
		$num_exemplaire = mysqli_real_escape_string($db,$num_exemplaire);
		
		$requete=mysqli_query($db,"DELETE FROM `emprunt` WHERE `num_exemplaire`='$num_exemplaire'");
		$requete=mysqli_query($db,"UPDATE exemplaire SET disponible=1 WHERE `num_exemplaire`='$num_exemplaire'");
	}

function body_panel(){
	$db=db_connect();
	$name=$_SESSION['user'];
	$name = stripslashes($name);
	$name = mysqli_real_escape_string($db,$name);
	
	//assignation
	if(isset($_SESSION['admin'])&&$_SESSION['admin']=='2'){
	if($_SESSION['langue']=='ru'){
		echo '<br><h1>Связать книгу :</h1><br>';
	}
	else{
		echo '<br><h1>Assigner un livre :</h1><br>';
	}
	$requete=mysqli_query($db,"SELECT * FROM utilisateur");
	echo '<form method="post" action="compte.php">
	<select required name="pseudo"><option value="" disabled selected>';
	if($_SESSION['langue']=='ru'){
		echo 'пользователь';
	}
	else{
		echo 'Utilisateur';
	}
	echo'</option>';
	while($row_cat=mysqli_fetch_array($requete)){
		echo'<option value="'.$row_cat['pseudo'].'">'.$row_cat['pseudo'].'</option>';
	}
	echo '</select>';
	echo '<select required name="num_exemplaire">';
	$requete=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,num_exemplaire FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) AND exemplaire.disponible=1");
	echo'<option value="" disabled selected>';
	if($_SESSION['langue']=='ru'){
		echo 'книга';
	}
	else{
		echo 'Livre';
	}
	echo'</option>';
	while($row_cat=mysqli_fetch_array($requete)){
		echo'<option value="'.$row_cat['num_exemplaire'].'">'.$row_cat['titre'].' - '.$row_cat['num_exemplaire'].'</option>';	
	}
	echo '</select>
	<input class="co silver-flat-button" type="submit" name="demande" value="';
	if($_SESSION['langue']=='ru'){
		echo 'Связать книгу';
	}
	else{
		echo 'Assigner le livre';
	}
	echo '">
	</form><br>';
	//admin affiche toute les demandes
	$requete=mysqli_query($db,"SELECT * FROM demande d,exemplaire e,livre l WHERE (d.num_exemplaire=e.num_exemplaire AND e.isbn=l.isbn)");//AND e.isbn=l.isbn
	if($_SESSION['langue']=='ru'){
		echo '<h1>Запрос :</h1><br>';
	}
	else{
		echo '<h1>Demande(s) :</h1><br>';
	}
	while($row=mysqli_fetch_array($requete)){
		if($_SESSION['langue']=='ru'){
			echo '<div class="demande"><p>пользователь : '.$row['pseudo'].'</p>
			<p>Скопировать номер : '.$row['num_exemplaire'].'</p>
			<p>ISBN : '.$row['isbn'].'</p>
			<p>название : '.$row['titre'].'</p>
			<br>
			<form method="post" action="compte.php">
				<input style="display:none;" name="pseudo" value="'.$row['pseudo'].'" >
				<input style="display:none;" name="num_exemplaire" value="'.$row['num_exemplaire'].'" >
				<input class="co silver-flat-button" type="submit" name="demande" value="Подтвердить запрос">
				<input class="co silver-flat-button" type="submit" name="supprimer_demande" value="Удалить приложение">
			</form>
			</div>
			<br>
			';
		}
		else{
			echo '<div class="demande"><p>Utilisateur : '.$row['pseudo'].'</p>
			<p>Exemplaire numéro : '.$row['num_exemplaire'].'</p>
			<p>ISBN : '.$row['isbn'].'</p>
			<p>Titre : '.$row['titre'].'</p>
			<br>
			<form method="post" action="compte.php">
				<input style="display:none;" name="pseudo" value="'.$row['pseudo'].'" >
				<input style="display:none;" name="num_exemplaire" value="'.$row['num_exemplaire'].'" >
				<input class="co silver-flat-button" type="submit" name="demande" value="Valider la demande">
				<input class="co silver-flat-button" type="submit" name="supprimer_demande" value="Supprimer la demande">
			</form>
			</div>
			<br>
			';
		}
	}
	//affiche tous les emprunts en cours
	$requete=mysqli_query($db,"SELECT * FROM emprunt t,exemplaire e,livre l WHERE (t.num_exemplaire=e.num_exemplaire AND e.isbn=l.isbn)");//AND e.isbn=l.isbn
	if($_SESSION['langue']=='ru'){
		echo '<h1>заимствование :</h1><br>';
	}
	else{
		echo '<h1>Emprunt(s) :</h1><br>';
	}
	while($row=mysqli_fetch_array($requete)){
		if($_SESSION['langue']=='ru'){
			$date = strtotime("+21 days" , strtotime($row['date_fin_emprunt']));
			echo '<div class="demande"><p>пользователь : '.$row['pseudo'].'</p>
			<p>Скопировать номер : '.$row['num_exemplaire'].'</p>
			<p>ISBN : '.$row['isbn'].'</p>
			<p>название : '.$row['titre'].'</p>
			<p style="color:';
			if(date("d-m-Y") >= date("d-m-Y", strtotime($row['date_fin_emprunt']))){
				echo 'red;';
			}
			echo '"> Дата Возврата : '.date("d-m-Y", strtotime($row['date_fin_emprunt'])).'</p>
			<br>
			<form method="post" action="compte.php">
				<input style="display:none;" name="num_exemplaire" value="'.$row['num_exemplaire'].'" >
				<input class="co silver-flat-button" type="submit" name="rendu" value="Оказание">
			</form>
			</div>
			<br>
			';
		}
		else{
			$date = strtotime("+21 days" , strtotime($row['date_fin_emprunt']));
			echo '<div class="demande"><p>Utilisateur : '.$row['pseudo'].'</p>
			<p>Exemplaire numéro : '.$row['num_exemplaire'].'</p>
			<p>ISBN : '.$row['isbn'].'</p>
			<p>Titre : '.$row['titre'].'</p>
			<p style="color:';
			if(date("d-m-Y") >= date("d-m-Y", strtotime($row['date_fin_emprunt']))){
				echo 'red;';
			}
			echo '"> Date de retour : '.date("d-m-Y", strtotime($row['date_fin_emprunt'])).'</p>
			<br>
			<form method="post" action="compte.php">
				<input style="display:none;" name="num_exemplaire" value="'.$row['num_exemplaire'].'" >
				<input class="co silver-flat-button" type="submit" name="rendu" value="Rendu">
			</form>
			</div>
			<br>
			';
		}
	}
	}
	else{
		$requete=mysqli_query($db,"SELECT * FROM demande d,exemplaire e,livre l WHERE (d.num_exemplaire=e.num_exemplaire AND e.isbn=l.isbn AND pseudo='$name')");//AND e.isbn=l.isbn
		if($_SESSION['langue']=='ru'){
			echo '<h1>Ваш запрос :</h1><br>';
		}
		else{
			echo '<h1>Vos demande(s) :</h1><br>';
		}
	while($row=mysqli_fetch_array($requete)){
		if($_SESSION['langue']=='ru'){
			echo '<div class="demande">
			<p>Скопировать номер : '.$row['num_exemplaire'].'</p>
			<p>ISBN : '.$row['isbn'].'</p>
			<p>название : '.$row['titre'].'</p>
			<br>
			<form method="post" action="compte.php">
				<input style="display:none;" name="pseudo" value="'.$row['pseudo'].'" >
				<input style="display:none;" name="num_exemplaire" value="'.$row['num_exemplaire'].'" >
				<input class="co silver-flat-button" type="submit" name="supprimer_demande" value="Удалить запрос">
			</form>
			</div>
			<br>
			';
		}
		else{
			echo '<div class="demande">
			<p>Exemplaire numéro : '.$row['num_exemplaire'].'</p>
			<p>ISBN : '.$row['isbn'].'</p>
			<p>Titre : '.$row['titre'].'</p>
			<br>
			<form method="post" action="compte.php">
				<input style="display:none;" name="pseudo" value="'.$row['pseudo'].'" >
				<input style="display:none;" name="num_exemplaire" value="'.$row['num_exemplaire'].'" >
				<input class="co silver-flat-button" type="submit" name="supprimer_demande" value="Supprimer la demande">
			</form>
			</div>
			<br>
			';
		}
	}
	}
	//user normal voit les livres qu'il empreinte
	$requete=mysqli_query($db,"SELECT * FROM emprunt t,exemplaire e,livre l WHERE (t.num_exemplaire=e.num_exemplaire AND e.isbn=l.isbn AND pseudo='$name')");
	if($_SESSION['langue']=='ru'){
		echo '<h1>Ваш кредит :</h1><br>';
	}
	else{
		echo '<h1>Vos Emprunt(s) :</h1><br>';
	}
	while($row=mysqli_fetch_array($requete)){
		if($_SESSION['langue']=='ru'){
			echo '<div class="demande">
			<p>ISBN : '.$row['isbn'].'</p>
			<p>название : '.$row['titre'].'</p>
			<p style="color:';
			if(date("d-m-Y") >= date("d-m-Y", strtotime($row['date_fin_emprunt']))){
				echo 'red;';
			}
			echo '"> Дата Возврата : '.date("d-m-Y", strtotime($row['date_fin_emprunt'])).'</p>
			<br>
			</div>
			<br>
			';
		}
		else{
			echo '<div class="demande">
			<p>ISBN : '.$row['isbn'].'</p>
			<p>Titre : '.$row['titre'].'</p>
						<p style="color:';
			if(date("d-m-Y") >= date("d-m-Y", strtotime($row['date_fin_emprunt']))){
				echo 'red;';
			}
			echo '"> Date de retour : '.date("d-m-Y", strtotime($row['date_fin_emprunt'])).'</p>
			<br>
			</div>
			<br>
			';
		}
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
				echo '<h1 class="titre">мой счет</h1>';
			}
			else{
				echo '<h1 class="titre">Mon Compte</h1>';
			}
			?>
			<div id="mid_emprunt">
				<?php body_panel(); ?>
			</div>
		</section>
    </body>
</html> 