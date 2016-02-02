<?php session_start();
//connexion à la base de données 
include('db.php');

$db=db_connect();

$q=$_GET['q'];
$q = stripslashes($q);
$q = mysqli_real_escape_string($db,$q);
 
 if(isset($_GET['q'])){
	 //recherche des résultats dans la base de données
	$result=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn) AND (titre LIKE '%$q%' OR prenom_auteur LIKE '%$q%' OR nom_auteur LIKE '%$q%' OR description LIKE '%$q%' OR genre LIKE '%$q%' OR categorie LIKE '%$q%')") ;//OR auteur='$book'
 }
else{
	$result=mysqli_query($db,"SELECT DISTINCT livre.isbn,livre.titre,livre.description,livre.categorie,livre.genre FROM livre_auteur, livre,exemplaire WHERE (livre.isbn=livre_auteur.isbn) AND (livre.isbn=exemplaire.isbn)") ;//OR auteur='$book'
}
// affichage d'un message "pas de résultats"
if( mysqli_num_rows( $result ) == 0 )
{
	if($_SESSION['langue'] != 'ru'){?>
		<h3 style="text-align:center; margin:10px 0;">Pas de r&eacute;sultats pour cette recherche</h3>
	<?php
	}
	else{?>
		<h3 style="text-align:center; margin:10px 0;">Нет результатов для этого поиска</h3>
	<?php
	}
}
else
{
	$i=0;
    // parcours et affichage des résultats
    while($post=mysqli_fetch_array($result)){
		$isbn = stripslashes($post['isbn']);
		$isbn = mysqli_real_escape_string($db,$isbn);
		$i++;
		$i=$i%2;
		/////////////////////
		if(	$_SESSION['langue'] != 'ru'){
			echo '<br><div class="noalign livre" style="background-color:';if($i){echo '#ecf0f1';}echo';">';
				
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
						<h1> Titre : '.$post['titre']	.'</h1>
						<h2 >Auteur(s) : ';
						$auteur=mysqli_query($db,"SELECT * FROM livre_auteur WHERE isbn='$isbn'");
						 while($post_auteur=mysqli_fetch_array($auteur)){
							echo'<span class="auteur"> '.$post_auteur['nom_auteur'].' '.$post_auteur['prenom_auteur'].'</span><br>';
						 }
						echo '</h2>
						<h3>Catégorie : '.$post['categorie'].'</h3>
						<h3>Genre : '.$post['genre'].'</h3>
					</div>
					<br><br><br><br><br><br><br><br><br><br>
						<p class="resume"><h3> Résumé : </h3><br>'.$post['description'].'</p>
					<br></div><br>';
		}
		else{
		echo '<br><div class="noalign livre" style="background-color:';if($i){echo '#ecf0f1';}echo';">';
				
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
	}
}
 
/*****
fonctions
*****/
function safe($var)
{
	$var = mysql_real_escape_string($var);
	$var = addcslashes($var, '%_');
	$var = trim($var);
	$var = htmlspecialchars($var);
	return $var;
}
?>