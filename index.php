<?php
/*
Plugin Name: HCERES
Plugin URI: https://axphyr.github.io/
Description: Crée et gère un tableau de données pour l'ISTeP
Author: Arbër Jonuzi
Version: 1.0
Author URI: https://axphyr.github.io/
*/
error_reporting(E_ALL); ini_set('display_errors', '1');
wp_enqueue_script('data-table-js',plugins_url('data-table.js',__FILE__),array(), false, true);
wp_enqueue_style('style',plugins_url('style.css',__FILE__));

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
		array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', ' ', 'Responsabilité de projets de recherche (ou tasks indépendantes)', ' ', ' ', ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', 'Responsabilités, Expertises & administration de la recherche', ' ', ' ', ' ', ' ', '|'),
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
			"Organisations de colloques/congrès internationaux", "", "|",
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
			"Lieu, fonction", "Année ou période (début MM/AAAA - fin MM/AAAA)", "|",
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

	// ajout des pages pour les différents blocks
	$lst = ["Informations Générales", "Discipline", "Thème de recherche", "Publications 1", "Publications 2", "Enseignement", "Master 1", "Master 2", "Encadrement thèse ISTeP", "Encadrement thèse hors ISTeP", "Encadrement post-doctorats", "Prix ou Distinctions", "Appartenance IUF", "Séjours", "Colloques/Congrès", "Sociétés Savantes", "Responsabilités de projets de recherche", "Responsabilités, Expertises & administration de la recherche", "Responsabilités administratives", "Vulgarisation & dissémination scientifique", "Rayonnement", "Brevet" ];
	for($i = 0; $i < 22; $i++){
		create_custom_pages("(DT) Formulaire " . $lst[$i], "add_istep_annual_table_form_block_" . $i + 1);
	}
}
register_activation_hook( __FILE__, 'annual_data_table_install' );

function delete_custom_pages(): void {
	// Get all page IDs where the title starts with "(DT) Formulaire"
	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'orderby'        => 'title',
		'order'          => 'ASC',
		's'              => '(DT) Formulaire',
	);

	$query = new WP_Query($args);
	$page_ids = array();

	while ($query->have_posts()) {
		$query->the_post();
		$page_ids[] = get_the_ID();
	}

	wp_reset_postdata();

	// Loop through the page IDs and delete the pages
	foreach ($page_ids as $page_id) {
		wp_delete_post($page_id, true);
	}
}
register_deactivation_hook(__FILE__, 'delete_custom_pages');

add_shortcode('add_istep_annual_table_download','download_annual_table');

function create_custom_pages($title, $content): void {
	$shortcode = "[" . $content . "]";
	// Create the page post object
	$page = array(
		'post_title'    => $title,
		'post_content'  => wp_kses_post($shortcode),
		'post_status'   => 'publish',
		'post_type'     => 'page',
	);

	// Insert the page into the database
	wp_insert_post($page);
}

function download_annual_table(): string {
	return <<<HTML
	<button><a class="dt-download" href="/wp-admin/tableau-annuel/data-table.csv" download> Accéder au fichier </a></button>
HTML;
}

function isRegistered(): bool {
	$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r');
	$is_registered = false;
	$last_name = ucfirst(wp_get_current_user()->last_name);

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
		if (in_array(ucfirst(wp_get_current_user()->first_name), $row)) {
			rewind($file);
			fclose($file);
			return $id;
		}
		$id++;
	}
	rewind($file);
	fclose($file);
	return 0;
}

function deleteAndInsertRowInCSV($file, $rowNumber, $newRow): void {
	// Read the original file
	$rows = [];
	if (($handle = fopen($file, 'r')) !== false) {
		while (($data = fgetcsv($handle)) !== false) {
			$rows[] = $data;
		}
		fclose($handle);
	}

	// Delete the row at the specified position
	unset($rows[$rowNumber - 1]);

	// Insert the new row at the specified position
	array_splice($rows, $rowNumber - 1, 0, [$newRow]);

	// Write the updated contents back to the file
	if (($handle = fopen($file, 'w')) !== false) {
		foreach ($rows as $row) {
			fputcsv($handle, $row);
		}
		fclose($handle);
	}
}

function replace_or_pushes_values(int $column, array $values): void
{
	$values[] = "|";
	if (isRegistered()) {
		$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r+');

		// Skip the first rows
		for ($i = 1; $i < user_id_in_csv_file(); $i++) {
			fgetcsv($file);
		}

		// Get the nth row as an array
		$row = fgetcsv($file);

		// Modify the original array if needed
		if(count($row) <= $column + count($values)) {
			for($i = 0; $i < $column + count($values); $i++) {
				$row[] = " ";
			}
		}

		// Modify the original values
		for($i = $column; $i < count($values) + $column; $i++){
			$row[$i] = $values[$i-$column];
		}

		deleteAndInsertRowInCSV('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', user_id_in_csv_file(), $row);

	} else{
		$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'a');
		fputcsv($file, $values);
	}
	fclose($file);
}

function getCell(int $column): string {
	$file = fopen( '/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r' );

	$id = 1;
	while (($row = fgetcsv($file)) !== false) {
		if ($id == user_id_in_csv_file()) {
			if (isset($row[$column])) {
				fclose($file);
				return $row[$column];
			}
		}
		$id++;
	}
	fclose( $file );

	return "";
}

function hasAnswered(): array {
	$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r');
	$answered = [];

	// Skip the specified number of rows
	for ($i = 1; $i < user_id_in_csv_file(); $i++) {
		fgetcsv($file);
	}

	$numbers = [0, 16, 19, 21, 29, 36, 41, 47, 53, 65, 78, 86, 89, 92, 95, 98, 101, 110, 117, 120, 123, 125];

	// Read the first row and check for elements after each given number
	$row = fgetcsv($file);
	if ($row !== false) {
		foreach ($numbers as $number) {
			if (isset($row[$number + 1]) && $row[$number + 1] !== " ") {
				$answered[] = true;
			} else {
				$answered[] = false;
			}
		}
	}

	fclose($file);

	return $answered;
}

function checkArray($arr): bool {
	foreach ($arr as $value) {
		if ($value !== true) {
			return false;
		}
	}
	return true;
}

function haveAnswered(): float {
	$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r');
	$res = 0;
	$nbUsers = count(getUsersFromCSV());

	$numbers = [0, 16, 19, 21, 29, 36, 41, 47, 53, 65, 78, 86, 89, 92, 95, 98, 101, 110, 117, 120, 123, 125];

	// Skip the header rows
	fgetcsv($file);
	fgetcsv($file);
	fgetcsv($file);

	while (($row = fgetcsv($file)) !== false) {
		$answered = [];
		foreach ($numbers as $number) {
			if (isset($row[$number + 1]) && $row[$number + 1] !== " ") {
				$answered[] = true;
			} else {
				$answered[] = false;
			}
		}
		if (checkArray($answered)){
			$res++;
		}
	}

	if($nbUsers === 0){
		return 0;
	}

	return $res * 100 / $nbUsers;
}

function calculatePercentage($arr): float|int {
	$totalElements = count($arr);
	$trueCount = 0;

	foreach ($arr as $value) {
		if ($value === true) {
			$trueCount++;
		}
	}

	if ($totalElements > 0) {
		return ($trueCount / $totalElements) * 100;
	} else {
		return 0;
	}
}


function averageAnswer(): float{
	$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r');
	$res = 0;
	$avg = 0;

	$numbers = [0, 16, 19, 21, 29, 36, 41, 47, 53, 65, 78, 86, 89, 92, 95, 98, 101, 110, 117, 120, 123, 125];

	// Skip the header rows
	fgetcsv($file);
	fgetcsv($file);
	fgetcsv($file);

	while (($row = fgetcsv($file)) !== false) {
		$answered = [];
		foreach ($numbers as $number) {
			if (isset($row[$number + 1]) && $row[$number + 1] !== " ") {
				$answered[] = true;
			} else {
				$answered[] = false;
			}
		}
		$avg += calculatePercentage($answered);
		$res++;
	}

	if ($res === 0){
		return 0;
	}

	return $avg / $res;
}

function getUsersFromCSV(): array {
	$file = fopen('/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r');

	$users = [];

	// Skip the header rows
	fgetcsv($file);
	fgetcsv($file);
	fgetcsv($file);

	while (($row = fgetcsv($file)) !== false) {
		$firstName = $row[1];
		$lastName = $row[0];

		// Add the user to the array
		$users[] = $firstName . ' ' . $lastName;
	}

	fclose($file);

	return $users;
}

function getUsersFromWordPress($roles = ''): array {
	if ($roles === ''){
		$users = get_users();
	} else {
		$args = array(
			'role__in' => $roles,
		);
		$users = get_users($args);
	}

	return $users;
}

function getAbsentUsers($roles = ''): array {
	$absent = [];

	// Get all WordPress users
	$wpUsers = getUsersFromWordPress($roles);

	// Get the list of users from the CSV file
	$csvUsers = getUsersFromCSV();

	for ($i = 0; $i < count($wpUsers); $i++){
		if(!in_array(ucfirst($wpUsers[$i]->first_name) . ucfirst($wpUsers[$i]->last_name), $csvUsers)){
			$absent[] = $wpUsers[$i];
		}
	}

	// Return the absent users
	return $absent;
}

add_shortcode('add_istep_annual_table_panel','panel');
function panel(): string {

	$nbUsers = round(count(getUsersFromCSV()) * 100 / count(get_users()));
	$nbUsersDone = round(haveAnswered());
	$avg = round(averageAnswer());
	$absent = getAbsentUsers();

	$html = <<<HTML
	<div class="dt__panel">
		<p>Panel HCERES</p>
		<div class="col-div-4-1">
			<div class="box-1">
				<div class="content-box-1">
					<h5 class="head-1">Pourcentage de personnes à avoir commencé le tableau</h5>
					<div class="circle-wrap">
					    <div class="circle">
					        <div class="mask full">
					            <div class="fill"></div>
					        </div>
					        <div class="mask half">
					            <div class="fill"></div>
					        </div>
					        <div class="inside-circle"> $nbUsers% </div>
					    </div>
					</div>
				</div>
			</div>
			<div class="box-1">
				<div class="content-box-1">
					<h5 class="head-1">Pourcentage de personnes à avoir complété le tableau</h5>
					<div class="circle-wrap2">
					    <div class="circle">
					        <div class="mask full">
					            <div class="fill"></div>
					        </div>
					        <div class="mask half">
					            <div class="fill"></div>
					        </div>
					        <div class="inside-circle2"> $nbUsersDone% </div>
					    </div>
					</div>
				</div>
			</div>
			<div class="box-1">
				<div class="content-box-1">
					<h5 class="head-1">Pourcentage de complétion moyen du tableau</h5>
					<div class="circle-wrap3">
					    <div class="circle">
					        <div class="mask full">
					            <div class="fill"></div>
					        </div>
					        <div class="mask half">
					            <div class="fill"></div>
					        </div>
					        <div class="inside-circle3"> $avg% </div>
					    </div>
					</div>
				</div>
			</div>
		</div>
		<p>Liste des personnes non inscrites</p>
		<div class="dt__absent-users">
HTML;

	for ($i = 0; $i < count($absent); $i++) {
		$user = $absent[$i];
		$html .= <<<HTML
			<div class="dt__absent-user">
				<div class="dt__absent-user-name">$user->display_name</div>
				<div class="dt__absent-user-email">$user->user_email</div>
			</div>
HTML;

	}

	$html .= <<<HTML
		</div>
	</div>
HTML;

	return $html;
}

function transformString($str): string {
	return trim(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $str))), '-');
}

add_shortcode('add_istep_annual_table_summary','summary');
function summary(): string{
	$lst = ["Informations Générales", "Discipline", "Thème de recherche", "Publications 1", "Publications 2", "Enseignement", "Master 1", "Master 2", "Encadrement thèse ISTeP", "Encadrement thèse hors ISTeP", "Encadrement post-doctorats", "Prix ou Distinctions", "Appartenance IUF", "Séjours", "Colloques/Congrès", "Sociétés Savantes", "Responsabilités de projets de recherche", "Responsabilités, Expertises & administration de la recherche", "Responsabilités administratives", "Vulgarisation & dissémination scientifique", "Rayonnement", "Brevet"];
	$html = <<<HTML
		<div class="annual_data_table_summary">
			<p> Liste des formulaires HCERES</p>
			<div class="bouttons">
HTML;

	$count = count($lst);
	if(!isRegistered()){
		$count = 1;
	}

	$colored = hasAnswered();

	for ($i = 0; $i < $count; $i++) {
		$page = transformString("(DT) Formulaire " . $lst[$i]);
		if($colored[$i]){
			$class = 'class = "dt__green"';
		}else{
			$class = "";
		}
		$html .= <<<HTML
				<button type="button" $class onclick="window.location.href = '$page'">$lst[$i]</button>
HTML;
	}
	$html .= "</div>";
	$html .= <<<HTML
				<p>Votre progression</p>
				<div id="progress-container">
					<div id="progress-bar"></div>
				</div>
				<p id="pourcent">0%</p>
HTML;

	$html .= <<<HTML
		</div>
		<div class="nb__buttons">$count</div>
HTML;
	return $html;
}

add_shortcode('add_istep_annual_table_form_block_1','block1');
function block1(): string{
	$name = ucfirst(wp_get_current_user()->first_name);
	$last_name = ucfirst(wp_get_current_user()->last_name);

	if(isset($_POST["submit1"])){
		$data = [$last_name,
			$name,
			$_POST["equipe1"],
			$_POST["equipe2"],
			$_POST["equipe3"],
			$_POST["pole"],
			$_POST["fonction"],
			$_POST["corps"],
			$_POST["rang"],
			date("m/Y", strtotime($_POST["date_entree"])),
			date("m/Y", strtotime($_POST["date_sortie"])),
			$_POST["annee_naissance"],
			$_POST["annee_obtention_these"],
			$_POST["annee_obtention_hdr"],
			$_POST["annee_obtention_these_etat"]
		];

		if(!isRegistered()){
			// searches the number of fields in the csv file and stocks it
			$file = fopen( '/home/jonu0002/Local Sites/tableau-plugin/app/public/wp-admin/tableau-annuel/data-table.csv', 'r' );
			fgetcsv($file);
			$maxFields = count(fgetcsv($file));
			fclose($file);

			for($i = count($_POST) + 1; $i <= $maxFields - 1; $i++){
				$data[] = ' ';
			}
		}
		replace_or_pushes_values(0, $data);
	}


	$demo = null;
	$pgm2 = null;
	$ppb = null;

	if(getCell(2) == "DEMO"){
		$demo = "selected";
	}
	if(getCell(2) == "PGM2"){
		$pgm2 = "selected";
	}
	if(getCell(2) == "PPB"){
		$ppb = "selected";
	}

	$petrodyn = null;
	$tecto = null;
	$termer = null;

	if(getCell(3) == "PETRODYN"){
		$petrodyn = "selected";
	}
	if(getCell(3) == "TECTO"){
		$tecto = "selected";
	}
	if(getCell(3) == "TERMER"){
		$termer = "selected";
	}

	$petrodyn2 = null;
	$tecto2 = null;
	$termer2 = null;
	$prisme = null;

	if(getCell(4) == "PETRODYN"){
		$petrodyn2 = "selected";
	}
	if(getCell(4) == "TECTO"){
		$tecto2 = "selected";
	}
	if(getCell(4) == "TERMER"){
		$termer2 = "selected";
	}
	if(getCell(4) == "PRISME"){
		$prisme = "selected";
	}

	$pole = getCell(5);
	$fonction = getCell(6);
	$corps = getCell(7);
	$rang = getCell(8);
	$year = date('Y');

	$annee_naissance = null;
	$annee_these = null;
	$annee_hdr = null;
	$annee_etat = null;
	$date_entree = null;
	$date_sortie = null;

	if(isRegistered()){
		$annee_naissance = (int)getCell(11);
		$annee_these = (int)getCell(12);
		$annee_hdr = (int)getCell(13);
		$annee_etat = (int)getCell(14);
		$date_entree = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(9))));
		$date_sortie = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(10))));
	}

	return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Informations générales</h5>
		<label for="last_name">Nom</label>
			<input type="text" name="last_name" value="$last_name" class="ninja-forms-field nf-element" required disabled>
		<label for="name">Prénom</label>
			<input type="text" name="name" value="$name" required disabled>
		</br>
		<label for="equipe1">Equipe 2017-2022</label>
		<select name="equipe1">
			<option value=" "></option>
    		<option value="DEMO" $demo>DEMO</option>
    		<option value="PGM2" $pgm2>PGM2</option>
    		<option value="PPB" $ppb>PPB</option>
		</select>
		<label for="equipe2">Equipe 2022-2025</label>
		<select name="equipe2">
			<option value=" "></option>
			<option value="PETRODYN" $petrodyn>PETRODYN</option>
			<option value="TECTO" $tecto>TECTO</option>
			<option value="TERMER" $termer>TERMER</option>
		</select>
		<label for="equipe3">Equipe 2025-…</label>
		<select name="equipe3">
			<option value=" "></option>
			<option value="PETRODYN" $petrodyn2>PETRODYN</option>
			<option value="TECTO" $tecto2>TECTO</option>
			<option value="TERMER" $termer2>TERMER</option>
			<option value="PRISME" $prisme>PRISME</option>
		</select>
		<label for="pole">Pôles des services généraux (le cas échéant)</label>
			<input type="text" name="pole" value="$pole" required>
		<label for="fonction">Fonction exercée</label>
			<input type="text" name="fonction" value="$fonction" required>
		<label for="corps">Corps</label>
			<input type="text" name="corps" value="$corps" required>
		<label for="rang">Rang</label>
			<input type="text" name="rang" value="$rang" required>
		<label for="date_entree">Date entrée (MM/AAAA)</label>
			<input type="date" name="date_entree" value="$date_entree" required>
		<label for="date_sortie">Date sortie (MM/AAAA)</label>
			<input type="date" name="date_sortie" value="$date_sortie" required>
		<label for="annee_naissance">année naissance</label>
			<input type="number" min="1900" max="$year" name="annee_naissance" value="$annee_naissance" required>
		<label for="annee_obtention_these">année obtention thèse</label>
			<input type="number" min="1900" max="$year" name="annee_obtention_these" value="$annee_these" required>
		<label for="annee_obtention_hdr">année obtention HDR</label>
			<input type="number" min="1900" max="$year" name="annee_obtention_hdr" value="$annee_hdr" required>
		<label for="annee_obtention_these_etat">année obtention Thèse d'état</label>
			<input type="number" min="1900" max="$year" name="annee_obtention_these_etat" value="$annee_etat" required>
		<button type="submit" name="submit1">Envoyer</button>
	</form>
HTML;
}

add_shortcode('add_istep_annual_table_form_block_2','block2');
function block2(): string{

	if(isRegistered()){
		if(isset($_POST["submit2"])) {
			$data = [
				$_POST["discipline1"],
				$_POST["discipline2"]
			];
			replace_or_pushes_values(16, $data);
		}

		$disc1 = null;
		$disc2 = null;

		if(getCell(16) !== " "){
			$disc1 = getCell(16);
		}

		if(getCell(17) !== " "){
			$disc2 = getCell(17);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Discipline</h5>
		<label for="discipline1">Discipline 1</label>
			<input type="text" name="discipline1" value="$disc1" required>
		<label for="discipline2">Discipline 2</label>
			<input type="text" name="discipline2" value="$disc2" required>
	<button type="submit" name="submit2">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_3','block3');
function block3(): string{

	if(isRegistered()){
			if(isset($_POST["submit3"])){
				$data = [
					$_POST["theme_recherche"]
				];
				replace_or_pushes_values(19, $data);
			}

	$theme = "";

	if(getCell(19) !== " "){
		$theme = getCell(19);
	}

	return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Thèmes de recherche (80 mots max)</h5>
			<input type="text" name="theme_recherche" value="$theme" id="theme_recherche" oninput="limitWords()" required>
		<button type="submit" name="submit3">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_4','block4');
function block4(): string{

	if(isRegistered()){
		if(isset($_POST["submit4"])){
			$data = [
				$_POST["nb_publi_rang_a1"],
				$_POST["nb_publi_rang_premier"],
				$_POST["nb_citations_isi"],
				$_POST["h_factor_isi"],
				$_POST["nb_citations_isi_google"],
				$_POST["h_factor_google"],
				$_POST["nb_resume_conference"]
			];
			replace_or_pushes_values(21, $data);
		}

		$nb_publi_rang_a1 = null;
		$nb_publi_rang_premier = null;
		$nb_citations_isi = null;
		$h_factor_isi = null;
		$nb_citations_isi_google = null;
		$h_factor_google = null;
		$nb_resume_conference = null;

		if(getCell(21) !== " "){
			$nb_publi_rang_a1 = (int)getCell(21);
		}

		if(getCell(22) !== " "){
			$nb_publi_rang_premier = (int)getCell(22);
		}

		if(getCell(23) !== " "){
			$nb_citations_isi = (int)getCell(23);
		}

		if(getCell(24) !== " "){
			$h_factor_isi = getCell(24);
		}

		if(getCell(25) !== " "){
			$nb_citations_isi_google = (int)getCell(25);
		}

		if(getCell(26) !== " "){
			$h_factor_google = getCell(26);
		}

		if(getCell(27) !== " "){
			$nb_resume_conference = (int)getCell(27);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Publications sur l'ensemble de la carrière jusqu'à aujourd'hui</h5>
		<label for="nb_publi_rang_a1">Nombre total de publi de rang A</label>
			<input type="number" name="nb_publi_rang_a1" min="0" value="$nb_publi_rang_a1" required>
		<label for="nb_publi_rang_premier">Nombre total de publi de rang A en 1ier auteur ou derrière un doctorant</label>
			<input type="number" name="nb_publi_rang_premier" min="0" value="$nb_publi_rang_premier" required>
		<label for="nb_citations_isi">nombre de citations (isi-web of science)</label>
			<input type="number" name="nb_citations_isi" min="0" value="$nb_citations_isi" required>
		<label for="h_factor_isi">h-factor (Isi-Web)</label>
			<input type="text" name="h_factor_isi" value="$h_factor_isi" required>
		<label for="nb_citations_isi_google">nombre de citations (google scholar)</label>
			<input type="number" name="nb_citations_isi_google" min="0" value="$nb_citations_isi_google" required>
		<label for="h_factor_google">h-factor (google scholar)</label>
			<input type="text" name="h_factor_google" value="$h_factor_google" required>
		<label for="nb_resume_conference">Nbre de résumé à conférence avec comité de lecture</label>
			<input type="number" name="nb_resume_conference" min="0" value="$nb_resume_conference" required>
		<button type="submit" name="submit4">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_5','block5');
function block5(): string{

	if(isRegistered()){
		if(isset($_POST["submit5"])){
			$data = [
				$_POST["nb_publi_rang_a2"],
				$_POST["nb_publi_premier"],
				$_POST["nb_article_doctorant"],
				$_POST["nb_article_rang_a_collab"],
				$_POST["chapitre_ouvrage"],
				$_POST["nb_resume_comite_lecture"],
			];
			replace_or_pushes_values(29, $data);
		}

		$nb_publi_rang_a2 = null;
		$nb_publi_premier = null;
		$nb_article_doctorant = null;
		$nb_article_rang_a_collab = null;
		$chapitre_ouvrage = null;
		$nb_resume_comite_lecture = null;

		if(getCell(29) !== " "){
			$nb_publi_rang_a2 = (int)getCell(29);
		}

		if(getCell(30) !== " "){
			$nb_publi_premier = (int)getCell(30);
		}

		if(getCell(31) !== " "){
			$nb_article_doctorant = (int)getCell(31);
		}

		if(getCell(32) !== " "){
			$nb_article_rang_a_collab = (int)getCell(32);
		}

		if(getCell(33) !== " "){
			$chapitre_ouvrage = getCell(33);
		}

		if(getCell(34) !== " "){
			$nb_resume_comite_lecture = (int)getCell(34);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Détail des publications par année depuis 2022</h5>
		<label for="nb_publi_rang_a2">Nombre total de publi de rang A</label>
			<input type="number" name="nb_publi_rang_a2" min="0" value="$nb_publi_rang_a2" required>
		<label for="nb_publi_premier">Nbre article en 1er auteur</label>
			<input type="number" name="nb_publi_premier" min="0" value="$nb_publi_premier" required>
		<label for="nb_article_doctorant">Nbre article  derrière un doctorant</label>
			<input type="number" name="nb_article_doctorant" min="0" value="$nb_article_doctorant" required>
		<label for="nb_article_rang_a_collab">Nbre d'articles rang A avec des collab. (autres laboratoires)</label>
			<input type="number" name="nb_article_rang_a_collab" min="0" value="$nb_article_rang_a_collab" required>
		<label for="chapitre_ouvrage">Chapitre d'ouvrage / livre</label>
			<input type="text" name="chapitre_ouvrage" value="$chapitre_ouvrage" required>
		<label for="nb_resume_comite_lecture">Nbre de résumé à des congrès avec comité de lecture</label>
			<input type="number" name="nb_resume_comite_lecture" min="0" value="$nb_resume_comite_lecture" required>
		<button type="submit" name="submit5">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_6','block6');
function block6(): string{

	if(isRegistered()){
		if(isset($_POST["submit6"])){
			$data = [
				$_POST["enseignement1"],
				$_POST["enseignement2"],
				$_POST["enseignement3"],
				$_POST["enseignement4"],
			];
			replace_or_pushes_values(36, $data);
		}

		$enseignement1 = null;
		$enseignement2 = null;
		$enseignement3 = null;
		$enseignement4 = null;

		if(getCell(36) !== " "){
			$enseignement1 = (int)getCell(36);
		}

		if(getCell(37) !== " "){
			$enseignement2 = (int)getCell(37);
		}

		if(getCell(38) !== " "){
			$enseignement3 = (int)getCell(38);
		}

		if(getCell(39) !== " "){
			$enseignement4 = (int)getCell(39);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Enseignement</h5>
		<label for="enseignement1">nb heures enseignées 2022-2023</label>
			<input type="number" name="enseignement1" min="0" value="$enseignement1" required>
		<label for="enseignement2">nb heures enseignées 2023-2024</label>
			<input type="number" name="enseignement2" min="0" value="$enseignement2" required>
		<label for="enseignement3">nb heures enseignées 2024-2025</label>
			<input type="number" name="enseignement3" min="0" value="$enseignement3" required>
		<label for="enseignement4">nb heures enseignées 2025-2026</label>
			<input type="number" name="enseignement4" min="0" value="$enseignement4" required>
		<button type="submit" name="submit6">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_7','block7');
function block7(): string{

	if (isRegistered()){
		if(isset($_POST["submit7"])){
			$data = [
				$_POST["master1_nom"],
				$_POST["master1_prenom"],
				$_POST["master1_annee"],
				$_POST["master1_nom_prenom_co-encadrants"],
				$_POST["master1_sujet"]
			];
			replace_or_pushes_values(41, $data);
		}

		$master1_nom = null;
		$master1_prenom = null;
		$master1_annee = null;
		$master1_nom_prenom_co_encadrants = null;
		$master1_sujet = null;

		if(getCell(41) !== " "){
			$master1_nom = getCell(41);
		}

		if(getCell(42) !== " "){
			$master1_prenom = getCell(42);
		}

		if(getCell(43) !== " "){
			$master1_annee = (int)getCell(43);
		}

		if(getCell(44) !== " "){
			$master1_nom_prenom_co_encadrants = getCell(44);
		}

		if(getCell(45) !== " "){
			$master1_sujet = getCell(45);
		}

		$year = date('Y');

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Encadrement Master 1 (à partir de 2022)</h5>
		<label for="master1_nom">Nom</label>
			<input type="text" name="master1_nom" value="$master1_nom" required>
		<label for="master1_prenom">Prénom</label>
			<input type="text" name="master1_prenom" value="$master1_prenom" required>
		<label for="master1_annee">Année</label>
			<input type="number" min="2022" max="$year" name="master1_annee" value="$master1_annee" required>
		<label for="master1_nom_prenom_co-encadrants">NOM Prénom des Co-encadrants</label>
			<input type="text" name="master1_nom_prenom_co-encadrants" value="$master1_nom_prenom_co_encadrants" required>
		<label for="master1_sujet">Titre sujet (indiquer si hors ISTeP)</label>
			<input type="text" name="master1_sujet" value="$master1_sujet" required>
		<button type="submit" name="submit7">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_8','block8');
function block8(): string{

	if (isRegistered()){
		if(isset($_POST["submit8"])){
			$data = [
				$_POST["master2_nom"],
				$_POST["master2_prenom"],
				$_POST["master2_annee"],
				$_POST["master2_nom_prenom_co-encadrants"],
				$_POST["master2_sujet"]
			];
			replace_or_pushes_values(47, $data);
		}

		$master2_nom = null;
		$master2_prenom = null;
		$master2_annee = null;
		$master2_nom_prenom_co_encadrants = null;
		$master2_sujet = null;

		if(getCell(47) !== " "){
			$master2_nom = getCell(47);
		}

		if(getCell(48) !== " "){
			$master2_prenom = getCell(48);
		}

		if(getCell(49) !== " "){
			$master2_annee = (int)getCell(49);
		}

		if(getCell(50) !== " "){
			$master2_nom_prenom_co_encadrants = getCell(50);
		}

		if(getCell(51) !== " "){
			$master2_sujet = getCell(51);
		}

		$year = date('Y');
		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Encadrement Master 2 (à partir de 2022)</h5>
		<label for="master2_nom">Nom</label>
			<input type="text" name="master2_nom" value="$master2_nom" required>
		<label for="master2_prenom">Prénom</label>
			<input type="text" name="master2_prenom" value="$master2_prenom" required>
		<label for="master2_annee">Année</label>
			<input type="number" min="2022" max="$year" name="master2_annee" value="$master2_annee" required>
		<label for="master2_nom_prenom_co-encadrants">NOM Prénom des Co-encadrants</label>
			<input type="text" name="master2_nom_prenom_co-encadrants" value="$master2_nom_prenom_co_encadrants" required>
		<label for="master2_sujet">Titre sujet (indiquer si hors ISTeP)</label>
			<input type="text" name="master2_sujet" value="$master2_sujet" required>
		<button type="submit" name="submit8">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_9','block9');
function block9(): string{

	if (isRegistered()){
		if(isset($_POST["submit9"])){
			$data = [
				$_POST["encadrement_istep_nom"],
				$_POST["encadrement_istep_prenom"],
				$_POST["sexe"],
				date("m/Y", strtotime($_POST["encadrement_istep_date_inscription_these"])),
				date("m/Y", strtotime($_POST["encadrement_istep_date_soutenance"])),
				$_POST["encadrement_istep_nom_prenom_co-directerurs"],
				$_POST["encadrement_istep_titre_these"],
				$_POST["encadrement_istep_etablissement"],
				$_POST["encadrement_istep_numero_ed"],
				$_POST["encadrement_istep_financement_doctorat"],
				$_POST["encadrement_istep_fonction"]
			];
			replace_or_pushes_values(53, $data);
		}

		$encadrement_istep_nom = null;
		$encadrement_istep_prenom = null;
		$homme = null;
		$femme = null;
		$encadrement_istep_date_inscription_these = null;
		$encadrement_istep_date_soutenance = null;
		$encadrement_istep_nom_prenom_co = null;
		$encadrement_istep_titre_these = null;
		$encadrement_istep_etablissement = null;
		$encadrement_istep_numero_ed = null;
		$encadrement_istep_financement_doctorat = null;
		$encadrement_istep_fonction = null;


		if(getCell(53) !== " "){
			$encadrement_istep_nom = getCell(53);
		}

		if(getCell(54) !== " "){
			$encadrement_istep_prenom = getCell(54);
		}

		if(getCell(55) === "Homme"){
			$homme = "selected";
		}
		if(getCell(55) === "Femme"){
			$femme = "selected";
		}

		if(getCell(56) !== " "){
			$encadrement_istep_date_inscription_these = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(56))));
		}

		if(getCell(57) !== " "){
			$encadrement_istep_date_soutenance = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(57))));
		}

		if(getCell(58) !== " "){
			$encadrement_istep_nom_prenom_co = getCell(58);
		}

		if(getCell(59) !== " "){
			$encadrement_istep_titre_these = getCell(59);
		}

		if(getCell(60) !== " "){
			$encadrement_istep_etablissement = getCell(60);
		}
		if(getCell(61) !== " "){
			$encadrement_istep_numero_ed = getCell(61);
		}

		if(getCell(62) !== " "){
			$encadrement_istep_financement_doctorat = getCell(62);
		}

		if(getCell(63) !== " "){
			$encadrement_istep_fonction = getCell(63);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Encadrement thèse ISTeP à partir de 2022</h5>
		<label for="encadrement_istep_nom">Nom</label>
			<input type="text" name="encadrement_istep_nom" value="$encadrement_istep_nom" required>
		<label for="encadrement_istep_prenom">Prénom</label>
			<input type="text" name="encadrement_istep_prenom" value="$encadrement_istep_prenom" required>
		<label for="encadrement_istep_hf">H/F</label>
			<select name="sexe">
				<option value=" "></option>
				<option value="Homme" $homme>Homme</option>
				<option value="Femme" $femme>Femme</option>
			</select>
		<label for="encadrement_istep_date_inscription_these">Date d'inscription en thèse (MM/AAAA)</label>
			<input type="date" name="encadrement_istep_date_inscription_these" value="$encadrement_istep_date_inscription_these" required>
		<label for="encadrement_istep_date_soutenance">Date de soutenance (MM/AAAA)</label>
			<input type="date" name="encadrement_istep_date_soutenance" value="$encadrement_istep_date_soutenance" required>
		<label for="encadrement_istep_nom_prenom_co-directerurs">NOM Prénom des Co-directeurs</label>
			<input type="text" name="encadrement_istep_nom_prenom_co-directerurs" value="$encadrement_istep_nom_prenom_co" required>
		<label for="encadrement_istep_titre_these">Titre thèse</label>
			<input type="text" name="encadrement_istep_titre_these" value="$encadrement_istep_titre_these" required>
		<label for="encadrement_istep_etablissement">Établissement ayant délivré le master (ou diplôme équivalent)</label>
			<input type="text" name="encadrement_istep_etablissement" value="$encadrement_istep_etablissement" required>
		<label for="encadrement_istep_numero_ed">Numéro de l'ED de rattachement</label>
			<input type="text" name="encadrement_istep_numero_ed" value="$encadrement_istep_numero_ed" required>
		<label for="encadrement_istep_financement_doctorat">Financement du doctorat</label>
			<input type="text" name="encadrement_istep_financement_doctorat" value="$encadrement_istep_financement_doctorat" required>
		<label for="encadrement_istep_fonction">Fonction de direction ou encadrement ?</label>
			<input type="text" name="encadrement_istep_fonction" value="$encadrement_istep_fonction" required>
		<button type="submit" name="submit9">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_10','block10');
function block10(): string{

	if (isRegistered()){
		if(isset($_POST["submit10"])){
			$data = [
				$_POST["encadrement_histep_nom"],
				$_POST["encadrement_histep_prenom"],
				$_POST["sexe"],
				date("m/Y", strtotime($_POST["encadrement_histep_date_inscription_these"])),
				date("m/Y", strtotime($_POST["encadrement_histep_date_soutenance"])),
				$_POST["encadrement_histep_direction_these"],
				$_POST["encadrement_histep_titre_these"],
				$_POST["encadrement_histep_etablissement"],
				$_POST["encadrement_histep_numero_ed"],
				$_POST["encadrement_histep_etablissement_rattachement_direction_these"],
				$_POST["encadrement_histep_financement_doctorat"],
				$_POST["encadrement_histep_fonction"]
			];
			replace_or_pushes_values(65, $data);
		}

		$encadrement_histep_nom = null;
		$encadrement_histep_prenom = null;
		$homme = null;
		$femme = null;
		$encadrement_histep_date_inscription_these = null;
		$encadrement_histep_date_soutenance = null;
		$encadrement_histep_direction_these = null;
		$encadrement_histep_titre_these = null;
		$encadrement_histep_etablissement = null;
		$encadrement_histep_numero_ed = null;
		$encadrement_histep_etablissement_rattachement_direction_these = null;
		$encadrement_histep_financement_doctorat = null;
		$encadrement_histep_fonction = null;


		if(getCell(65) !== " "){
			$encadrement_histep_nom = getCell(65);
		}

		if(getCell(66) !== " "){
			$encadrement_histep_prenom = getCell(66);
		}

		if(getCell(67) === "Homme"){
			$homme = "selected";
		}
		if(getCell(67) === "Femme"){
			$femme = "selected";
		}

		if(getCell(68) !== " "){
			$encadrement_histep_date_inscription_these = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(68))));
		}

		if(getCell(69) !== " "){
			$encadrement_histep_date_soutenance = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(69))));
		}

		if(getCell(70) !== " "){
			$encadrement_histep_direction_these = getCell(70);
		}

		if(getCell(71) !== " "){
			$encadrement_histep_titre_these = getCell(71);
		}

		if(getCell(72) !== " "){
			$encadrement_histep_etablissement = getCell(72);
		}
		if(getCell(73) !== " "){
			$encadrement_histep_numero_ed = getCell(73);
		}

		if(getCell(74) !== " "){
			$encadrement_histep_etablissement_rattachement_direction_these = getCell(74);
		}

		if(getCell(75) !== " "){
			$encadrement_histep_financement_doctorat = getCell(75);
		}

		if(getCell(76) !== " "){
			$encadrement_histep_fonction = getCell(76);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Encadrement thèse hors ISTeP à partir de 2022</h5>
		<label for="encadrement_histep_nom">Nom</label>
			<input type="text" name="encadrement_histep_nom" value="$encadrement_histep_nom" required>
		<label for="encadrement_histep_prenom">Prénom</label>
			<input type="text" name="encadrement_histep_prenom" value="$encadrement_histep_prenom" required>
		<label for="encadrement_histep_hf">H/F</label>
			<select name="sexe">
				<option value=" "></option>
				<option value="Homme" $homme>Homme</option>
				<option value="Femme" $femme>Femme</option>
			</select>
		<label for="encadrement_histep_date_inscription_these">Date d'inscription en thèse (MM/AAAA)</label>
			<input type="date" name="encadrement_histep_date_inscription_these" value="$encadrement_histep_date_inscription_these" required>
		<label for="encadrement_histep_date_soutenance">Date de soutenance (MM/AAAA)</label>
			<input type="date" name="encadrement_histep_date_soutenance" value="$encadrement_histep_date_soutenance" required>
		<label for="encadrement_histep_direction_these">Direction de thèse (Nom, Prénom)</label>
			<input type="text" name="encadrement_histep_direction_these" value="$encadrement_histep_direction_these" required>
		<label for="encadrement_histep_titre_these">Titre thèse</label>
			<input type="text" name="encadrement_histep_titre_these" value="$encadrement_histep_titre_these" required>
		<label for="encadrement_histep_etablissement">Établissement ayant délivré le master (ou diplôme équivalent)</label>
			<input type="text" name="encadrement_histep_etablissement" value="$encadrement_histep_etablissement" required>
		<label for="encadrement_histep_numero_ed">Numéro de l'ED de rattachement</label>
			<input type="text" name="encadrement_histep_numero_ed" value="$encadrement_histep_numero_ed" required>
		<label for="encadrement_histep_etablissement_rattachement_direction_these">Etablissement de rattachement de la direction de thèse</label>
			<input type="text" name="encadrement_histep_etablissement_rattachement_direction_these" value="$encadrement_histep_etablissement_rattachement_direction_these" required>
		<label for="encadrement_histep_financement_doctorat">Financement du doctorat</label>
			<input type="text" name="encadrement_histep_financement_doctorat" value="$encadrement_histep_financement_doctorat" required>
		<label for="encadrement_histep_fonction">Fonction de direction ou encadrement ?</label>
			<input type="text" name="encadrement_histep_fonction" value="$encadrement_histep_fonction" required>
		<button type="submit" name="submit10">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_11','block11');
function block11(): string{
	if(isRegistered()){
		if(isset($_POST["submit11"])){
			$data = [
				$_POST["encadrement_pd_nom"],
				$_POST["encadrement_pd_prenom"],
				$_POST["sexe"],
				date("m/Y", strtotime($_POST["encadrement_pd_date_entree"])),
				date("m/Y", strtotime($_POST["encadrement_pd_date_sortie"])),
				$_POST["encadrement_pd_annee_naissance"],
				$_POST["encadrement_pd_employeur"]
			];
			replace_or_pushes_values(78, $data);
		}

		$encadrement_pd_nom = null;
		$encadrement_pd_prenom = null;
		$homme = null;
		$femme = null;
		$encadrement_pd_date_entree = null;
		$encadrement_pd_date_sortie = null;
		$encadrement_pd_annee_naissance = null;
		$encadrement_pd_employeur = null;


		if(getCell(78) !== " "){
			$encadrement_pd_nom = getCell(78);
		}

		if(getCell(79) !== " "){
			$encadrement_pd_prenom = getCell(79);
		}

		if(getCell(80) === "Homme"){
			$homme = "selected";
		}
		if(getCell(80) === "Femme"){
			$femme = "selected";
		}

		if(getCell(81) !== " "){
			$encadrement_pd_date_entree = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(81))));
		}

		if(getCell(82) !== " "){
			$encadrement_pd_date_sortie = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(82))));
		}

		if(getCell(83) !== " "){
			$encadrement_pd_annee_naissance = getCell(83);
		}

		if(getCell(84) !== " "){
			$encadrement_pd_employeur = getCell(84);
		}

		$year = date('Y');

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Encadrement de post-doctorats à partir de 2022</h5>
		<label for="encadrement_pd_nom">Nom</label>
			<input type="text" name="encadrement_pd_nom" value="$encadrement_pd_nom" required>
		<label for="encadrement_pd_prenom">Prénom</label>
			<input type="text" name="encadrement_pd_prenom" value="$encadrement_pd_prenom" required>
		<label for="encadrement_pd_hf">H/F</label>
			<select name="sexe">
				<option value=""></option>
				<option value="Homme" $homme>Homme</option>
				<option value="Femme" $femme>Femme</option>
			</select>
		<label for="encadrement_pd_date_entree">Date d'entrée (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_entree" value="$encadrement_pd_date_entree" required>
		<label for="encadrement_pd_date_sortie">Date de sortie (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_sortie" value="$encadrement_pd_date_sortie" required>
		<label for="encadrement_pd_annee_naissance">Année de naissance</label>
			<input type="number" min="1900" max="$year" name="encadrement_pd_annee_naissance" value="$encadrement_pd_annee_naissance" required>
		<label for="encadrement_pd_employeur">Etablissement ou organisme employeur</label>
			<input type="text" name="encadrement_pd_employeur" value="$encadrement_pd_employeur" required>
		<button type="submit" name="submit11">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_12','block12');
function block12(): string{
	if (isRegistered()){
		if(isset($_POST["submit12"])){
			$data = [
				$_POST["distinction_intitule"],
				$_POST["distinction_annee"]
			];
			replace_or_pushes_values(86, $data);
		}

		$distinction_intitule = null;
		$distinction_annee = null;

		if(getCell(86) !== " "){
			$distinction_intitule = getCell(86);
		}

		if(getCell(87) !== " "){
			$distinction_annee = getCell(87);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Prix ou distinctions scientifiques</h5>
		<label for="distinction_intitule">Intitulé de l'élément de distinction (nom du prix par exemple)</label>
			<input type="text" name="distinction_intitule" value="$distinction_intitule" required>
		<label for="distinction_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="distinction_annee" value="$distinction_annee" required>
		<button type="submit" name="submit12">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_13','block13');
function block13(): string{
	if (isRegistered()){
		if(isset($_POST["submit13"])){
			$data = [
				$_POST["iuf_intitule"],
				$_POST["iuf_annee"]
			];
			replace_or_pushes_values(89, $data);
		}

		$iuf_intitule = null;
		$iuf_annee = null;

		if(getCell(89) !== " "){
			$iuf_intitule = getCell(89);
		}

		if(getCell(90) !== " "){
			$iuf_annee = getCell(90);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Appartenance à l'IUF</h5>
		<label for="iuf_intitule">Intitulé de l'élément (membre, fonction …)</label>
			<input type="text" name="iuf_intitule" value="$iuf_intitule" required>
		<label for="iuf_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="iuf_annee" value="$iuf_annee" required>
		<button type="submit" name="submit13">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_14','block14');
function block14(): string{
	if (isRegistered()){
		if(isset($_POST["submit14"])){
			$data = [
				$_POST["sejour_lieu"],
				$_POST["sejour_annee"]
			];
			replace_or_pushes_values(92, $data);
		}

		$sejour_lieu = null;
		$sejour_annee = null;

		if(getCell(92) !== " "){
			$sejour_lieu = getCell(92);
		}

		if(getCell(93) !== " "){
			$sejour_annee = getCell(93);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Séjours dans des laboratoires étrangers</h5>
		<label for="sejour_lieu">Lieu, fonction</label>
			<input type="text" name="sejour_lieu" value="$sejour_lieu" required>
		<label for="sejour_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="sejour_annee" value="$sejour_annee" required>
		<button type="submit" name="submit14">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_15','block15');
function block15(): string{
	if (isRegistered()){
		if(isset($_POST["submit15"])){
			$data = [
				$_POST["organisation_nom"],
				$_POST["organisation_annee"]
			];
			replace_or_pushes_values(95, $data);
		}

		$organisation_nom = null;
		$organisation_annee = null;

		if(getCell(95) !== " "){
			$organisation_nom = getCell(95);
		}

		if(getCell(96) !== " "){
			$organisation_annee = getCell(96);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Organisations de colloques/congrès internationaux</h5>
		<label for="organisation_nom">Nom de l'évènement, fonction</label>
			<input type="text" name="organisation_nom" value="$organisation_nom" required>
		<label for="organisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="organisation_annee" value="$organisation_annee" required>
		<button type="submit" name="submit15">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_16','block16');
function block16(): string{
	if (isRegistered()){
		if(isset($_POST["submit16"])){
			$data = [
				$_POST["societe_savantes_nom"],
				$_POST["societe_savantes_annee"]
			];
			replace_or_pushes_values(98, $data);
		}

		$societe_savantes_nom = null;
		$societe_savantes_annee = null;

		if(getCell(98) !== " "){
			$societe_savantes_nom = getCell(98);
		}

		if(getCell(99) !== " "){
			$societe_savantes_annee = getCell(99);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Responsabilités dans des sociétés savantes</h5>
		<label for="societe_savantes_nom">Nom de la société, fonction</label>
			<input type="text" name="societe_savantes_nom" value="$societe_savantes_nom" required>
		<label for="societe_savantes_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="societe_savantes_annee" value="$societe_savantes_annee" required>
		<button type="submit" name="submit16">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_17','block17');
function block17(): string{
	if (isRegistered()){
		if(isset($_POST["submit17"])){
			$data = [
				$_POST["responsabilite1_region_montant"],
				$_POST["responsabilite1_region_nom"],
				"|",
				$_POST["responsabilite1_national_montant"],
				$_POST["responsabilite1_national_nom"],
				"|",
				$_POST["responsabilite1_international_montant"],
				$_POST["responsabilite1_international_nom"],
				"|",
				$_POST["responsabilite1_partenariat_montant"],
				$_POST["responsabilite1_partenariat_nom"]
			];
			replace_or_pushes_values(101, $data);
		}

		$responsabilite1_region_montant = null;
		$responsabilite1_region_nom = null;
		$responsabilite1_national_montant = null;
		$responsabilite1_national_nom = null;
		$responsabilite1_international_montant = null;
		$responsabilite1_international_nom = null;
		$responsabilite1_partenariat_montant = null;
		$responsabilite1_partenariat_nom = null;

		if(getCell(101) !== " "){
			$responsabilite1_region_montant = getCell(101);
		}

		if(getCell(102) !== " "){
			$responsabilite1_region_nom = getCell(102);
		}

		if(getCell(103) !== " "){
			$responsabilite1_national_montant = getCell(103);
		}

		if(getCell(104) !== " "){
			$responsabilite1_national_nom = getCell(104);
		}

		if(getCell(105) !== " "){
			$responsabilite1_international_montant = getCell(105);
		}

		if(getCell(106) !== " "){
			$responsabilite1_international_nom = getCell(106);
		}

		if(getCell(107) !== " "){
			$responsabilite1_partenariat_montant = getCell(107);
		}

		if(getCell(108) !== " "){
			$responsabilite1_partenariat_nom = getCell(108);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h4>Responsabilité de projets de recherche (ou tasks indépendantes)</h4>
		
		<h5>Régional et local</h5>
		<label for="responsabilite1_region_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_region_montant" value="$responsabilite1_region_montant" required>
		<label for="responsabilite1_region_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_region_nom" value="$responsabilite1_region_nom" required>
		
		<h5>National</h5>
		<label for="responsabilite1_national_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_national_montant" value="$responsabilite1_national_montant" required>
		<label for="responsabilite1_national_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_national_nom" value="$responsabilite1_national_nom" required>
		
		<h5>International</h5>
		<label for="responsabilite1_international_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_international_montant" value="$responsabilite1_international_montant" required>
		<label for="responsabilite1_international_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_international_nom" value="$responsabilite1_international_nom" required>
		
		<h5>Partenariat (industrie, EPIC)</h5>
		<label for="responsabilite1_partenariat_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_partenariat_montant" value="$responsabilite1_partenariat_montant" required>
		<label for="responsabilite1_partenariat_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_partenariat_nom" value="$responsabilite1_partenariat_nom" required>
		<button type="submit" name="submit17">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_18','block18');
function block18(): string{
	if (isRegistered()){
		if(isset($_POST["submit18"])){
			$data = [
				$_POST["responsabilite2_locale_intitule"],
				$_POST["responsabilite2_locale_annee"],
				"|",
				$_POST["responsabilite2_regional_intitule"],
				$_POST["responsabilite2_regional_annee"],
				"|",
				$_POST["responsabilite2_international_intitule"],
				$_POST["responsabilite2_international_annee"]
			];
			replace_or_pushes_values(110, $data);
		}

		$responsabilite2_locale_intitule = null;
		$responsabilite2_locale_annee = null;
		$responsabilite2_regional_intitule = null;
		$responsabilite2_regional_annee = null;
		$responsabilite2_international_intitule = null;
		$responsabilite2_international_annee = null;

		if(getCell(110) !== " "){
			$responsabilite2_locale_intitule = getCell(110);
		}

		if(getCell(111) !== " "){
			$responsabilite2_locale_annee = getCell(111);
		}

		if(getCell(112) !== " "){
			$responsabilite2_regional_intitule = getCell(112);
		}

		if(getCell(113) !== " "){
			$responsabilite2_regional_annee = getCell(113);
		}

		if(getCell(114) !== " "){
			$responsabilite2_international_intitule = getCell(114);
		}

		if(getCell(115) !== " "){
			$responsabilite2_international_annee = getCell(115);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h4>Responsabilités, Expertises & administration de la recherche</h4>
		
		<h5>Locale</h5>
		<label for="responsabilite2_locale_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_locale_intitule" value="$responsabilite2_locale_intitule" required>
		<label for="responsabilite2_locale_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_locale_annee" value="$responsabilite2_locale_annee" required>
		
		<h5>Régional</h5>
		<label for="responsabilite2_regional_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_regional_intitule" value="$responsabilite2_regional_intitule" required>
		<label for="responsabilite2_regional_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_regional_annee" value="$responsabilite2_regional_annee" required>
		
		<h5>Internationale</h5>
		<label for="responsabilite2_international_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_international_intitule" value="$responsabilite2_international_intitule" required>
		<label for="responsabilite2_international_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_international_annee" value="$responsabilite2_international_annee" required>
		<button type="submit" name="submit18">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_19','block19');
function block19(): string{
	if (isRegistered()){
		if(isset($_POST["submit19"])){
			$data = [
				$_POST["responsabilite3_intitule"],
				$_POST["responsabilite3_annee"]
			];
			replace_or_pushes_values(117, $data);
		}

		$responsabilite3_intitule = null;
		$responsabilite3_annee = null;

		if(getCell(117) !== " "){
			$responsabilite3_intitule = getCell(117);
		}

		if(getCell(118) !== " "){
			$responsabilite3_annee = getCell(118);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Responsabilités & administration de la formation/enseignement</h5>
		<label for="responsabilite3_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="responsabilite3_intitule" value="$responsabilite3_intitule" required>
		<label for="responsabilite3_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite3_annee" value="$responsabilite3_annee" required>
		<button type="submit" name="submit19">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_20','block20');
function block20(): string{
	if (isRegistered()){
		if(isset($_POST["submit20"])){
			$data = [
				$_POST["vulgarisation_intitule"],
				$_POST["vulgarisation_annee"]
			];
			replace_or_pushes_values(120, $data);
		}

		$vulgarisation_intitule = null;
		$vulgarisation_annee = null;

		if(getCell(120) !== " "){
			$vulgarisation_intitule = getCell(120);
		}

		if(getCell(121) !== " "){
			$vulgarisation_annee = getCell(121);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Vulgarisation, dissémination scientifique</h5>
		<label for="vulgarisation_intitule">Intitulé de l'élément (évènement, vidéo, livre, …) et fonction</label>
			<input type="text" name="vulgarisation_intitule" value="$vulgarisation_intitule" required>
		<label for="vulgarisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="vulgarisation_annee" value="$vulgarisation_annee" required>
		<button type="submit" name="submit20">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_21','block21');
function block21(): string{
	if (isRegistered()){
		if(isset($_POST["submit21"])){
			$data = [
				$_POST["rayonnement"]
			];
			replace_or_pushes_values(123, $data);
		}

		$rayonnement = null;

		if(getCell(123) !== " "){
			$rayonnement = getCell(123);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Rayonnement / résultats majeurs sur la période à mettre en avant</h5>
			<input type="text" name="rayonnement" value="$rayonnement" required>
		<button type="submit" name="submit21">Envoyer</button>
	</form>
HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}

add_shortcode('add_istep_annual_table_form_block_22','block22');
function block22(): string{
	if (isRegistered()){
		if(isset($_POST["submit22"])){
			$data = [
				$_POST["brevet_intitule"],
				$_POST["brevet_annee"]
			];
			replace_or_pushes_values(125, $data);
		}

		$brevet_intitule = null;
		$brevet_annee = null;

		if(getCell(125) !== " "){
			$brevet_intitule = getCell(125);
		}

		if(getCell(126) !== " "){
			$brevet_annee = getCell(126);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Brevet</h5>
		<label for="brevet_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="brevet_intitule" value="$brevet_intitule" required>
		<label for="brevet_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="brevet_annee" value="$brevet_annee" required>
		<button type="submit" name="submit22">Envoyer</button>
	</form>

HTML;
	} else{
		return <<<HTML
			<p class="acces_denied">Accès non autorisé</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
	}
}