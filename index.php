<?php
/*
Plugin Name: Semi-annual to annual data table in csv for ISTeP
Plugin URI: https://wpusermanager.com/
Description: Crée et gère un tableau de données pour l'ISTeP
Author: Robin Simonneau, Arbër Jonuzi
Version: 1.0
Author URI: https://axphyr.github.io/
*/
error_reporting(E_ALL); ini_set('display_errors', '1');
wp_enqueue_script('data-table-js',plugins_url('data-table.js',__FILE__),array(), false, true);


/**
 * Créer le fichier xls lors de l'activation du plugin
 * @return void
 */
function annual_data_table_install(): void
{
	// définit le nom du répertoire et le crée s'il n'existe pas
	$dirName = 'tableau-annuel';

	if (!is_dir($dirName)) {
		mkdir($dirName);
		echo "Directory created successfully";
	} else {
		echo "Directory already exists";
	}

	// Définit le nom du fichier ainsi que son chemin
	$filename = 'data-table.csv';
	$filepath = $dirName . '/' . $filename;

	$res = "";

	// Vérifie si le fichier existe déjà
	if (file_exists($filepath)) {
		// Supprime le fichier
		unlink($filepath);
		$res = "File edited successfully: " . $filepath;
	}
	else{
		$res = 'File created successfully: ' . $filepath;
	}

	// Définit les données à inscrire dans le fichier CSV
	$data = array(
		array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', ' ', 'Responsabilité de projets de recherche (ou tasks indépendantes)', ' ', ' ', ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', 'Responsabilités, Expertises & administration de la recherche', ' ', ' ', ' ', ' ', '|'),
		// Catégories (un retour par catégories et espaces pour centrer)
		array(' ', ' ', ' ', ' ', ' ', ' ', 'Informations générales', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '|',
			'Discipline', ' ', '|',
			' ', '|',
			' ', ' ', ' ', "Publications sur l'ensemble de la carrière jusqu'à aujourd'hui", ' ', ' ', ' ', '|',
			' ', ' ', 'Détail des publications par année depuis 2022', ' ', ' ', ' ', '|',
			' ', 'Enseignement', ' ', ' ', '|',
			' ', ' ', 'Encadrement Master 1 (à partir de 2022)', ' ', ' ', '|',
			' ', ' ', 'Encadrement Master 2 (à partir de 2022)', ' ', ' ', '|',
			' ', ' ', ' ', ' ', ' ', 'Encadrement thèse ISTeP à partir de 2022', ' ', ' ', ' ', ' ', ' ', '|',
			' ', ' ', ' ', ' ', ' ', 'Encadrement thèse hors ISTeP à partir de 2022', ' ', ' ', ' ', ' ', ' ', ' ', '|',
			' ', ' ', ' ', 'Encadrement de post-doctorats à partir de 2022', ' ', ' ', ' ', '|',
			'Prix ou distinctions scientifiques	', ' ', '|',
			"Appartenance à l'IUF", ' ', '|',
			"Séjours dans des laboratoires étrangers", ' ', '|',
			'Responsabilités dans des sociétés savantes', ' ', '|',
			"Régional et local", ' ', '|',
			'National', ' ', '|',
			'International', ' ', '|',
			'Partenariat (industrie, EPIC)', ' ', '|',
			'Locale', ' ', '|',
			'Régional', ' ', '|',
			'Internationale', ' ', '|',
			'Responsabilités & administration de la formation/enseignement', ' ', '|',
			'Vulgarisation, dissémination scientifique', ' ', '|',
			' ', '|',
			'Brevet', ' ', '|'),

		// Champs par catégories (un retour par catégories)
		array('NOM ', 'PRENOM', 'Equipe 2017-2022', 'Equipe 2022-2025', 'Equipe 2025-…', 'Pôles des services généraux (le cas échéant)', 'Fonction exercée', 'Corps', 'Rang', 'Date entrée (MM/AAAA)', 'Date sortie (MM/AAAA)', 'année naissance', 'année obtention thèse', 'année obtention HDR', "annee obtention thèse d'état", '|',
			'Discipline	1', 'Discipline	2', '|',
			'Thèmes de recherche','|',
			'Nombre total de publi de rang A', 'Nombre total de publi de rang A en 1ier auteur ou derrière un doctorant', 'nombre de citations (isi-web of science)', 'h-factor (Isi-Web)', 'nombre de citations (google scholar)', 'h-factor (google scholar)', 'Nbre de résumé à conférence avec comité de lecture', '|',
			'Nombre total de publications de rang A', 'Nbre article en 1er auteur ', 'Nbre article  derrière un doctorant', "Nbre d'articles rang A avec des collab. (autres laboratoires)", "Chapitre d'ouvrage / livre", 'Nombre de résumé à des congrès avec comité de lecture', '|',
			'nb heures enseignées 2022-2023', 'nb heures enseignées 2023-2024', 'nb heures enseignées 2024-2025', 'nb heures enseignées 2025-2026', '|',
			'Nom', 'Prénom', 'Année', 'NOM Prénom des Co-encadrants', 'Titre sujet (indiquer si hors ISTeP)', '|',
			'Nom', 'Prénom', 'Année', 'NOM Prénom des Co-encadrants', 'Titre sujet (indiquer si hors ISTeP)', '|',
			'Nom', 'Prénom', 'H/F', "Date d'inscription en thèse (MM/AAAA)", 'Date de soutenance (MM/AAAA)', 'NOM Prénom des Co-directeurs', 'Titre thèse', 'Établissement ayant délivré le master (ou diplôme équivalent)', "Numéro de l'ED de rattachement", 'Financement du doctorat', 'Fonction de direction ou encadrement ?', '|',
			'Nom', 'Prénom', 'H/F', "Date d'inscription en thèse (MM/AAAA)", 'Date de soutenance (MM/AAAA)', 'Direction de thèse (Nom, Prénom)', 'Titre thèse', 'Établissement ayant délivré le master (ou diplôme équivalent)', "Numéro de l'ED de rattachement", "Etablissement de rattachement de la direction de thèse", 'Financement du doctorat', 'Fonction de direction ou encadrement ?', '|',
			'Nom', 'Prénom', 'Année', "Date d'entrée (MM/AAAA)", 'Date de sortie (MM/AAAA)', 'Année de naissance', 'Etablissement ou organisme employeur', '|',
			"Intitulé de l'élément de distinction (nom du prix par exemple)",'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			"Intitulé de l'élément (membre, fonction …)", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			"Nom de l'évènement, fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			'Nom de la société, fonction', 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			'montant (k€)', 'Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)', '|',
			'montant (k€)', 'Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)', '|',
			'montant (k€)', 'Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)', '|',
			"Intitulé de l'élément et fonction", 'Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)', '|',
			"Intitulé de l'élément et fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			"Intitulé de l'élément et fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			"Intitulé de l'élément et fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			"Intitulé de l'élément et votre fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			"Intitulé de l'élément (évènement, vidéo, livre, …) et fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|',
			'Rayonnement / résultats majeurs sur la période à mettre en avant', '|',
			"Intitulé de l'élément et votre fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|')
	);

	// Ouvre le fichier pour l'écriture
	$file = fopen($filepath, 'w');

	// Inscrit les données dans le fichier
	foreach ($data as $row) {
		fputcsv($file, $row);
	}

	// Ferme le fichier
	fclose($file);

	// Retourne un messag à l'utilisateur
	echo $res;


}
register_activation_hook( __FILE__, 'annual_data_table_install' );

add_shortcode('add_istep_annual_table_download','download_annual_table');

function download_annual_table(): string {
	return <<<HTML
	<button><a href="/wp-admin/tableau-annuel/data-table.csv" download>Access File</a></button>
HTML;
}

function isRegistered(): bool {
	$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r');
	$is_registered = false;
	$last_name = wp_get_current_user()->last_name;

	while (($row = fgetcsv($file)) !== false) {
		if (in_array($last_name, $row)) {
			$is_registered = true;
			break;
		}
	}

	fclose($file);
	return $is_registered;
}

function user_id_in_csv_file(): int
{
	$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r');
	$id = 1;

	while (($row = fgetcsv($file)) !== false) {
		if (in_array(wp_get_current_user()->last_name, $row)) {
			fclose($file);
			return $id;
		}
		$id++;
	}
	fclose($file);
	return 0;
}

function replace_or_pushes_values(int $column, array $values): void
{
	$values[] = "|";
	if(isRegistered()){
		$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r+');
		$index = 0;
		while (($row = fgetcsv($file)) !== false) {
			if ($index === user_id_in_csv_file()) {
				// Replace the desired values in the row
				for ($i = $column; $i < count($values); $i++) {
					$row[$i] = $values[$i-$column];
				}
				// Move the file pointer to the beginning of the row
				fseek($file, -$index * strlen(implode(',', $row)), SEEK_CUR);
				// Write the new row to the file
				fputcsv($file, $row);
				break;
			}
			$index++;
		}
	}else{
		$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'a');
		fputcsv($file, $values);
	}
	fclose($file);
}

add_shortcode('add_istep_annual_table_form','block1');

function block1(): string{
	$name = wp_get_current_user()->first_name;
	$last_name = wp_get_current_user()->last_name;

	if(isset($_POST["submit1"])){
		replace_or_pushes_values(0, [$last_name, $name,
			$_POST["equipe1"], $_POST["equipe2"], $_POST["equipe3"], $_POST["pole"],
			$_POST["fonction"], $_POST["corps"], $_POST["rang"],
			date("Y", strtotime($_POST["date_entree"])), date("Y", strtotime($_POST["date_sortie"])), date("Y", strtotime($_POST["annee_naissance"])),
			date("Y", strtotime($_POST["annee_obtention_these"])), date("Y", strtotime($_POST["annee_obtention_hdr"])), date("Y", strtotime($_POST["annee_obtention_these_etat"]))]);
	}

	$roro = array();
	$rowLength = 0;
	$index = 1;
	if(isRegistered()){
		$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r+');
		while (($row = fgetcsv($file)) !== false) {
			$rowLength = count($row);
			$roro = $row;
			var_dump(fgetcsv($file));
			if ($index == user_id_in_csv_file()) {
				$index = 20;
				// Move the file pointer to the beginning of the row
				fseek($file, -$index * strlen(implode(',', $row)), SEEK_CUR);
				// Write the new row to the file
				fputcsv($file, $row);
				break;
			}
			$index++;
		}
	}

	$usId = user_id_in_csv_file();

	return <<<HTML
	<h4>Formulaire Informations Générales</h4>
	
	<h1> index = {$index}</h1>
	<h1> user row id = {$usId}</h1>
	<h1> row = {$roro[3]}, length = {$rowLength}</h1>

	<form method="POST" class="data-table-form_1">
		<h5>Informations générales</h5>
		<label for="last_name">Nom
			<input type="text" name="last_name" value="$last_name" required disabled>
		</label>
		<label for="name">Prénom
			<input type="text" name="name" value="$name" required disabled>
		</label>
		<label for="equipe1">Equipe 2017-2022</label>
		<select name="equipe1">
			<option value=" "></option>
			<option value="DEMO">DEMO</option>
			<option value="PGM2">PGM2</option>
			<option value="PPB">PPB</option>
		</select>
		<label for="equipe2">Equipe 2022-2025</label>
		<select name="equipe2">
			<option value=" "></option>
			<option value="PETRODYN">PETRODYN</option>
			<option value="TECTO">TECTO</option>
			<option value="TERMER">TERMER</option>
		</select>
		<label for="equipe3">Equipe 2025-…</label>
		<select name="equipe3">
			<option value=" "></option>
			<option value="PETRODYN">PETRODYN</option>
			<option value="TECTO">TECTO</option>
			<option value="TERMER">TERMER</option>
			<option value="PRISME">PRISME</option>
		</select>
		<label for="pole">Pôles des services généraux (le cas échéant)</label>
			<input type="text" name="pole" required>
		<label for="fonction">Fonction exercée</label>
			<input type="text" name="fonction" required>
		<label for="corps">Corps</label>
			<input type="text" name="corps" required>
		<label for="rang">Rang</label>
			<input type="text" name="rang" required>
		<label for="date_entree">Date entrée (MM/AAAA)</label>
			<input type="date" name="date_entree" required>
		<label for="date_sortie">Date sortie (MM/AAAA)</label>
			<input type="date" name="date_sortie" required>
		<label for="annee_naissance">année naissance</label>
			<input type="date" name="annee_naissance" required>
		<label for="annee_obtention_these">année obtention thèse</label>
			<input type="date" name="annee_obtention_these" required>
		<label for="annee_obtention_hdr">année obtention HDR</label>
			<input type="date" name="annee_obtention_hdr" required>
		<label for="annee_obtention_these_etat">année obtention Thèse d'état</label>
			<input type="date" name="annee_obtention_these_etat" required>
		<button type="submit" name="submit1">Envoyer</button>
	</form>
HTML;
}

function block2(): string{
	return <<<HTML
	<h4>Formulaire Discipline</h4>

	<form method="POST" class="data-table-form_2">
		<h5>Discipline</h5>
		<label for="discipline1">Discipline 1</label>
			<input type="text" name="discipline1" required>
		<label for="discipline2">Discipline 2</label>
			<input type="text" name="discipline2" required>
	<button type="submit" name="submit2">Envoyer</button>
	</form>

HTML;
}

function block3(): string{
	return <<<HTML
	<h4>Formulaire Thème de recherche</h4>

	<form method="POST" class="data-table-form_3">
		<h5>Thèmes de recherche (80 mots max)</h5>
			<input type="text" name="theme_recherche" id="theme_recherche" oninput="limitWords()" required>
		<button type="submit" name="submit3">Envoyer</button>
	</form>

HTML;
}

function block4(): string{
	return <<<HTML
	<h4>Formulaire Publications 1</h4>

	<form method="POST" class="data-table-form_4">
		<h5>Publications sur l'ensemble de la carrière jusqu'à aujourd'hui</h5>
		<label for="nb_publi_rang_a1">Nombre total de publi de rang A</label>
			<input type="number" name="nb_publi_rang_a1" required>
		<label for="nb_publi_rang_premier">Nombre total de publi de rang A en 1ier auteur ou derrière un doctorant</label>
			<input type="number" name="nb_publi_rang_premier" required>
		<label for="nb_citations_isi">nombre de citations (isi-web of science)</label>
			<input type="number" name="nb_citations_isi" required>
		<label for="h_factor_isi">h-factor (Isi-Web)</label>
			<input type="text" name="h_factor_isi" required>
		<label for="nb_citations_isi_google">nombre de citations (google scholar)</label>
			<input type="number" name="nb_citations_isi_google" required>
		<label for="h_factor_google">h-factor (google scholar)</label>
			<input type="text" name="h_factor_google" required>
		<label for="nb_resume_conference">Nbre de résumé à conférence avec comité de lecture</label>
			<input type="number" name="nb_resume_conference" required>
		<button type="submit" name="submit4">Envoyer</button>
	</form>

HTML;
}

function block5(): string{
	return <<<HTML
	<h4>Formulaire Publications 2</h4>

	<form method="POST" class="data-table-form_5">
		<h5>Détail des publications par année depuis 2022</h5>
		<label for="nb_publi_rang_a2">Nombre total de publi de rang A</label>
			<input type="number" name="nb_publi_rang_a2" required>
		<label for="nb_publi_premier">Nbre article en 1er auteur</label>
			<input type="number" name="nb_publi_premier" required>
		<label for="nb_article_doctorant">Nbre article  derrière un doctorant</label>
			<input type="number" name="nb_article_doctorant" required>
		<label for="nb_article_rang_a_collab">Nbre d'articles rang A avec des collab. (autres laboratoires)</label>
			<input type="number" name="nb_article_rang_a_collab" required>
		<label for="chapitre_ouvrage">Chapitre d'ouvrage / livre</label>
			<input type="text" name="chapitre_ouvrage" required>
		<label for="nb_resume_comite_lecture">Nbre de résumé à des congrès avec comité de lecture</label>
			<input type="number" name="nb_resume_comite_lecture" required>
		<button type="submit" name="submit5">Envoyer</button>
	</form>

HTML;
}

function block6(): string{
	return <<<HTML
	<h4>Formulaire Discipline</h4>

	<form method="POST" class="data-table-form_6">
		<h5>Discipline</h5>
		<label for="discipline1">Discipline 1</label>
			<input type="text" name="discipline1" required>
		<label for="discipline2">Discipline 2</label>
			<input type="text" name="discipline2" required>
		<button type="submit" name="submit6">Envoyer</button>
	</form>

HTML;
}

function block7(): string{
	return <<<HTML
	<h4>Formulaire Enseignement</h4>

	<form method="POST" class="data-table-form_7">
		<h5>Enseignement</h5>
		<label for="enseignement1">nb heures enseignées 2022-2023</label>
			<input type="number" name="enseignement1" required>
		<label for="enseignement2">nb heures enseignées 2023-2024</label>
			<input type="number" name="enseignement2" required>
		<label for="enseignement3">nb heures enseignées 2024-2025</label>
			<input type="number" name="enseignement3" required>
		<label for="enseignement4">nb heures enseignées 2025-2026</label>
			<input type="number" name="enseignement4" required>
		<button type="submit" name="submit7">Envoyer</button>
	</form>

HTML;
}

function block8(): string{
	return <<<HTML
	<h4>Formulaire Master 1</h4>

	<form method="POST" class="data-table-form_8">
		<h5>Encadrement Master 1 (à partir de 2022)</h5>
		<label for="master1_nom">Nom</label>
			<input type="text" name="master1_nom" required>
		<label for="master1_prenom">Prénom</label>
			<input type="text" name="master1_prenom" required>
		<label for="master1_annee">Année</label>
			<input type="date" name="master1_annee" required>
		<label for="master1_nom_prenom_co-encadrants">NOM Prénom des Co-encadrants</label>
			<input type="text" name="master1_nom_prenom_co-encadrants" required>
		<label for="master1_sujet">Titre sujet (indiquer si hors ISTeP)</label>
			<input type="text" name="master1_sujet" required>
		<button type="submit" name="submit8">Envoyer</button>
	</form>

HTML;
}

function block9(): string{
	return <<<HTML
	<h4>Formulaire Master 2</h4>

	<form method="POST" class="data-table-form_9">
		<h5>Encadrement Master 2 (à partir de 2022)</h5>
		<label for="master2_nom">Nom</label>
			<input type="text" name="master2_nom" required>
		<label for="master2_prenom">Prénom</label>
			<input type="text" name="master2_prenom" required>
		<label for="master2_annee">Année</label>
			<input type="date" name="master2_annee" required>
		<label for="master2_nom_prenom_co-encadrants">NOM Prénom des Co-encadrants</label>
			<input type="text" name="master2_nom_prenom_co-encadrants" required>
		<label for="master2_sujet">Titre sujet (indiquer si hors ISTeP)</label>
			<input type="text" name="master2_sujet" required>
		<button type="submit" name="submit9">Envoyer</button>
	</form>

HTML;
}

function block10(): string{
	return <<<HTML
	<h4>Formulaire Encadrement thèse ISTeP</h4>

	<form method="POST" class="data-table-form_10">
		<h5>Encadrement thèse ISTeP à partir de 2022</h5>
		<label for="encadrement_istep_nom">Nom</label>
			<input type="text" name="encadrement_istep_nom" required>
		<label for="encadrement_istep_prenom">Prénom</label>
			<input type="text" name="encadrement_istep_prenom" required>
		<label for="encadrement_istep_hf">H/F</label>
			<select name="sexe">
				<option value="Homme">Homme</option>
				<option value="Femme">Femme</option>
			</select>
		<label for="encadrement_istep_date_inscription_these">Date d'inscription en thèse (MM/AAAA)</label>
			<input type="date" name="encadrement_istep_date_inscription_these" required>
		<label for="encadrement_istep_date_soutenance">Date de soutenance (MM/AAAA)</label>
			<input type="date" name="encadrement_istep_date_soutenance" required>
		<label for="encadrement_istep_nom_prenom_co-directerurs">NOM Prénom des Co-directeurs</label>
			<input type="text" name="encadrement_istep_nom_prenom_co-directerurs" required>
		<label for="encadrement_istep_titre_these">Titre thèse</label>
			<input type="text" name="encadrement_istep_titre_these" required>
		<label for="encadrement_istep_etablissement">Établissement ayant délivré le master (ou diplôme équivalent)</label>
			<input type="text" name="encadrement_istep_etablissement" required>
		<label for="encadrement_istep_numero_ed">Numéro de l'ED de rattachement</label>
			<input type="text" name="encadrement_istep_numero_ed" required>
		<label for="encadrement_istep_financement_doctorat">Financement du doctorat</label>
			<input type="text" name="encadrement_istep_financement_doctorat" required>
		<label for="encadrement_istep_fonction">Fonction de direction ou encadrement ?</label>
			<input type="text" name="encadrement_istep_fonction" required>
		<button type="submit" name="submit10">Envoyer</button>
	</form>

HTML;
}

function block11(): string{
	return <<<HTML
	<h4>Formulaire Encadrement thèse hors ISTeP</h4>

	<form method="POST" class="data-table-form_11">
		<h5>Encadrement thèse hors ISTeP à partir de 2022</h5>
		<label for="encadrement_histep_nom">Nom</label>
			<input type="text" name="encadrement_histep_nom" required>
		<label for="encadrement_histep_prenom">Prénom</label>
			<input type="text" name="encadrement_histep_prenom" required>
		<label for="encadrement_histep_hf">H/F</label>
			<select name="sexe">
				<option value="Homme">Homme</option>
				<option value="Femme">Femme</option>
			</select>
		<label for="encadrement_histep_date_inscription_these">Date d'inscription en thèse (MM/AAAA)</label>
			<input type="date" name="encadrement_histep_date_inscription_these" required>
		<label for="encadrement_histep_date_soutenance">Date de soutenance (MM/AAAA)</label>
			<input type="date" name="encadrement_histep_date_soutenance" required>
		<label for="encadrement_histep_direction_these">Direction de thèse (Nom, Prénom)</label>
			<input type="text" name="encadrement_histep_direction_these" required>
		<label for="encadrement_histep_titre_these">Titre thèse</label>
			<input type="text" name="encadrement_histep_titre_these" required>
		<label for="encadrement_histep_etablissement">Établissement ayant délivré le master (ou diplôme équivalent)</label>
			<input type="text" name="encadrement_histep_etablissement" required>
		<label for="encadrement_histep_numero_ed">Numéro de l'ED de rattachement</label>
			<input type="text" name="encadrement_histep_numero_ed" required>
		<label for="encadrement_histep_etablissement_rattachement_direction_these">Etablissement de rattachement de la direction de thèse<</label>
			<input type="text" name="encadrement_histep_etablissement_rattachement_direction_these" required>
		<label for="encadrement_histep_financement_doctorat">Financement du doctorat</label>
			<input type="text" name="encadrement_histep_financement_doctorat" required>
		<label for="encadrement_histep_fonction">Fonction de direction ou encadrement ?</label>
			<input type="text" name="encadrement_histep_fonction" required>
		<button type="submit" name="submit11">Envoyer</button>
	</form>

HTML;
}

function block12(): string{
	return <<<HTML
	<h4>Formulaire Encadrement post-doctorats</h4>

	<form method="POST" class="data-table-form_12">
		<h5>Encadrement de post-doctorats à partir de 2022</h5>
		<label for="encadrement_pd_nom">Nom</label>
			<input type="text" name="encadrement_pd_nom" required>
		<label for="encadrement_pd_prenom">Prénom</label>
			<input type="text" name="encadrement_pd_prenom" required>
		<label for="encadrement_pd_hf">H/F</label>
			<select name="sexe">
				<option value="Homme">Homme</option>
				<option value="Femme">Femme</option>
			</select>
		<label for="encadrement_pd_date_entree">Date d'entrée (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_entree" required>
		<label for="encadrement_pd_date_sortie">Date de sortie (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_sortie" required>
		<label for="encadrement_pd_annee_naissance">Année de naissance</label>
			<input type="date" name="encadrement_pd_annee_naissance" required>
		<label for="encadrement_pd_employeur">Etablissement ou organisme employeur</label>
			<input type="text" name="encadrement_pd_employeur" required>
		<button type="submit" name="submit12">Envoyer</button>
	</form>

HTML;
}

function block13(): string{
	return <<<HTML
	<h4>Formulaire Prix ou Distinctions</h4>

	<form method="POST" class="data-table-form_13">
		<h5>Prix ou distinctions scientifiques</h5>
		<label for="distinction_intitule">Intitulé de l'élément de distinction (nom du prix par exemple)</label>
			<input type="text" name="distinction_intitule" required>
		<label for="distinction_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="distinction_annee" required>
		<button type="submit" name="submit13">Envoyer</button>
	</form>

HTML;
}

function block14(): string{
	return <<<HTML
	<h4>Formulaire Appartenance IUF</h4>

	<form method="POST" class="data-table-form_14">
		<h5>Appartenance à l'IUF</h5>
		<label for="iuf_intitule">Intitulé de l'élément (membre, fonction …)</label>
			<input type="text" name="iuf_intitule" required>
		<label for="iuf_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="iuf_annee" required>
		<button type="submit" name="submit14">Envoyer</button>
	</form>

HTML;
}

function block15(): string{
	return <<<HTML
	<h4>Formulaire Séjours</h4>

	<form method="POST" class="data-table-form_15">
		<h5>Séjours dans des laboratoires étrangers</h5>
		<label for="sejour_lieu">Lieu, fonction</label>
			<input type="text" name="sejour_lieu" required>
		<label for="sejour_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="sejour_annee" required>
		<button type="submit" name="submit15">Envoyer</button>
	</form>

HTML;
}

function block16(): string{
	return <<<HTML
	<h4>Formulaire Colloques/Congrès</h4>

	<form method="POST" class="data-table-form_16">
		<h5>Organisations de colloques/congrès internationaux</h5>
		<label for="organisation_nom">Nom de l'évènement, fonction</label>
			<input type="text" name="organisation_nom" required>
		<label for="organisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="organisation_annee" required>
		<button type="submit" name="submit16">Envoyer</button>
	</form>

HTML;
}
function block17(): string{
	return <<<HTML
	<h4>Formulaire Sociétés Savantes</h4>

	<form method="POST" class="data-table-form_17">
		<h5>Responsabilités dans des sociétés savantes</h5>
		<label for="societe_savantes_nom">Nom de la société, fonction</label>
			<input type="text" name="societe_savantes_nom" required>
		<label for="societe_savantes_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="societe_savantes_annee" required>
		<button type="submit" name="submit17">Envoyer</button>
	</form>

HTML;
}

function block18(): string{
	return <<<HTML
	<h4>Formulaire Responsabilités de projets de recherche</h4>

	<form method="POST" class="data-table-form_18">
		<h4>Responsabilité de projets de recherche (ou tasks indépendantes)</h4>
		
		<h5>Régional et local</h5>
		<label for="responsabilite1_region_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_region_montant" required>
		<label for="responsabilite1_region_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_region_nom" required>
		
		<h5>National</h5>
		<label for="responsabilite1_national_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_national_montant" required>
		<label for="responsabilite1_national_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_national_nom" required>
		
		<h5>International</h5>
		<label for="responsabilite1_international_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_international_montant" required>
		<label for="responsabilite1_international_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_international_nom" required>		<h5>International</h5>
		
		<h5>Partenariat (industrie, EPIC)</h5>
		<label for="responsabilite1_partenariat_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_partenariat_montant" required>
		<label for="responsabilite1_partenariat_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_partenariat_nom" required>
		<button type="submit" name="submit18">Envoyer</button>
	</form>

HTML;
}

function block19(): string{
	return <<<HTML
	<h4>Formulaire Discipline</h4>

	<form method="POST" class="data-table-form_19">
		<h4>Responsabilités, Expertises & administration de la recherche</h4>
		
		<h5>Locale</h5>
		<label for="responsabilite2_locale_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_locale_intitule" required>
		<label for="responsabilite2_locale_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_locale_annee" required>
		
		<h5>Régional</h5>
		<label for="responsabilite2_regional_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_regional_intitule" required>
		<label for="responsabilite2_regional_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_regional_annee" required>
		
		<h5>Internationale</h5>
		<label for="responsabilite2_international_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_international_intitule" required>
		<label for="responsabilite2_international_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_international_annee" required>
		<button type="submit" name="submit19">Envoyer</button>
	</form>

HTML;
}

function block20(): string{
	return <<<HTML
	<h4>Formulaire Responsabilités administratives</h4>

	<form method="POST" class="data-table-form_20">
		<h5>Responsabilités & administration de la formation/enseignement</h5>
		<label for="responsabilite3_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="responsabilite3_intitule" required>
		<label for="responsabilite3_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite3_annee" required>
		<button type="submit" name="submit20">Envoyer</button>
	</form>

HTML;
}

function block21(): string{
	return <<<HTML
	<h4>Formulaire Vulgarisation</h4>

	<form method="POST" class="data-table-form_21">
		<h5>Vulgarisation, dissémination scientifique</h5>
		<label for="vulgarisation_intitule">Intitulé de l'élément (évènement, vidéo, livre, …) et fonction</label>
			<input type="text" name="vulgarisation_intitule" required>
		<label for="vulgarisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="vulgarisation_annee" required>
		<button type="submit" name="submit21">Envoyer</button>
	</form>

HTML;
}

function block22(): string{
	return <<<HTML
	<h4>Formulaire Rayonnement</h4>

	<form method="POST" class="data-table-form_22">
		<h5>Rayonnement / résultats majeurs sur la période à mettre en avant</h5>
			<input type="text" name="rayonnement" required>
		<button type="submit" name="submit22">Envoyer</button>
	</form>

HTML;
}

function block23(): string{
	return <<<HTML
	<h4>Formulaire Brevet</h4>

	<form method="POST" class="data-table-form_23">
		<h5>Brevet</h5>
		<label for="brevet_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="brevet_intitule" required>
		<label for="brevet_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="brevet_annee" required>
		<button type="submit" name="submit23">Envoyer</button>
	</form>

HTML;
}