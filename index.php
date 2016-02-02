<?php session_start();

include('db.php');
include('fonctions.php');

if(isset($_POST['deconnect'])){
	$_SESSION['user']= NULL;
	$_SESSION['admin']=  NULL;
}

function body_panel(){
	$db=db_connect();
	if(isset($_GET['tri'])&&$_GET['tri']=='dec'){
		$requete=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) ORDER BY livre.titre ASC");
	}
	else if(isset($_GET['tri'])&&$_GET['tri']=='date'){
		$requete=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre,livre.date FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) ORDER BY `livre`.`date` DESC");
	}
	else if(isset($_GET['genre'])&&!isset($_GET['categorie'])){
		$genre=$_GET['genre'];
		$genre = stripslashes($genre);
		$genre = mysqli_real_escape_string($db,$genre);
		$requete=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) AND livre.genre='$genre' ");
	}
	else if(isset($_GET['categorie'])&&!isset($_GET['genre'])){
		$cat=$_GET['categorie'];
		$cat = stripslashes($cat);
		$cat = mysqli_real_escape_string($db,$cat);
		$requete=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) AND livre.categorie='$cat' ");
	}
	else if(isset($_GET['categorie'])&&isset($_GET['genre'])){
		$genre=$_GET['genre'];
		$genre = stripslashes($genre);
		$genre = mysqli_real_escape_string($db,$genre);
		$cat=$_GET['categorie'];
		$cat = stripslashes($cat);
		$cat = mysqli_real_escape_string($db,$cat);
				$requete=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) AND livre.categorie='$cat' AND livre.genre='$genre' ");
	}
	else{
		$requete=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) ");
	}
	/*$exemplaire=mysqli_query($db,"SELECT * FROM exemplaire WHERE disponible=1");
		echo mysqli_num_rows($exemplaire);
	$exemplaire=mysqli_fetch_array($exemplaire);*/
	$i=0;
	echo '<br>';

    // parcours et affichage des résultats
    while($post=mysqli_fetch_array($requete)){
		
		$isbn = stripslashes($post['isbn']);
		$isbn = mysqli_real_escape_string($db,$isbn);
		$i++;
		$i=$i%2;
		if($_SESSION['langue']=='ru'){
			echo '<div class="noalign livre" style="background-color:';if($i){echo '#ecf0f1';}echo';">';
			
			//interface livre pour admin
			echo '<div class="interface_livre">';
			$exemplaire=mysqli_query($db,"SELECT COUNT(num_exemplaire) FROM exemplaire WHERE isbn='$isbn'");
			$exemplaire=mysqli_fetch_array($exemplaire);
			if(isset($_SESSION['admin'])&&$_SESSION['admin']!='0'){
				echo '<nav class="cl-effect-4">
					<a href="livre.php?book='.$post['isbn'].'">модификатор</a>
					<a href="index.php?delete='.$post['isbn'].'">удалять</a>
				</nav>
					<h4>ISBN : '.$post['isbn'].'</h4>
					<h4>Kопия : '.$exemplaire['COUNT(num_exemplaire)'].'</h4>';
				}
				
			//interface livre pour utilisateur normal	
			$disponible=mysqli_query($db,"SELECT COUNT(num_exemplaire) FROM exemplaire WHERE isbn='$isbn' AND disponible='1'");
			$disponible=mysqli_fetch_array($disponible);
			echo '<h4>доступный : '.$disponible['COUNT(num_exemplaire)'].'</h4>';

			//rajoute le lien pour emprunter
			$exemplaire=mysqli_query($db,"SELECT * FROM exemplaire WHERE isbn='$isbn' AND disponible=1");
			if(isset($_SESSION['admin'])&&isset($exemplaire)&&$count=mysqli_num_rows($exemplaire)){
				$exemplaire=mysqli_fetch_array($exemplaire);
				echo '<nav class="cl-effect-4">
					<a href="index.php?add='.$post['isbn'].'&exemplaire='.$exemplaire['num_exemplaire'].'">Одолжить</a>
				</nav>';
			}
			else{
				$date_dispo=mysqli_query($db,"SELECT date_fin_emprunt FROM emprunt t,exemplaire e WHERE (t.num_exemplaire=e.num_exemplaire AND e.isbn='$isbn') ORDER BY date_fin_emprunt DESC");
				if($date_dispo=mysqli_fetch_array($date_dispo)){
					echo '<h4>Дата Возврата : '.date("d-m-Y", strtotime($date_dispo['date_fin_emprunt'])).'</h4>';
				}
			}
			echo '</div>';
					
			//affichage du livre
			echo '<br><img class="photobook" src="image/'.$post['isbn'].'.jpeg"/>
				<div class="align" style="margin-bottom:50px;">
					<h1> название : '.$post['titre'].'</h1>
					<h2 >автор : ';
					$auteur=mysqli_query($db,"SELECT * FROM livre_auteur WHERE isbn='$isbn'");
					 while($post_auteur=mysqli_fetch_array($auteur)){
						echo'<span class="auteur"> '.$post_auteur['nom_auteur'].' '.$post_auteur['prenom_auteur'].'</span><br>';
					 }
					echo '</h2>
					<h3>категория : '.$post['categorie'].'</h3>
					<h3>вид : '.$post['genre'].'</h3>
				</div>
				<br><br><br><br><br><br><br><br><br><br><br>
					<p class="resume"><h3> Резюме : </h3><br>'.$post['description'].'</p>
				<br></div><br>';
		}
		else{
			echo '<div class="noalign livre" style="background-color:';if($i){echo '#ecf0f1';}echo';">';
			
			//interface livre pour admin
			echo '<div class="interface_livre">';
			$exemplaire=mysqli_query($db,"SELECT COUNT(num_exemplaire) FROM exemplaire WHERE isbn='$isbn'");
			$exemplaire=mysqli_fetch_array($exemplaire);
			if(isset($_SESSION['admin'])&&$_SESSION['admin']!='0'){
				echo '<nav class="cl-effect-4">
					<a href="livre.php?book='.$post['isbn'].'">Modifier</a>
					<a href="index.php?delete='.$post['isbn'].'">Supprimer</a>
				</nav>
					<h4>ISBN : '.$post['isbn'].'</h4>
					<h4>Exemplaire(s) : '.$exemplaire['COUNT(num_exemplaire)'].'</h4>';
				}
				
			//interface livre pour utilisateur normal	
			$disponible=mysqli_query($db,"SELECT COUNT(num_exemplaire) FROM exemplaire WHERE isbn='$isbn' AND disponible='1'");
			$disponible=mysqli_fetch_array($disponible);
			echo '<h4>Disponible(s) : '.$disponible['COUNT(num_exemplaire)'].'</h4>';

			//rajoute le lien pour emprunter
			$exemplaire=mysqli_query($db,"SELECT * FROM exemplaire WHERE isbn='$isbn' AND disponible=1");
			if(isset($_SESSION['admin'])&&isset($exemplaire)&&$count=mysqli_num_rows($exemplaire)){
				$exemplaire=mysqli_fetch_array($exemplaire);
				echo '<nav class="cl-effect-4">
					<a href="index.php?add='.$post['isbn'].'&exemplaire='.$exemplaire['num_exemplaire'].'">Emprunter</a>
				</nav>';
			}
			else{
				$date_dispo=mysqli_query($db,"SELECT date_fin_emprunt FROM emprunt t,exemplaire e WHERE (t.num_exemplaire=e.num_exemplaire AND e.isbn='$isbn') ORDER BY date_fin_emprunt DESC");
				if($date_dispo=mysqli_fetch_array($date_dispo)){
					echo '<h4>Date de retour : '.date("d-m-Y", strtotime($date_dispo['date_fin_emprunt'])).'</h4>';
				}
			}
			echo '</div>';
					
			//affichage du livre
			echo '<br><img class="photobook" src="image/'.$post['isbn'].'.jpeg"/>
				<div class="align" style="margin-bottom:50px;">
					<h1> Titre : '.$post['titre'].'</h1>
					<h2 >Auteur(s) : ';
					$auteur=mysqli_query($db,"SELECT * FROM livre_auteur WHERE isbn='$isbn'");
					 while($post_auteur=mysqli_fetch_array($auteur)){
						echo'<span class="auteur"> '.$post_auteur['nom_auteur'].' '.$post_auteur['prenom_auteur'].'</span><br>';
					 }
					echo '</h2>
					<h3>Catégorie : '.utf8_encode($post['categorie']).'</h3>
					<h3>Genre : '.utf8_encode($post['genre']).'</h3>
				</div>
				<br><br><br><br><br><br><br><br><br><br><br>
					<p class=" resume"><h3> Résumé : </h3><br>'.$post['description'].'</p>
				<br></div><br>';
		}
	}
}
//pour supprimer un livre
	if(isset($_GET['delete'])&&isset($_SESSION['admin'])&&$_SESSION['admin']=='2'){
		$db=db_connect();
		
		$book=$_GET['delete'];
		$book = stripslashes($book);
		$book = mysqli_real_escape_string($db,$book);
		if($requete=mysqli_query($db,"DELETE FROM exemplaire WHERE isbn='$book' LIMIT 1")){
			//unlink('image/'.$book.'.jpeg');
			$requete=mysqli_query($db,"SELECT titre FROM livre WHERE isbn='$book'");
			$requete=mysqli_fetch_array($requete);
			if($_SESSION['langue']=='ru'){
				echo ' <script>alert("Вы только что удалили копию '.$requete['titre'].'.")</script>';
			}
			else{
				echo ' <script>alert("Vous venz de supprimer '.$requete['titre'].'.")</script>';
			}
		}
		else{
			if($_SESSION['langue']=='ru'){		
				echo '<script>alert("Вы не можете удалять книгу, которая в долгу или спроса")</script>';			
			}
			else{
				echo '<script>alert("Vous ne pouvez pas supprimer ce livre car il est en demande ou en emprunt.")</script>';
			}
		}
	}
//pour emprunter un livre
	if(isset($_GET['add'])&&isset($_GET['exemplaire'])&&isset($_SESSION['user'])){
		$db=db_connect();
		$isbn=$_GET['add'];
		$exemplaire=$_GET['exemplaire'];
		$user=$_SESSION['user'];
		
		$user = stripslashes($user);
		$user = mysqli_real_escape_string($db,$user);
		$exemplaire = stripslashes($exemplaire);
		$exemplaire = mysqli_real_escape_string($db,$exemplaire);

		if(mysqli_num_rows(mysqli_query($db,"SELECT * FROM exemplaire WHERE disponible=1 AND num_exemplaire='$exemplaire'"))&&$requete=mysqli_query($db,"UPDATE exemplaire SET disponible=0 WHERE num_exemplaire='$exemplaire'")){
			$requete=mysqli_query($db,"INSERT INTO demande (pseudo, num_exemplaire, duree) VALUES ('$user', '$exemplaire', 21)");
			$requete=mysqli_query($db,"SELECT titre FROM exemplaire e,livre l WHERE e.isbn=l.isbn AND num_exemplaire='$exemplaire'");
			$requete=mysqli_fetch_array($requete);
			if($_SESSION['langue']=='ru'){			
				echo '<script>alert("Вы просто спросил заимствования '.$requete['titre'].'")</script>';
			}
			else{
				echo ' <script>alert("Vous venez de demander l\'emprunt de '.$requete['titre'].'.")</script>';
			}
		}
		else{
			if($_SESSION['langue']=='ru'){			
				echo '<script>alert("Эта книга не доступна.")</script>';
			}
			else{
				echo ' <script>alert("Ce livre n\'est pas disponible.")</script>';
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
	<script type="text/javascript" src="js/jquery.js"></script> 
	<script type="text/javascript">
		$(document).ready( function() {
	  // détection de la saisie dans le champ de recherche
	  $('#q').keyup( function(){
		$field = $(this);
		$('#results').html(''); // on vide les resultats
				$('#mid').html(''); // on vide les resultats

		$('#ajax-loader').remove(); // on retire le loader
	 
		// on commence à traiter à partir du 2ème caractère saisie
		if( $field.val().length > -1)
		{
		  // on envoie la valeur recherché en GET au fichier de traitement
		  $.ajax({
		type : 'GET', // envoi des données en GET ou POST
		url : 'ajax-search.php' , // url du fichier de traitement
		data : 'q='+$(this).val() , // données à envoyer en  GET ou POST
		beforeSend : function() { // traitements JS à faire AVANT l'envoi
			$field.after(''); // ajout d'un loader pour signifier l'action
		},
		success : function(data){ // traitements JS à faire APRES le retour d'ajax-search.php
			$('#ajax-loader').remove(); // on enleve le loader
			$('#results').html(data); // affichage des résultats dans le bloc
			$('html,body').animate({scrollTop: $('#panel').offset().top}, -50);
		}
		  });
		}		
	  });
	});
	</script>
    <body>
	<a href="index.php"><img class="image-header" src="image/header.jpg"/></a>
		<section>
			<div id="panel">
				<?php top_panel(); ?>
			</div>
			<br>
			<?php
			if($_SESSION['langue']=='ru'){
				echo '<h1 class="titre">библиотека</h1>';
			}
			else{
				echo '<h1 class="titre">Bibliothèque</h1>';
			}
			?>
			<br>
			<?php
			if($_SESSION['langue']=='ru'){
				echo '<p class="align tri">разбираться : </p>';
			}
			else{
				echo '<p class="align tri">Trier : </p>';
			}
			?>
			<nav class="align cl-effect-4" >
					<a href="index.php?tri=dec">A-z</a>
			</nav>
			<nav class="cl-effect-4" >
					<a  href="index.php?tri=date"><?php
			if($_SESSION['langue']=='ru'){
				echo 'дата';
			}
			else{
				echo 'Date';
			}
			?></a>
			</nav>
			<form style="margin-left:15px;"action="index.php" method="get">
				<select name="genre">
					<option value="" disabled selected><?php
						if($_SESSION['langue']=='ru'){
							echo 'вид';
						}
						else{
							echo 'Genre';
						}
					?></option>

				<?php
				$db=db_connect();
				$requete_genre=mysqli_query($db,"SELECT genre FROM genre");
				while($row_genre=mysqli_fetch_array($requete_genre)){
					echo'<option value="'.$row_genre['genre'].'">'.$row_genre['genre'].'</option>';
				}
				?>
				</select>
				<select style="margin-left:15px;" name="categorie">
					<option value="" disabled selected><?php
						if($_SESSION['langue']=='ru'){
							echo 'категория';
						}
						else{
							echo 'Catégorie';
						}
					?></option>
				<?php
				$db=db_connect();
				$requete_cat=mysqli_query($db,"SELECT categorie FROM categorie");
				while($row_cat=mysqli_fetch_array($requete_cat)){
					echo'<option value="'.$row_cat['categorie'].'">'.$row_cat['categorie'].'</option>';
				}
				?>
				</select>
				<button type="submit" class="btn btn-1 btn-1a btn-livre"><?php
						if($_SESSION['langue']=='ru'){
							echo 'Найти';
						}
						else{
							echo 'Trouver';
						}
					?></button>
			</form>

			<form class="ajax" action="search.php" method="get">

			<?php
			if($_SESSION['langue']=='ru'){
				echo '<p class="book">Найти книгу : <input class="book" placeholder="Найти книгу" type="text" name="q" id="q" value="" /></p>';
			}
			else{
				echo '<p class="book">Trouver un livre : <input class="book" placeholder="Trouver un livre" type="text" name="q" id="q" value="" /></p>';
			}
			?>
			</form>
			<div id="results"></div>
			<div id="mid">
			<?php body_panel(); ?>
			</div>
		</section>
    </body>
</html> 
