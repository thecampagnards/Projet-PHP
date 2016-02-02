<?php session_start();
if(!$_SESSION['admin']){
	header('Location: index.php'); 
}

include('db.php');
include('fonctions.php');

$db=db_connect();

////////////////////
$modif=NULL;

//si il y'a un isbn dans le lien
if(isset($_GET['book'])){

	$modif='Modifier';
	$isbn=$_GET['book'];
	$isbn = stripslashes($isbn);
	$isbn = mysqli_real_escape_string($db,$isbn);
	$requete=mysqli_query($db,"SELECT * FROM livre_auteur, livre WHERE (livre.isbn=livre_auteur.isbn) AND livre.isbn='$isbn' ");
	$row_mod=mysqli_fetch_array($requete);
	$requete=mysqli_query($db,"SELECT * FROM exemplaire WHERE isbn='$isbn' ");
	$row_count=mysqli_num_rows($requete);

	
}
if(isset($_POST['addgenre'])){
	$genre=$_POST['addgenre'];
	$genre = stripslashes($genre);
	$genre = mysqli_real_escape_string($db,$genre);
	if(	$requete=mysqli_query($db,"INSERT INTO genre (genre) VALUES ('$genre')")){
		if($_SESSION['langue']=='ru'){
			echo '<script>alert("Le genre a été ajouté.")</script>';
		}else{
			echo '<script>alert("Le genre a été ajouté.")</script>';
		}
	}
	else{
		if($_SESSION['langue']=='ru'){
			echo '<script>alert("Le genre existe déjà.")</script>';
		}else{
			echo '<script>alert("Le genre existe déjà.")</script>';
		}
	}
}
if(isset($_POST['addcat'])){
	$cat=$_POST['addcat'];
	$cat = stripslashes($cat);
	$cat = mysqli_real_escape_string($db,$cat);
	if(	$requete=mysqli_query($db,"INSERT INTO categorie (categorie) VALUES ('$cat')")){
		if($_SESSION['langue']=='ru'){
			echo '<script>alert("La catégorie a été ajouté.")</script>';
		}else{
			echo '<script>alert("La catégorie a été ajouté.")</script>';
		}
	}
	else{
		if($_SESSION['langue']=='ru'){
			echo '<script>alert("La catégorie existe déjà.")</script>';
		}else{
			echo '<script>alert("La catégorie existe déjà.")</script>';
		}
	}
}

if(isset($_POST['name'])&&isset($_POST['resume'])&&isset($_POST['author_name'])&&isset($_POST['author_surname'])&&isset($_POST['isbn'])&&isset($_POST['genre'])&&isset($_POST['categorie'])&&isset($_POST['nb_exemplaire'])){
	
	$name=$_POST['name'];
	$isbn=$_POST['isbn'];


	$isbn = stripslashes($isbn);
	$isbn = mysqli_real_escape_string($db,$isbn);
	
	$requete=mysqli_query($db,"SELECT * FROM livre WHERE isbn='$isbn'");
	$count=mysqli_num_rows($requete);
	
	// si il a trouv页n livre ecrit par le meme auteur
	if($count==1&&!isset($_GET['book'])){
		if($_SESSION['langue']=='ru'){
			echo '<script>alert("'.$name.' уже существует.")</script>';
		}else{
			echo '<script>alert("'.$name.' existe déjà.")</script>';
		}
	}
	// ce livre n'existe pas
	else {
		//traitement des données
		$resume=$_POST['resume'];
		$genre=$_POST['genre'];
		$categorie=$_POST['categorie'];
		$author_name=$_POST['author_name'];
		$author_surname=$_POST['author_surname'];
		$exemplaire=$_POST['nb_exemplaire'];
		$nb_livre=$_POST['nb_exemplaire'];
		$resume = stripslashes($resume);
		$name = stripslashes($name);
		$author_name = stripslashes($author_name);
		$author_surname = stripslashes($author_surname);
		$genre = stripslashes($genre);
		$categorie = stripslashes($categorie);
		$exemplaire = stripslashes($exemplaire);
		$author_name = mysqli_real_escape_string($db,$author_name);
		$author_surname = mysqli_real_escape_string($db,$author_surname);
		$genre = mysqli_real_escape_string($db,$genre);
		$resume = mysqli_real_escape_string($db,$resume);
		$name = mysqli_real_escape_string($db,$name);
		$exemplaire = mysqli_real_escape_string($db,$exemplaire);
		$categorie = mysqli_real_escape_string($db,$categorie);

		
		//traitement de l'image
		/*if ($_FILES['image']['size'] > 9999999) $erreur = "Le fichier est trop gros.";
		$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
		$extension_upload = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
		if (!empty($_FILES['image']['name'])&&!in_array($extension_upload,$extensions_valides)) $erreur = "Le fichier n'est pas au bon format.";
		*/
		$nom_image = 'image/'.$isbn.'.jpeg';
		//$count=mysqli_num_rows($requete);

		if(!isset($_GET['book'])){
			//!isset($_POST['Modifier'])&&!isset($erreur)&&
			$resultat = move_uploaded_file($_FILES['image']['tmp_name'],$nom_image);
			$requete=mysqli_query($db,"INSERT INTO livre (isbn, titre, description,genre,categorie) VALUES ('$isbn','$name','$resume','$genre','$categorie')");
			if(!mysqli_query($db,"SELECT * FRON auteur WHERE prenom_auteur='$author_surname' AND nom_auteur='$author_name'")){
				$requete=mysqli_query($db,"INSERT INTO auteur (nom_auteur,prenom_auteur) VALUES ('$author_name','$author_surname')");
			}
			$requete=mysqli_query($db,"INSERT INTO livre_auteur (isbn,nom_auteur,prenom_auteur) VALUES ('$isbn','$author_name','$author_surname')");
			$i=2;
			while(isset($_POST['author_name_'.$i.''])&&isset($_POST['author_surname_'.$i.''])){
				$author_name_1=$_POST['author_name_'.$i.''];
				$author_surname_1=$_POST['author_surname_'.$i.''];
				$author_name_1 = stripslashes($author_name_1);
				$author_surname_1 = stripslashes($author_surname_1);
				$requete=mysqli_query($db,"INSERT INTO auteur (nom_auteur,prenom_auteur) VALUES ('$author_name_1','$author_surname_1')");
				$requete=mysqli_query($db,"INSERT INTO livre_auteur (isbn,nom_auteur,prenom_auteur) VALUES ('$isbn','$author_name_1','$author_surname_1')");
				$i++;
			}
			for($i=0;$i<$nb_livre;$i++){
				$requete=mysqli_query($db,"INSERT INTO exemplaire (isbn) VALUES ('$isbn')");
			}
			if($_SESSION['langue']=='ru'){
				echo '<script>alert("Книга была добавлена.")</script>';
			}else{
				echo '<script>alert("Le livre a été ajouté.")</script>';
			}
		}
		else{
			$requete=mysqli_query($db,"UPDATE livre SET titre='$name', description='$resume', genre='$genre',categorie='$categorie' WHERE isbn='$isbn'");
			if(!$requete=mysqli_query($db,"UPDATE livre_auteur SET prenom_auteur='$author_surname', nom_auteur='$author_name' WHERE isbn='$isbn'")){
				$requete=mysqli_query($db,"INSERT INTO auteur (nom_auteur,prenom_auteur) VALUES ('$author_name','$author_surname')");
				$requete=mysqli_query($db,"UPDATE livre_auteur SET prenom_auteur='$author_surname', nom_auteur='$author_name' WHERE isbn='$isbn'");
			}
			$resultat = move_uploaded_file($_FILES['image']['tmp_name'],$nom_image);
			if($_SESSION['langue']=='ru'){
				echo '<script>alert("В книге был изменен.")</script>';	
			}else{
				echo '<script>alert("Le livre a été modifié.")</script>';	
			}
			}			
		}/*
		else{
			echo "Il y a eu un problème lors de l'ajout ou la modification du livre.";
		}*/
	}



?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Librairie</title>
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/set1.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<link rel="stylesheet" href="css/style.css" />
		
		<script type="text/javascript" src="js/jquery.js"></script> 
    </head>

    <body>
		<a href="index.php"><img class="image-header" src="image/header.jpg"/></a>
	<div id="panel">
				<?php top_panel(); ?>
			</div>
			<br><br>
	<form id="form-ajout" enctype="multipart/form-data" method="post" action="<?php echo "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>">
<?php if($_SESSION['langue']=='ru') { ?>
			<h1 class="titre">Добавить книгу</h1>
		<br>
		<span class="input input--hoshi">
					<input required name="name" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php 
						if(isset($_POST['name'])){
							echo $_POST['name'];
						}
						else if(isset($row_mod)){
							echo $row_mod['titre'];
						}
					?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">название</span>
					</label>
				</span>

		<p>категория : </p>
		<select required name="categorie">
			<?php
			$value=NULL;
			if(isset($_POST['categorie'])){
				$value=$_POST['categorie'];
			}
			else if(isset($row_mod)){
				$value=$row_mod['categorie'];
			}
			$requete=mysqli_query($db,"SELECT categorie FROM categorie");
			while($row_cat=mysqli_fetch_array($requete)){
				if($row_cat['categorie']==$value){
					echo'<option value="'.$value.'" selected="selected">'.$value.'</option>';
				}
				else{
					echo'<option value="'.$row_cat['categorie'].'">'.$row_cat['categorie'].'</option>';
				}
			}
			?>
		</select>
		<p>вид : </p>
		<select required name="genre">
			<?php
			$value=NULL;
			if(isset($_POST['genre'])){
				$value=$_POST['genre'];
			}
			else if(isset($row_mod)){
				$value=$row_mod['genre'];
			}
			echo $value;
			$requete=mysqli_query($db,"SELECT genre FROM genre");
			while($row_cat=mysqli_fetch_array($requete)){
				if($row_cat['genre']==$value){
					echo'<option value="'.$value.'" selected="selected">'.$value.'</option>';
				}
				else{
					echo'<option value="'.$row_cat['genre'].'">'.$row_cat['genre'].'</option>';
				}
			}
			?>
		</select>
		<p>резюме : </p>
		<textarea required name="resume"><?php 
			if(isset($_POST['resume'])){
				echo $_POST['resume'];
			}
			else if(isset($row_mod)){
				echo $row_mod['description'];
			}
		?></textarea>
<p>автор : </p>
		<?php 
		$nb_auteur=0;
		$requete_auteur=NULL;
		if(isset($row_mod)){
			$requete_auteur=mysqli_query($db,"SELECT * FROM livre_auteur WHERE isbn='$isbn'");
		}
		while(isset($_POST['author_surname_'.$nb_auteur.''])||(isset($requete_auteur)&&$auteur=mysqli_fetch_array($requete_auteur))||$nb_auteur==0){ ?>
				<span class="input input--hoshi">
					<input required name="author_name<?php if($nb_auteur!=0){echo '_'.$nb_auteur;}?>" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php
						if(isset($_POST['author_name'])&&$nb_auteur==0){
							echo $_POST['author_name'];
						}
						else if(isset($_POST['author_name_'.$nb_auteur.''])){
							echo $_POST['author_name_'.$nb_auteur.''];
						}
						else if(isset($row_mod)){
							echo $auteur['nom_auteur'];
						}
					?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">имя</span>
					</label>
				</span>

		<span class="input input--hoshi" style="display: inline-block!important;">
					<input required name="author_surname<?php if($nb_auteur!=0){echo '_'.$nb_auteur;}?>" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php
						if(isset($_POST['author_surname'])&&$nb_auteur==0){
							echo $_POST['author_surname'];
						}
						else if(isset($_POST['author_surname_'.$nb_auteur.''])){
							echo $_POST['author_surname_'.$nb_auteur.''];
						}
						else if(isset($row_mod)){
							echo $auteur['prenom_auteur'];
						}
					?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">имя</span>
					</label>
				</span>
				<br>
		<?php $nb_auteur++; } ?>

				<div id="second_auteur"></div>
				<a class="addauthor">добавить автора</a>

		<span class="input input--hoshi">
					<input required name="isbn" class="input__field input__field--hoshi" type="number" id="input-4" 
					value="<?php
					if(isset($_POST['isbn'])){
						echo $_POST['isbn'];
					}
					else if(isset($row_mod)){
						echo $row_mod['isbn'];
					}
				?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">ISBN</span>
					</label>
				</span>

		<span class="input input--hoshi">
					<input required name="nb_exemplaire" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php
					if(isset($_POST['nb_exemplaire'])){
				echo $_POST['nb_exemplaire'];
			}
			else if(isset($row_count)){
				echo $row_count;
			}
				?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Количество копий</span>
					</label>
				</span>

		<p>фото : </p><br>
		<?php 
			if(isset($_POST['isbn'])){
				echo '<img class="photoform" src="image/'.$_POST['isbn'].'.jpeg" />';
			}else if(isset($row_mod)){
				echo '<img class="photoform" src="image/'.$row_mod['isbn'].'.jpeg"/>';
			}
		?>
		<input <?php 
		if(!isset($_GET['book'])){
			echo 'required';
		}
		?> name="image" type="file" accept="image/*">
		<br><br>
		<button type="submit" value="Deconnexion" name="" class="btn btn-1 btn-1a btn-livre"><?php 
		if(isset($modif)){
				echo 'изменение';
			}
			else{
				echo 'Дополнение';
			}?> книга</button>
		
<?php }else{?>
			<h1 class="titre">
			<?php 
			if(isset($modif)){
				echo 'Modifier';
			}
			else{
				echo 'Ajouter';
			}?> un livre</h1>
		<br>
		<span class="input input--hoshi">
					<input required name="name" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php 
						if(isset($_POST['name'])){
							echo $_POST['name'];
						}
						else if(isset($row_mod)){
							echo $row_mod['titre'];
						}
					?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Titre</span>
					</label>
				</span>

		<p>Catégorie : </p>
		<select required name="categorie">
			<?php
			$value=NULL;
			if(isset($_POST['categorie'])){
				$value=$_POST['categorie'];
			}
			else if(isset($row_mod)){
				$value=$row_mod['categorie'];
			}
			$requete=mysqli_query($db,"SELECT categorie FROM categorie");
			while($row_cat=mysqli_fetch_array($requete)){
				if($row_cat['categorie']==$value){
					echo'<option value="'.$value.'" selected="selected">'.$value.'</option>';
				}
				else{
					echo'<option value="'.$row_cat['categorie'].'">'.$row_cat['categorie'].'</option>';
				}
			}
			?>
		</select>
		<p>Genre : </p>
		<select required name="genre">
			<?php
			$value=NULL;
			if(isset($_POST['genre'])){
				$value=$_POST['genre'];
			}
			else if(isset($row_mod)){
				$value=$row_mod['genre'];
			}
			echo $value;
			$requete=mysqli_query($db,"SELECT genre FROM genre");
			while($row_cat=mysqli_fetch_array($requete)){
				if($row_cat['genre']==$value){
					echo'<option value="'.$value.'" selected="selected">'.$value.'</option>';
				}
				else{
					echo'<option value="'.$row_cat['genre'].'">'.$row_cat['genre'].'</option>';
				}
			}
			?>
		</select>
		<p>Présentation : </p>
		<textarea required name="resume"><?php 
			if(isset($_POST['resume'])){
				echo $_POST['resume'];
			}
			else if(isset($row_mod)){
				echo $row_mod['description'];
			}
		?></textarea>
		<p>Auteur : </p>
		<?php 
		$nb_auteur=0;
		$requete_auteur=NULL;
		if(isset($row_mod)){
			$requete_auteur=mysqli_query($db,"SELECT * FROM livre_auteur WHERE isbn='$isbn'");
		}
		while(isset($_POST['author_surname_'.$nb_auteur.''])||(isset($requete_auteur)&&$auteur=mysqli_fetch_array($requete_auteur))||$nb_auteur==0){ ?>
				<span class="input input--hoshi">
					<input required name="author_name<?php if($nb_auteur!=0){echo '_'.$nb_auteur;}?>" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php
						if(isset($_POST['author_name'])&&$nb_auteur==0){
							echo $_POST['author_name'];
						}
						else if(isset($_POST['author_name_'.$nb_auteur.''])){
							echo $_POST['author_name_'.$nb_auteur.''];
						}
						else if(isset($row_mod)){
							echo $auteur['nom_auteur'];
						}
					?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Nom</span>
					</label>
				</span>

		<span class="input input--hoshi" style="display: inline-block!important;">
					<input required name="author_surname<?php if($nb_auteur!=0){echo '_'.$nb_auteur;}?>" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php
						if(isset($_POST['author_surname'])&&$nb_auteur==0){
							echo $_POST['author_surname'];
						}
						else if(isset($_POST['author_surname_'.$nb_auteur.''])){
							echo $_POST['author_surname_'.$nb_auteur.''];
						}
						else if(isset($row_mod)){
							echo $auteur['prenom_auteur'];
						}
					?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Prénom</span>
					</label>
				</span>
				<br>
		<?php $nb_auteur++; } ?>

				<div id="second_auteur"></div>
				<a class="addauthor">Ajouter un auteur</a>

		<span class="input input--hoshi">
					<input required name="isbn" class="input__field input__field--hoshi" type="number" id="input-4" 
					value="<?php
					if(isset($_POST['isbn'])){
						echo $_POST['isbn'];
					}
					else if(isset($row_mod)){
						echo $row_mod['isbn'];
					}
				?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">ISBN</span>
					</label>
				</span>

		<span class="input input--hoshi">
					<input required name="nb_exemplaire" class="input__field input__field--hoshi" type="text" id="input-4" 
					value="<?php
					if(isset($_POST['nb_exemplaire'])){
				echo $_POST['nb_exemplaire'];
			}
			else if(isset($row_count)){
				echo $row_count;
			}
				?>">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Nombre d'exemplaires</span>
					</label>
				</span>

		<p>Photo : </p><br>
		<?php 
			if(isset($_POST['isbn'])){
				echo '<img class="photoform" src="image/'.$_POST['isbn'].'.jpeg" />';
			}else if(isset($row_mod)){
				echo '<img class="photoform" src="image/'.$row_mod['isbn'].'.jpeg"/>';
			}
		?>
		<input <?php 
		if(!isset($_GET['book'])){
			echo 'required';
		}
		?> name="image" type="file" accept="image/*">
		<br><br>
		<button type="submit" value="Deconnexion" name="" class="btn btn-1 btn-1a btn-livre"><?php 
		if(isset($modif)){
				echo $modif;
			}
			else{
				echo 'Ajouter';
			}?> le livre</button>
			<?php }?>
	</form>
	<br>
	<?php 
			$requete=mysqli_query($db,"SELECT genre FROM genre");

	if($_SESSION['langue']=='ru'){?>
	<h1 class="titre">Добавить жанр</h1>
		<form class="form-center" method="post" action="livre.php">
		<p>Существующие жанры :</p><br>
		<?php
		while($row_genre=mysqli_fetch_array($requete)){
				echo$row_genre['genre'].'<br>';	
			}
		?>
			<span class="input input--hoshi">
					<input required name="addgenre" class="input__field input__field--hoshi" type="text" id="input-4">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">жанр</span>
					</label>
				</span>
		<button type="submit" value="addgenre" name="" class="btn btn-1 btn-1a btn-livre">Добавить жанр</button>
		</form>
	<?php }else{?>
		<h1 class="titre">Ajouter un genre</h1>
		<form class="form-center" method="post" action="livre.php">
		<p>Genres existants :</p><br>
		<?php
		while($row_genre=mysqli_fetch_array($requete)){
				echo$row_genre['genre'].'<br>';	
			}
		?>
			<span class="input input--hoshi">
					<input required name="addgenre" class="input__field input__field--hoshi" type="text" id="input-4">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Genre</span>
					</label>
				</span>
		<button type="submit" value="addgenre" name="" class="btn btn-1 btn-1a btn-livre">Ajouter un genre</button>
		</form>
	<?php }?>
		<?php 
		$requete=mysqli_query($db,"SELECT categorie FROM categorie");

		if($_SESSION['langue']=='ru'){?>
	<h1 class="titre">Добавить категорию</h1>
		<form class="form-center" method="post" action="livre.php">
		<p>Существующие категории :</p><br>
		<?php
		while($row_genre=mysqli_fetch_array($requete)){
				echo$row_genre['categorie'].'<br>';	
			}
		?>
			<span class="input input--hoshi">
					<input required name="addgenre" class="input__field input__field--hoshi" type="text" id="input-4">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">категории</span>
					</label>
				</span>
		<button type="submit" value="addcat" name="" class="btn btn-1 btn-1a btn-livre">Добавить категорию</button>
		</form>
	<?php }else{?>
		<h1 class="titre">Ajouter une categorie</h1>
		<form class="form-center" method="post" action="livre.php">
				<p>Catégories existantes :</p><br>

				<?php
		while($row_genre=mysqli_fetch_array($requete)){
				echo$row_genre['categorie'].'<br>';	
			}
		?>
			<span class="input input--hoshi">
					<input required name="addcat" class="input__field input__field--hoshi" type="text" id="input-4">
					<label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
						<span class="input__label-content input__label-content--hoshi">Catégorie</span>
					</label>
				</span>
		<button type="submit" value="addcat" name="" class="btn btn-1 btn-1a btn-livre">Ajouter une catégorie</button>
		</form>
	<?php }?>
	
			<script src="js/classie.js"></script>
		<script>
			(function() {
				// trim polyfill : https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/Trim
				if (!String.prototype.trim) {
					(function() {
						// Make sure we trim BOM and NBSP
						var rtrim = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g;
						String.prototype.trim = function() {
							return this.replace(rtrim, '');
						};
					})();
				}

				[].slice.call( document.querySelectorAll( 'input.input__field' ) ).forEach( function( inputEl ) {
					// in case the input is already filled..
					if( inputEl.value.trim() !== '' ) {
						classie.add( inputEl.parentNode, 'input--filled' );
					}

					// events:
					inputEl.addEventListener( 'focus', onInputFocus );
					inputEl.addEventListener( 'blur', onInputBlur );
				} );

				function onInputFocus( ev ) {
					classie.add( ev.target.parentNode, 'input--filled' );
				}

				function onInputBlur( ev ) {
					if( ev.target.value.trim() === '' ) {
						classie.remove( ev.target.parentNode, 'input--filled' );
					}
				}
			})();
		</script>
		<script>
		var i =<?php echo $nb_auteur;?>;
		$( ".addauthor" ).click(function()  {
			i++;
			$('#second_auteur').append('<div class="input"><input style="margin-bottom:2px;" name="author_name_'+i+'" type="text" value="" placeholder="<?php if($_SESSION['langue']=='ru') {echo 'имя';}else{echo 'Nom';}?>"><input name="author_surname_'+i+'" type="text" value="" placeholder="<?php if($_SESSION['langue']=='ru') {echo 'имя';}else{echo 'Prénom';}?>"></div>');
		});
		</script>
    </body>
</html> 