<?php
/*
Plugin Name: HCERES
Plugin URI: https://axphyr.github.io/
Description: Crée et gère un tableau de données pour l'ISTeP
Author: Arbër Jonuzi
Version: 1.0
Author URI: https://axphyr.github.io/
*/
function enqueue_my_scripts_and_styles(): void {
	// Enqueue the data-table.js script
	wp_enqueue_script('data-table-js', plugins_url('data-table.js', __FILE__), array(), false, true);

	// Enqueue the style.css file
	wp_enqueue_style('style', plugins_url('style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'enqueue_my_scripts_and_styles');

/** Creates the csv file with the headers and initializes the plugin
 * @return void
 */
function annual_data_table_install(): void
{
	// defines the directories name
	$dirName = 'hceres';

	if (!is_dir($dirName)) {
		mkdir($dirName);
	}

	// defines the files name and path
	$filename = 'data-table.csv';
	$filepath = $dirName . '/' . $filename;

	// verifies if the file exists or not
	if (file_exists($filepath)) {
		// erases the file
		unlink($filepath);
	}

	// defines the csv file's headers
	$data = array(
		array(' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', ' ', 'Responsabilité de projets de recherche (ou tasks indépendantes)', ' ', ' ', ' ', ' ', ' ', ' ', '|', ' ', ' ', ' ', 'Responsabilités, Expertises & administration de la recherche', ' ', ' ', ' ', ' ', '|'),
		// categories (a backspace per categories and spaces to align)
		// ' ' are here only to center the headers
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

		// fields per categories (a backspace per categories)
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

	// opens the file for writing
	$file = fopen($filepath, 'w');

	// inserts the headers into the file
	foreach ($data as $row) {
		fputcsv($file, $row);
	}

	// closes the file
	fclose($file);

	// adds WP pages for every headers
	$lst = ["Informations Generales", "Discipline", "Theme de recherche", "Publications 1", "Publications 2", "Enseignement", "Master 1", "Master 2", "Encadrement these ISTeP", "Encadrement these hors ISTeP", "Encadrement post-doctorats", "Prix ou Distinctions", "Appartenance IUF", "Sejours", "Colloques/Congres", "Societes Savantes", "Responsabilites de projets de recherche", "Responsabilites, Expertises & administration de la recherche", "Responsabilites administratives", "Vulgarisation & dissemination scientifique", "Rayonnement", "Brevet" ];
	for($i = 0; $i < 22; $i++){
		create_custom_pages("(DT) Formulaire " . $lst[$i], "add_istep_annual_table_form_block_" . $i + 1);
	}
}
register_activation_hook( __FILE__, 'annual_data_table_install' );

/** Function to delete custom pages on plugin deactivation.
 * @return void
 */
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

	// starts a querry to search the pages
	$query = new WP_Query($args);
	$page_ids = array();

	// registers the page's id
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

/**
 * function to launch on plugin deactivation
 */
register_deactivation_hook(__FILE__, 'delete_custom_pages');

add_shortcode('add_istep_annual_table_download','download_annual_table');

/** Function to create custom pages with a specific shortcode.
 * @param $title
 * @param $content
 *
 * @return void
 */
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

/** Returns the button to download the csv file.
 * @return string button html element
 */
function download_annual_table(): string {
	return <<<HTML
	<button><a class="dt-download" href="/wp-admin/hceres/data-table.csv" download> Accéder au fichier </a></button>
HTML;
}

/** Searches in the csv file if the current WP user is registered by looking
 * at the WP user's last name.
 * @return bool returns true if the user is found, false else.
 */
function isRegistered(): bool {
	// opens the file
	$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r');

	// initializes the returned boolean
	$is_registered = false;

	// searches through the file for an occurence of the WP user's last name
	while (($row = fgetcsv($file)) !== false) {
		if (ucfirst(wp_get_current_user()->last_name) === $row[0]) {
			$is_registered = true;
			break;
		}
	}

	// closes the file
	fclose($file);

	return $is_registered;
}

/** Searches in the file the first occurence of a user's name to locate him.
 * @return int the id of the user in the csv file
 */
function user_id_in_csv_file(): int
{
	// opens the file
	$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r');

	// initializes the user's id to 1
	$id = 1;

	// searches through the file for the user's last name
	while (($row = fgetcsv($file)) !== false) {
		if (in_array(ucfirst(wp_get_current_user()->first_name), $row)) {
			fclose($file);
			return $id;
		}
		// increments the id variable if the user is not found on the current row
		$id++;
	}

	// closes the file
	fclose($file);

	return 0;
}

/** Deletes the user's row in the csv file to replace by the new one
 * @param $file
 * @param $rowNumber
 * @param $newRow
 *
 * @return void
 */
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

/** Replaces the values of the csv file by new ones, or adds a new line if the
 * user is not registered in the file
 * @param int $column
 * @param array $values
 *
 * @return void
 */
function push(int $column, array $values): void{

	// adds a "|" element to act as a separator between the headers
	$values[] = "|";

	// looks if the user is registered into the file
	if (isRegistered()) {

		// opens the file
		$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r+');

		// Skip rows to reach the user
		for ($i = 1; $i < user_id_in_csv_file(); $i++) {
			fgetcsv($file);
		}

		// Get the user's row as an array
		$row = fgetcsv($file);

		// Modify the original array if the array is to short
		if(count($row) <= $column + count($values)) {
			for($i = 0; $i < $column + count($values); $i++) {
				$row[] = " ";
			}
		}

		// Modify the original values by adding the new ones
		for($i = $column; $i < count($values) + $column; $i++){
			$row[$i] = $values[$i-$column];
		}

		// deletes the old row from the file and adds the new one
		deleteAndInsertRowInCSV(ABSPATH . 'wp-admin/hceres/data-table.csv', user_id_in_csv_file(), $row);

	} else{
		// adds the new values to the csv file in append mode if the user is not found
		$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'a');
		fputcsv($file, $values);
	}
	fclose($file);
}

/** Searches the number of times the user appears in the csv file
 * @return int
 */
function userTime(): int {
	$count = 0;

	if (($handle = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', "r")) !== false) {
		while (($row = fgetcsv($handle)) !== false) {
			if ($row[0] === ucfirst(wp_get_current_user()->last_name)) {
				$count++;
			}
		}
		fclose($handle);
	}

	return $count;
}

/** Upgrades version of push() to handle multiple line modifications
 * NOTE : took me a while, change it at your own risk
 * @param int $column
 * @param array $values
 *
 * @return void
 */
function replace_or_pushes_values(int $column, array $values): void
{
	// verifies if the last element of an array is equal to a randomly
	//generated RSA key as to secure the field a make it so that a user
	//can't randomly type it
	// This serves to change the behaviour of the function so that it
	//treats the multiline feature (see block5 for better understanding)
	if($values[count($values) - 1] === "SjK8cVSHm6J7PSTgex0zrOmxaNwMZGBiAT5e07FC6tsOBCxHO+NEMWEq3A/RUiASZJ18M10RshYlRFQ/iGwLZw=="){
		$beg = [];

		// deletes the RSA key from the treated values
		array_pop($values);

		// if the form values are only up to 1 year, then just add a single line
		for ($i = 0; $i < 6; $i++){
			$beg[] = $values[$i];
		}

		// pushes the line into the file
		push($column, $beg);

		$file = ABSPATH . 'wp-admin/hceres/data-table.csv';

		// Retrieves the data from the original file
		$rows = [];
		if (($handle = fopen($file, 'r')) !== false) {
			while (($data = fgetcsv($handle)) !== false) {
				$rows[] = $data;
			}
			fclose($handle);
		}

		// deletes the excess of rows if the user deleted them in the form
		if(count($values) === 6){
			for ($i = user_id_in_csv_file(); $i <= userTime() - 2 + user_id_in_csv_file(); $i++){
				unset($rows[$i]);
			}
		}

		// if the number of years is above 1 (6 fields per year)
		if(count($values) > 6){

			// creates an array to add just after the user with the publication information
			$ini = [ucfirst(wp_get_current_user()->last_name), ucfirst(wp_get_current_user()->first_name)];
			for($i = 0; $i < 27; $i++){
				$ini[] = " ";
			}

			// year count
			$count = 0;

			// for each year (every 6 elements in the array)
			for ($i = 6; $i < count($values); $i += 6) {

				// takes one year worth of elements into a separate array
				$subArray = array_slice($values, $i, 6);

				// if the nth year already exists then it replaces it
				if(isset($rows[user_id_in_csv_file() + $count][0]) && $rows[user_id_in_csv_file() + $count][0] === ucfirst(wp_get_current_user()->last_name)){
					$rows[user_id_in_csv_file() + $count] = array_merge($ini, $subArray);

				} else{
					// Insert the new row just after the user
					array_splice($rows, user_id_in_csv_file() + $count, 0, [array_merge($ini, $subArray)]);
				}
				$count++;
			}
		}

		// Write the updated contents back to the file
		if (($handle = fopen($file, 'w')) !== false) {
			foreach ($rows as $row) {
				fputcsv($handle, $row);
			}
			fclose($handle);
		}
	} else {
		push($column, $values);
	}
}

/** Returns the value of a cell, used to show the user their informations back
 * @param int $column
 * @param int $add
 *
 * @return string
 */
function getCell(int $column, int $add = 0): string {
	// opens the file
	$file = fopen( ABSPATH . 'wp-admin/hceres/data-table.csv', 'r' );

	$id = 1;

	// gets the given parameters cell
	while (($row = fgetcsv($file)) !== false) {
		if ($id === user_id_in_csv_file() + $add && $row[0] === ucfirst(wp_get_current_user()->last_name)) {
			if (isset($row[$column])) {
				fclose($file);
				return $row[$column];
			}
		}
		$id++;
	}

	// closes the file
	fclose($file);

	return "";
}

/** References the anwsers of a user
 * @return array returns an array of booleans, true if the user has answered
 * to a specific part, else false
 */
function hasAnswered(): array {

	// opens the file and initializes the returned array
	$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r');
	$answered = [];

	// Skip rows to reach the user
	for ($i = 1; $i < user_id_in_csv_file(); $i++) {
		fgetcsv($file);
	}

	// gets the headers position in the file
	$numbers = getHeadersId();

	// Read the first row and check for elements after each given headers id
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

	// closes the file
	fclose($file);

	return $answered;
}

/** Checks an array of boolean
 * @param $arr
 *
 * @return bool returns true if every boolean of the array are true, else false
 */
function checkArray($arr): bool {

	// checks the value of every elements
	foreach ($arr as $value) {
		if ($value !== true) {
			return false;
		}
	}
	return true;
}

/** Used for statistics, references the % of people that are done with the table
 * @return float returns the % of people that have compleated the data table
 */
function haveAnswered(): float {

	// opens the file and initializes the variable that counts the number of users done with the dt
	$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r');
	$res = 0;

	// gets the number of users in the csv file
	$nbUsers = count(getUsersFromCSV());

	// gets the headers id in the file
	$numbers = getHeadersId();

	// Skip the headers to reach the users
	fgetcsv($file);
	fgetcsv($file);
	fgetcsv($file);

	// searches in the file for every users
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

	// returns 0 if there are no users in the data table
	if($nbUsers === 0){
		return 0;
	}

	// transforms the number of users done into a stat that can be used
	return $res * 100 / $nbUsers;
}

/** Transforms an array into percentage
 * @param $arr
 *
 * @return float|int returns the percentage of users done with the dt
 */
function calculatePercentage($arr): float|int {

	// gets the total number of elements
	$totalElements = count($arr);
	$trueCount = 0;

	// increments a count for every users done
	foreach ($arr as $value) {
		if ($value === true) {
			$trueCount++;
		}
	}

	// returns the percentage if there are elements into the array
	if ($totalElements > 0) {
		return ($trueCount / $totalElements) * 100;
	} else {
		return 0;
	}
}

/** Calculs the percentage of average answered parts of the dt for all users
 * @return float percentage of users's average answers
 */
function averageAnswer(): float{

	// opens the file and initializes the average number to 0
	$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r');
	$avg = 0;

	// gets the headers id
	$numbers = getHeadersId();

	// Skip the headers to reach the users
	fgetcsv($file);
	fgetcsv($file);
	fgetcsv($file);

	// array of already done perople, so as to not go through a user multiple times
	$present = [];

	// searches through the array
	while (($row = fgetcsv($file)) !== false) {
		$answered = [];
		foreach ($numbers as $number) {
			// if the user is already done, skips
			if(in_array($row[0], $present)){
				break;
			}

			// if the value of the headers are not null, then it means the user
			//has answered the current part
			if (isset($row[$number + 1]) && $row[$number + 1] !== " ") {
				$answered[] = true;
			} else {
				$answered[] = false;
			}
		}

		// gets the percentage of answers for every users
		$avg += calculatePercentage($answered);

		// adds the user to the already done ones
		$present[] = $row[0];
	}

	// if there are no users in the file, it returns 0
	if (count(getUsersFromCSV()) === 0){
		return 0;
	}

	// transforms the variables into a final usable percentage
	return $avg / count(getUsersFromCSV());
}

/** Gets an array with every user of the csv file
 * @return array
 */
function getUsersFromCSV(): array {

	// opens the file
	$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r');

	// initializes the array of users and already done users
	$users = [];
	$present = [];

	// Skip the headers row to reach the users
	fgetcsv($file);
	fgetcsv($file);
	fgetcsv($file);

	// searches through the file every users
	while (($row = fgetcsv($file)) !== false) {
		$firstName = $row[1];
		$lastName = $row[0];

		if(!in_array($firstName, $present)){
			// Add the user to the array
			$users[] = $firstName . ' ' . $lastName;
		}

		// adds the user to the already done ones
		$present[] = $firstName;
	}

	// closes the file
	fclose($file);

	return $users;
}

/** Gets every WP users in the database from given roles
 *
 * @param string $roles
 *
 * @return array
 */
function getUsersFromWordPress(string $roles = ''): array {

	// if the role isn't specified, returns every users
	if ($roles === ''){
		$users = get_users();
	} else {
		// returns every users of a given role
		$args = array(
			'role__in' => $roles,
		);
		$users = get_users($args);
	}

	return $users;
}

/** Gets the users from the WP database that are absent from the csv file
 * @param string $roles
 *
 * @return array
 */
function getAbsentUsers(string $roles = ''): array {

	// initializes the array of absent users
	$absent = [];

	// Get all WordPress users
	$wpUsers = getUsersFromWordPress($roles);

	// Get the list of users from the CSV file
	$csvUsers = getUsersFromCSV();

	// goes through every WP users and looks if they are in the csv file
	for ($i = 0; $i < count($wpUsers); $i++){
		if(!in_array(ucfirst($wpUsers[$i]->first_name) . " " . ucfirst($wpUsers[$i]->last_name), $csvUsers)){
			$absent[] = $wpUsers[$i];
		}
	}

	return $absent;
}

add_shortcode('add_istep_annual_table_panel','panel');

/** Returns a panel of statistics and useful informations
 * @return string
 */
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

	$html .= <<<HTML
	<table class="dt__table">
		<thead>
			<tr>
				<th>Nom</th>
				<th>Email</th>
			</tr>
		</thead>
		<tbody>
HTML;

	for ($i = 0; $i < count($absent); $i++) {
		$user = $absent[$i];
		$html .= <<<HTML
			<tr class="dt__absent-user">
				<td class="dt__absent-user-name">$user->display_name</td>
				<td class="dt__absent-user-email"><a href="mailto:$user->user_email">$user->user_email</a></td>
			</tr>
HTML;
	}

	$html .= <<<HTML
			</tbody>
		</table>
		</div>
	</div>
HTML;

	return $html;
}

/** Transforms a string into a string usable as a url
 * @param $str
 *
 * @return string
 */
function transformString($str): string {
	return trim(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $str))), '-');
}

add_shortcode('add_istep_annual_table_summary','summary');

/** Gives the user a summary of the parts of the HCERES data table that they
 * have completed or not, as well as a progress bar based on a system of
 * classes.
 * CAREFUL : this works on harmony with some CSS and JS, modify it at your
 * ow risk.
 * @return string
 */
function summary(): string{
	$lst = ["Informations Generales", "Discipline", "Theme de recherche", "Publications 1", "Publications 2", "Enseignement", "Master 1", "Master 2", "Encadrement these ISTeP", "Encadrement these hors ISTeP", "Encadrement post-doctorats", "Prix ou Distinctions", "Appartenance IUF", "Sejours", "Colloques/Congres", "Societes Savantes", "Responsabilites de projets de recherche", "Responsabilites, Expertises & administration de la recherche", "Responsabilites administratives", "Vulgarisation & dissemination scientifique", "Rayonnement", "Brevet" ];
	$year = date('Y');
	$html = <<<HTML
		<div class="annual_data_table_summary">
			<p> Liste des formulaires HCERES $year</p>
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

/** Gets an array of ints of every id of headers in the csv file
 * @return array
 */
function getHeadersId(): array {
	$file = fopen(ABSPATH . 'wp-admin/hceres/data-table.csv', 'r');

	fgetcsv($file);

	$row = fgetcsv($file);

	$indexes = [];
	$skipCount = 0;
	$skipIndexes = [];
	$skipper = 0;
	$reg = false;
	$loc = false;

	// looks into the file and adds each time an int into the returned array
	//of every time it finds a "|" in the file (because it acts as a separator
	//between the headers)
	foreach ($row as $index => $value) {

		if ($value === '|') {
			$indexes[] = $index + 1;
			if($skipCount !== 0){
				$skipIndexes[] = $index + 1;
				$skipper++;
			}
		}

		if( ($reg === true && $skipper === 3) || ($loc === true && $skipper === 2)){
			$skipCount = 0;
			$skipper  = 0;
			$reg = false;
			$loc = false;
		}

		// handles the specfific headers with multiple part in them
		if ($value === 'Régional et local') {
			$skipCount = 3;
			$reg = true;
		}
		if ($value === 'Locale') {
			$skipCount = 2;
			$loc = true;
		}
	}

	// deletes the last unused id
	array_pop($indexes);

	// closes the file
	fclose($file);

	// skips the rows that are to be skipped
	$indexes = array_diff($indexes, $skipIndexes);

	return array_merge([0], $indexes);
}

/** Gets the number of times the user is in the file (could actually be
 * replaced by the userTimes() function)
 * @param $arr
 * @param $str
 *
 * @return int
 */
function countStringOccurrencesInFirstPosition($arr, $str): int {
	$count = 0;

	foreach ($arr as $innerArray) {
		if (!empty($innerArray) && $innerArray[0] === $str) {
			$count++;
		}
	}

	return $count;
}

/** Deletes the user from the file.
 * The user can delete his information from the file if wanted.
 * Used to respect the RGPD French laws.
 * @return void
 */
function deleteSelf(): void {
	$filePath = ABSPATH . 'wp-admin/hceres/data-table.csv';

	// Read the CSV file into an array
	$rows = [];
	if (($file = fopen($filePath, 'r')) !== false) {
		while (($row = fgetcsv($file)) !== false) {
			$rows[] = $row;
		}
		fclose($file);
	}

	// deletes the datas of the user
	for ($i = user_id_in_csv_file() - 1; $i <= countStringOccurrencesInFirstPosition($rows, ucfirst(wp_get_current_user()->last_name)) + user_id_in_csv_file() - 1; $i++){
		unset($rows[$i]);
	}
	unset($rows[user_id_in_csv_file()-1]);

	// Recreate the CSV file with the updated data
	if (($file = fopen($filePath, 'w')) !== false) {
		foreach ($rows as $row) {
			fputcsv($file, $row);
		}
		fclose($file);
	}
}

add_shortcode('add_istep_annual_table_delete','deleteUser');

/** Shows a delete button using the deleteSelf feature
 * @return string
 */
function deleteUser(): string{
	if (isRegistered()){
		if(isset($_POST["dt__delete"])){
			deleteSelf();
			return <<<HTML
			<p class="dt__data__erased">Données supprimées.</p>
			<p class="back_to_home">Revenir à l'<a href="/">accueil</a>.</p>
HTML;
		}

		return <<<HTML
	<form class="data-table-delete" method="post">
		<button class="dt__delete" name="dt__delete" onclick="return confirmDelete()" type="submit">Supprimer mes données HCERES</button>
	</form>
HTML;
	}else{
		return "";
	}
}

/** Gets and sanitizes the data from a form depending on the data's type
 * @param $form_data
 *
 * @return array
 */
function sanitize_form_values($form_data): array {

	// initializes the sanitized elements array
	$sanitized_data = [];

	// sanitizes every elements of the form
	foreach ($form_data as $value) {
		if (isset($value)) {
			if (is_string($value)) {
				if (is_date_field($value)) {
					$sanitized_data[] = format_date_field($value);
				} else {
					$sanitized_data[] = sanitize_text_field($value);
				}
			} else {
				$sanitized_data[] = $value;
			}
		} else {
			$sanitized_data[] = "";
		}
	}

	// deletes the submit button from the array
	array_pop($sanitized_data);

	return $sanitized_data;
}

/** Verifies if the given data is a date but as a string
 * @param $value
 *
 * @return bool
 */
function is_date_field($value): bool {
	$date_format = "Y-m-d";
	$parsed_date = date_parse_from_format($date_format, $value);
	return $parsed_date['error_count'] === 0 && $parsed_date['warning_count'] === 0;
}

/** Transforms the string date into an actual Date
 * @param $value
 *
 * @return string
 */
function format_date_field($value): string {
	return date("m/Y", strtotime($value));
}

add_shortcode('add_istep_annual_table_form_block_1','block1');
function block1(): string{
	$name = ucfirst(wp_get_current_user()->first_name);
	$last_name = ucfirst(wp_get_current_user()->last_name);

	if(isset($_POST["submit1"])){
		$data = array_merge([$last_name, $name],sanitize_form_values($_POST));

		if(!isRegistered()){
			// searches the number of fields in the csv file and stocks it
			$file = fopen( ABSPATH . 'wp-admin/hceres/data-table.csv', 'r' );
			fgetcsv($file);
			$maxFields = count(fgetcsv($file));
			fclose($file);

			for($i = count($_POST) + 1; $i < $maxFields - 1; $i++){
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

	$rang = getCell(8);
	$year = date('Y');

	$annee_naissance = null;
	$annee_these = null;
	$annee_hdr = null;
	$annee_etat = null;
	$date_entree = null;
	$date_sortie = "";

	$poles = [
		"Secrétariat",
		"Direction",
		"Gestion administrative",
		"Gestion financière",
		"Informatique",
		"Cartographie, SIG",
		"Communication",
		"Plateformes d’analyses",
		"Assistant de prévention"
	];

	$selectedPole = getCell(5);

	$fonctions = [
		"Enseignant-Chercheur",
		"Direction",
		"Gestion administrative",
		"Gestion financière",
		"Gestion administrative",
		"Direction UMR",
		"Direction adjointe UMR",
		"Secrétaire général",
		"Responsable gestion administrative",
		"Responsable gestion financière",
		"ATER",
		"Doctorant",
		"Cartographe",
		"Sciences de l’information géographique",
		"Traitement des données",
		"Gestion base de données",
		"Science et caractérisation de matériaux",
		"Litholamellage",
		"Gestion d’application / assistant support",
		"Analyse chimique",
		"Développement d’expérimentation",
		"Calcul Scientifique",
		"Communication",
		"Bénévole",
		"CRCT",
		"Emérite",
		"Détachement"
	];

	$selectedFonction = getCell(6);

	$corps = [
		"AI",
		"AJT",
		"ATER",
		"Bénévole",
		"CR",
		"DR",
		"DREM",
		"IE",
		"IR",
		"MCF",
		"MCFEM",
		"PAST",
		"Post-doc",
		"PR",
		"PREM",
		"TCH"
	];

	$selectedCoprs = getCell(7);

	if(isRegistered()){
		$annee_naissance = (int)getCell(11);
		$annee_these = (int)getCell(12);
		$annee_hdr = (int)getCell(13);
		$annee_etat = (int)getCell(14);
		$date_entree = date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(9))));
		if(getCell(10) !== ""){
			$date_sortie = 'value="' . date('Y-m-d', strtotime('01-' . str_replace('/', '-', getCell(10)))) . '"';
		}
	}

	$html =  <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Informations générales</h5>
		<label for="last_name">Nom</label>
			<input type="text" id="last_name" name="last_name" value="$last_name" class="ninja-forms-field nf-element" required disabled>
		<label for="name">Prénom</label>
			<input type="text" id="name" name="name" value="$name" required disabled>
		</br>
		<label for="equipe1">Equipe 2017-2022</label>
		<select id="equipe1" name="equipe1" required>
			<option value=" "></option>
    		<option value="DEMO" $demo>DEMO</option>
    		<option value="PGM2" $pgm2>PGM2</option>
    		<option value="PPB" $ppb>PPB</option>
		</select>
		<label for="equipe2">Equipe 2022-2025</label>
		<select id="equipe2" name="equipe2" required>
			<option value=" "></option>
			<option value="PETRODYN" $petrodyn>PETRODYN</option>
			<option value="TECTO" $tecto>TECTO</option>
			<option value="TERMER" $termer>TERMER</option>
		</select>
		<label for="equipe3">Equipe 2025-…</label>
		<select name="equipe3" id="equipe3">
			<option value=" "></option>
			<option value="PETRODYN" $petrodyn2>PETRODYN</option>
			<option value="TECTO" $tecto2>TECTO</option>
			<option value="TERMER" $termer2>TERMER</option>
			<option value="PRISME" $prisme>PRISME</option>
		</select>
		<label for="poles">Pôles des services généraux (le cas échéant)</label>
		<select id="poles" name="poles" required>
			<option value=" "></option>
HTML;

	$selectedP = "";
	for ($i = 0; $i < count($poles); $i++){
		if($poles[$i] === $selectedPole){
			$selectedP = "selected";
		}
		$html .= <<<HTML
			<option value="$poles[$i]" $selectedP>$poles[$i]</option>
HTML;
		$selectedP = "";
	}

	$html .= <<<HTML
		</select>
		<label for="fonction">Fonction exercée</label>
		<select id="fonction" name="fonction" required>
			<option value=" "></option>
HTML;

	$selectedF = "";
	for ($i = 0; $i < count($fonctions); $i++){
		if($fonctions[$i] === $selectedFonction){
			$selectedF = "selected";
		}
		$html .= <<<HTML
			<option value="$fonctions[$i]" $selectedF>$fonctions[$i]</option>
HTML;
		$selectedF = "";
	}

	$html .= <<<HTML
		</select>
		<label for="corps">Corps</label>
		<select id="corps" name="corps" required>
			<option value=" "></option>
HTML;

	$selectedC = "";
	for ($i = 0; $i < count($corps); $i++){
		if($corps[$i] === $selectedCoprs){
			$selectedC = "selected";
		}
		$html .= <<<HTML
			<option value="$corps[$i]" $selectedC>$corps[$i]</option>
HTML;
		$selectedC = "";
	}

	$html .= <<<HTML
		</select>
		<label for="rang">Rang</label>
			<input type="text" id="rang" name="rang" value="$rang" required>
		<label for="date_entree">Date entrée (MM/AAAA)</label>
			<input type="date" id="date_entree" name="date_entree" value="$date_entree" required>
		<label for="date_sortie">Date sortie (MM/AAAA)</label>
			<input type="date" id="date_sortie" name="date_sortie" $date_sortie>
		<label for="annee_naissance">année naissance</label>
			<input type="number" min="1900" max="$year" name="annee_naissance" id="annee_naissance" value="$annee_naissance" required>
		<label for="annee_obtention_these">année obtention thèse</label>
			<input type="number" min="1900" max="$year" name="annee_obtention_these" id="annee_obtention_these" value="$annee_these">
		<label for="annee_obtention_hdr">année obtention HDR</label>
			<input type="number" min="1900" max="$year" name="annee_obtention_hdr" id="annee_obtention_hdr" value="$annee_hdr">
		<label for="annee_obtention_these_etat">année obtention Thèse d'état</label>
			<input type="number" min="1900" max="$year" name="annee_obtention_these_etat" id="annee_obtention_these_etat" value="$annee_etat">
		<button type="submit" name="submit1">Envoyer</button>
	</form>
HTML;

		return $html;
}

add_shortcode('add_istep_annual_table_form_block_2','block2');
function block2(): string{

	if(isRegistered()){
		if(isset($_POST["submit2"])) {
			$data = [
				sanitize_text_field($_POST["discipline1"]),
				sanitize_text_field($_POST["discipline2"])
			];
			replace_or_pushes_values(16, $data);
		}

		$disciplines = [
			"Géochimie",
			"Volcanologie",
			"Pétrologie",
			"Minéralogie",
			"Sismologie",
			"Gravimétrie-Géodésie",
			"Magnétisme",
			"Géologie",
			"Tectonique",
			"Géophysique",
			"Sédimentologie",
			"Paléontologie",
			"Paléoenvironnement",
			"Pédologie",
			"Géomorphologie",
			"Stratigraphie",
			"Géodynamique",
			"Hydrologie-hydrogéologie",
			"Pétrophysique"
		];

		$html = <<<HTML
		<form method="POST" class="data-table-form">
		<h5>Discipline</h5>
		<label for="discipline1">Discipline 1</label>
		<select name="discipline1" id="discipline1" required>
			<option value=" "></option>
HTML;


		$selectedDisc1 = getCell(16);
		$selectedDisc2 = getCell(17);

		$selectedD = "";
		for ($i = 0; $i < count($disciplines); $i++){
			if($disciplines[$i] === $selectedDisc1){
				$selectedD = "selected";
			}
			$html .= <<<HTML
			<option value="$disciplines[$i]" $selectedD>$disciplines[$i]</option>
HTML;
			$selectedD = "";
		}

		$html .= <<<HTML
		</select>
		<label for="discipline2">Discipline 2</label>
		<select name="discipline2" id="discipline2" required>
			<option value=" "></option>
HTML;

		for ($i = 0; $i < count($disciplines); $i++){
			if($disciplines[$i] === $selectedDisc2){
				$selectedD = "selected";
			}
			$html .= <<<HTML
			<option value="$disciplines[$i]" $selectedD>$disciplines[$i]</option>
HTML;
			$selectedD = "";
		}

		$html .= <<<HTML
		</select>
		<button type="submit" name="submit2">Envoyer</button>
		</form>
HTML;
		return $html;
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
					sanitize_text_field($_POST["theme_recherche"])
				];
				replace_or_pushes_values(19, $data);
			}

	$theme = "";

	if(getCell(19) !== " "){
		$theme = getCell(19);
	}

	return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Thèmes de recherche (80 mots max) <label for="theme_recherche"></label></h5>
			<input type="text" id="theme_recherche" name="theme_recherche" value="$theme" id="theme_recherche" oninput="limitWords()" required>
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
				sanitize_text_field($_POST["h_factor_isi"]),
				$_POST["nb_citations_isi_google"],
				sanitize_text_field($_POST["h_factor_google"]),
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
			<input type="number" id="nb_publi_rang_a1" name="nb_publi_rang_a1" min="0" value="$nb_publi_rang_a1" required>
		<label for="nb_publi_rang_premier">Nombre total de publi de rang A en 1ier auteur ou derrière un doctorant</label>
			<input type="number" id="nb_publi_rang_premier" name="nb_publi_rang_premier" min="0" value="$nb_publi_rang_premier" required>
		<label for="nb_citations_isi">nombre de citations (isi-web of science)</label>
			<input type="number" id="nb_citations_isi" name="nb_citations_isi" min="0" value="$nb_citations_isi" required>
		<label for="h_factor_isi">h-factor (Isi-Web)</label>
			<input type="text" id="h_factor_isi" name="h_factor_isi" value="$h_factor_isi" required>
		<label for="nb_citations_isi_google">nombre de citations (google scholar)</label>
			<input type="number" id="nb_citations_isi_google" name="nb_citations_isi_google" min="0" value="$nb_citations_isi_google" required>
		<label for="h_factor_google">h-factor (google scholar)</label>
			<input type="text" id="h_factor_google" name="h_factor_google" value="$h_factor_google" required>
		<label for="nb_resume_conference">Nbre de résumé à conférence avec comité de lecture</label>
			<input type="number" id="nb_resume_conference" name="nb_resume_conference" min="0" value="$nb_resume_conference" required>
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
		$html = <<<HTML
	<form method="POST" class="data-table-form" id="hceres__var__form">
		<h5>Détail des publications par année depuis 2022</h5>
		<div class="dt__wt__buttons">
HTML;

		if(isset($_POST["submit5"])){
			$data = [];
			foreach ($_POST as $value) {
				if (isset($value)) {
					$data[] = $value;
				}
			}
			array_pop($data);
			$data[] = "SjK8cVSHm6J7PSTgex0zrOmxaNwMZGBiAT5e07FC6tsOBCxHO+NEMWEq3A/RUiASZJ18M10RshYlRFQ/iGwLZw==";
			replace_or_pushes_values(29, $data);
		}

		$arr = ["", " "];

		for ($i = 0; $i <= date('Y') - 2022; $i++){

			if(!in_array(getCell(29, $i), $arr)){
				$nb_publi_rang_a2 = (int)getCell(29, $i);
			} else {
				$nb_publi_rang_a2 = null;
			}

			if(!in_array(getCell(30, $i), $arr)){
				$nb_publi_premier = (int)getCell(30, $i);
			} else {
				$nb_publi_premier = null;
			}

			if(!in_array(getCell(31, $i), $arr)){
				$nb_article_doctorant = (int)getCell(31, $i);
			} else {
				$nb_article_doctorant = null;
			}

			if(!in_array(getCell(32, $i), $arr)){
				$nb_article_rang_a_collab = (int)getCell(32, $i);
			} else {
				$nb_article_rang_a_collab = null;
			}

			if(!in_array(getCell(33, $i), $arr)){
				$chapitre_ouvrage = getCell(33, $i);
			} else {
				$chapitre_ouvrage = null;
			}

			if(!in_array(getCell(34, $i), $arr)){
				$nb_resume_comite_lecture = (int)getCell(34, $i);
			} else {
				$nb_resume_comite_lecture = null;
			}

			$year = 2022 + $i;
			$html .= <<<HTML
		<div class="dt__year__$i dt__year">
			<h2>$year</h2>
			<label for="nb_publi_rang_a2__$i">Nombre total de publications de rang A</label>
				<input type="number" id="nb_publi_rang_a2__$i" name="nb_publi_rang_a2__$i" min="0" value="$nb_publi_rang_a2" required>
			<label for="nb_publi_premier__$i">Nombre d'articles en 1er auteur</label>
				<input type="number" id="nb_publi_premier__$i"name="nb_publi_premier__$i" min="0" value="$nb_publi_premier" required>
			<label for="nb_article_doctorant__$i">Nombre article derrière un doctorant</label>
				<input type="number" id="nb_article_doctorant__$i"name="nb_article_doctorant__$i" min="0" value="$nb_article_doctorant" required>
			<label for="nb_article_rang_a_collab__$i">Nombre d'articles rang A avec des collab. (autres laboratoires)</label>
				<input type="number" id="nb_article_rang_a_collab__$i" name="nb_article_rang_a_collab__$i" min="0" value="$nb_article_rang_a_collab" required>
			<label for="chapitre_ouvrage__$i">Chapitre d'ouvrage / livre</label>
				<input type="text" id="chapitre_ouvrage__$i" name="chapitre_ouvrage__$i" value="$chapitre_ouvrage" required>
			<label for="nb_resume_comite_lecture__$i">Nombre de résumé à des congrès avec comité de lecture</label>
				<input type="number" id="nb_resume_comite_lecture__$i" name="nb_resume_comite_lecture__$i" min="0" value="$nb_resume_comite_lecture" required>
		</div>
HTML;
		}

		$html .= <<<HTML
		</div>
		<button id="showBtn" type="button">+</button>
		<button id="hideBtn" type="button">-</button>		
		<button type="submit" name="submit5">Envoyer</button>
	</form>
HTML;
		return $html;
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
			<input type="number" id="enseignement1" name="enseignement1" min="0" value="$enseignement1" required>
		<label for="enseignement2">nb heures enseignées 2023-2024</label>
			<input type="number" id="enseignement2" name="enseignement2" min="0" value="$enseignement2" required>
		<label for="enseignement3">nb heures enseignées 2024-2025</label>
			<input type="number" id="enseignement3" name="enseignement3" min="0" value="$enseignement3" required>
		<label for="enseignement4">nb heures enseignées 2025-2026</label>
			<input type="number" id="enseignement4" name="enseignement4" min="0" value="$enseignement4" required>
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
				sanitize_text_field($_POST["master1_nom"]),
				sanitize_text_field($_POST["master1_prenom"]),
				$_POST["master1_annee"],
				sanitize_text_field($_POST["master1_nom_prenom_co-encadrants"]),
				sanitize_text_field($_POST["master1_sujet"])
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
			<input type="text" id="master1_nom" name="master1_nom" value="$master1_nom" required>
		<label for="master1_prenom">Prénom</label>
			<input type="text" id="master1_prenom" name="master1_prenom" value="$master1_prenom" required>
		<label for="master1_annee">Année</label>
			<input type="number" min="2022" max="$year" name="master1_annee" id="master1_annee" value="$master1_annee" required>
		<label for="master1_nom_prenom_co-encadrants">NOM Prénom des Co-encadrants</label>
			<input type="text" id="master1_nom_prenom_co-encadrants" name="master1_nom_prenom_co-encadrants" value="$master1_nom_prenom_co_encadrants" required>
		<label for="master1_sujet">Titre sujet (indiquer si hors ISTeP)</label>
			<input type="text" id="master1_sujet" name="master1_sujet" value="$master1_sujet" required>
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
				sanitize_text_field($_POST["master2_nom"]),
				sanitize_text_field($_POST["master2_prenom"]),
				$_POST["master2_annee"],
				sanitize_text_field($_POST["master2_nom_prenom_co-encadrants"]),
				sanitize_text_field($_POST["master2_sujet"])
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
			<input type="text" id="master2_nom" name="master2_nom" value="$master2_nom" required>
		<label for="master2_prenom">Prénom</label>
			<input type="text" id="master2_prenom" name="master2_prenom" value="$master2_prenom" required>
		<label for="master2_annee">Année</label>
			<input type="number" min="2022" max="$year" name="master2_annee" id="master2_annee" value="$master2_annee" required>
		<label for="master2_nom_prenom_co-encadrants">NOM Prénom des Co-encadrants</label>
			<input type="text" name="master2_nom_prenom_co-encadrants" id="master2_nom_prenom_co-encadrants" value="$master2_nom_prenom_co_encadrants" required>
		<label for="master2_sujet">Titre sujet (indiquer si hors ISTeP)</label>
			<input type="text" id="master2_sujet" name="master2_sujet" value="$master2_sujet" required>
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
				sanitize_text_field($_POST["encadrement_istep_nom"]),
				sanitize_text_field($_POST["encadrement_istep_prenom"]),
				$_POST["encadrement_istep_hf"],
				date("m/Y", strtotime($_POST["encadrement_istep_date_inscription_these"])),
				date("m/Y", strtotime($_POST["encadrement_istep_date_soutenance"])),
				sanitize_text_field($_POST["encadrement_istep_nom_prenom_co-directerurs"]),
				sanitize_text_field($_POST["encadrement_istep_titre_these"]),
				sanitize_text_field($_POST["encadrement_istep_etablissement"]),
				sanitize_text_field($_POST["encadrement_istep_numero_ed"]),
				sanitize_text_field($_POST["encadrement_istep_financement_doctorat"]),
				sanitize_text_field($_POST["encadrement_istep_fonction"])
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

		$html = <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Encadrement thèse ISTeP à partir de 2022</h5>
		<label for="encadrement_istep_nom">Nom</label>
			<input type="text" id="encadrement_istep_nom" name="encadrement_istep_nom" value="$encadrement_istep_nom" required>
		<label for="encadrement_istep_prenom">Prénom</label>
			<input type="text" id="encadrement_istep_prenom" name="encadrement_istep_prenom" value="$encadrement_istep_prenom" required>
		<label for="encadrement_istep_hf">H/F</label>
			<select id="encadrement_istep_hf" name="encadrement_istep_hf" required>
				<option value=" "></option>
				<option value="Homme" $homme>Homme</option>
				<option value="Femme" $femme>Femme</option>
			</select>
		<label for="encadrement_istep_date_inscription_these">Date d'inscription en thèse (MM/AAAA)</label>
			<input type="date" name="encadrement_istep_date_inscription_these" id="encadrement_istep_date_inscription_these" value="$encadrement_istep_date_inscription_these" required>
		<label for="encadrement_istep_date_soutenance">Date de soutenance (MM/AAAA)</label>
			<input type="date" name="encadrement_istep_date_soutenance" id="encadrement_istep_date_soutenance" value="$encadrement_istep_date_soutenance" required>
		<label for="encadrement_istep_nom_prenom_co-directerurs">NOM Prénom des Co-directeurs</label>
			<input type="text" name="encadrement_istep_nom_prenom_co-directerurs" id="encadrement_istep_nom_prenom_co-directerurs" value="$encadrement_istep_nom_prenom_co" required>
		<label for="encadrement_istep_titre_these">Titre thèse</label>
			<input type="text" name="encadrement_istep_titre_these" id="encadrement_istep_titre_these" value="$encadrement_istep_titre_these" required>
		<label for="encadrement_istep_etablissement">Établissement ayant délivré le master (ou diplôme équivalent)</label>
			<input type="text" name="encadrement_istep_etablissement" id="encadrement_istep_etablissement" value="$encadrement_istep_etablissement" required>
		<label for="encadrement_istep_numero_ed">Numéro de l'ED de rattachement</label>
			<input type="text" name="encadrement_istep_numero_ed" id="encadrement_istep_numero_ed" value="$encadrement_istep_numero_ed" required>
		<label for="encadrement_istep_financement_doctorat">Financement du doctorat</label>
		<select name="encadrement_istep_financement_doctorat" id="encadrement_istep_financement_doctorat" required>
			<option value=" "></option>
HTML;

		$financement = [
			"Contrat Doctoral",
			"Collectivités Territoriales",
			"Agence Française de Financement Recherche",
			"CIFRE",
			"Financements Privés France",
			"Comission Européenne",
			"Financements Etrangers",
			"Formation Continue",
			"Organismes Internationaux",
			"Autres"
		];

		$selectedFinancement = getCell(62);
		$selectedDirection = getCell(63);

		$selectedF = "";
		for ($i = 0; $i < count($financement); $i++){
			if($financement[$i] === $selectedFinancement){
				$selectedF = "selected";
			}
			$html .= <<<HTML
			<option value="$financement[$i]" $selectedF>$financement[$i]</option>
HTML;
			$selectedF = "";
		}

		$html .= <<<HTML
		</select>
		<label for="encadrement_istep_fonction">Fonction de direction ou encadrement ?</label>
		<select id="encadrement_istep_fonction" name="encadrement_istep_fonction" required>
			<option value=" "></option>
HTML;

		$direction = [
			"Direction",
			"Co-direction",
			"Encadrant",
			"Co-encadrant"
		];

		$selectedD = "";
		for ($i = 0; $i < count($direction); $i++){
			if($direction[$i] === $selectedDirection){
				$selectedD = "selected";
			}
			$html .= <<<HTML
			<option value="$direction[$i]" $selectedD>$direction[$i]</option>
HTML;
			$selectedD = "";
		}

		$html .= <<<HTML
		</select>
		<button type="submit" name="submit9">Envoyer</button>
	</form>
HTML;
		return $html;
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
				sanitize_text_field($_POST["encadrement_histep_nom"]),
				sanitize_text_field($_POST["encadrement_histep_prenom"]),
				$_POST["encadrement_histep_hf"],
				date("m/Y", strtotime($_POST["encadrement_histep_date_inscription_these"])),
				date("m/Y", strtotime($_POST["encadrement_histep_date_soutenance"])),
				sanitize_text_field($_POST["encadrement_histep_direction_these"]),
				sanitize_text_field($_POST["encadrement_histep_titre_these"]),
				sanitize_text_field($_POST["encadrement_histep_etablissement"]),
				sanitize_text_field($_POST["encadrement_histep_numero_ed"]),
				sanitize_text_field($_POST["encadrement_histep_etablissement_rattachement_direction_these"]),
				sanitize_text_field($_POST["encadrement_histep_financement_doctorat"]),
				sanitize_text_field($_POST["encadrement_histep_fonction"])
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

		$html = <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Encadrement thèse hors ISTeP à partir de 2022</h5>
		<label for="encadrement_histep_nom">Nom</label>
			<input type="text" id="encadrement_histep_nom" name="encadrement_histep_nom" value="$encadrement_histep_nom" required>
		<label for="encadrement_histep_prenom">Prénom</label>
			<input type="text" name="encadrement_histep_prenom" id="encadrement_histep_prenom" value="$encadrement_histep_prenom" required>
		<label for="encadrement_histep_hf">H/F</label>
			<select id="encadrement_histep_hf" name="encadrement_histep_hf" required>
				<option value=" "></option>
				<option value="Homme" $homme>Homme</option>
				<option value="Femme" $femme>Femme</option>
			</select>
		<label for="encadrement_histep_date_inscription_these">Date d'inscription en thèse (MM/AAAA)</label>
			<input type="date" name="encadrement_histep_date_inscription_these" id="encadrement_histep_date_inscription_these" value="$encadrement_histep_date_inscription_these" required>
		<label for="encadrement_histep_date_soutenance">Date de soutenance (MM/AAAA)</label>
			<input type="date" name="encadrement_histep_date_soutenance" id="encadrement_histep_date_soutenance" value="$encadrement_histep_date_soutenance" required>
		<label for="encadrement_histep_direction_these">Direction de thèse (Nom, Prénom)</label>
			<input type="text" name="encadrement_histep_direction_these" id="encadrement_histep_direction_these" value="$encadrement_histep_direction_these" required>
		<label for="encadrement_histep_titre_these">Titre thèse</label>
			<input type="text" name="encadrement_histep_titre_these" id="encadrement_histep_titre_these" value="$encadrement_histep_titre_these" required>
		<label for="encadrement_histep_etablissement">Établissement ayant délivré le master (ou diplôme équivalent)</label>
			<input type="text" name="encadrement_histep_etablissement" id="encadrement_histep_etablissement" value="$encadrement_histep_etablissement" required>
		<label for="encadrement_histep_numero_ed">Numéro de l'ED de rattachement</label>
			<input type="text" name="encadrement_histep_numero_ed" id="encadrement_histep_numero_ed" value="$encadrement_histep_numero_ed" required>
		<label for="encadrement_histep_etablissement_rattachement_direction_these">Etablissement de rattachement de la direction de thèse</label>
			<input type="text" name="encadrement_histep_etablissement_rattachement_direction_these" id="encadrement_histep_etablissement_rattachement_direction_these" value="$encadrement_histep_etablissement_rattachement_direction_these" required>
		<label for="encadrement_histep_financement_doctorat">Financement du doctorat</label>
		<select name="encadrement_histep_financement_doctorat" id="encadrement_histep_financement_doctorat" required>
			<option value=" "></option>
HTML;

		$financement = [
			"Contrat Doctoral",
			"Collectivités Territoriales",
			"Agence Française de Financement Recherche",
			"CIFRE",
			"Financements Privés France",
			"Comission Européenne",
			"Financements Etrangers",
			"Formation Continue",
			"Organismes Internationaux",
			"Autres"
		];

		$selectedFinancement = getCell(75);
		$selectedDirection = getCell(76);

		$selectedF = "";
		for ($i = 0; $i < count($financement); $i++){
			if($financement[$i] === $selectedFinancement){
				$selectedF = "selected";
			}
			$html .= <<<HTML
			<option value="$financement[$i]" $selectedF>$financement[$i]</option>
HTML;
			$selectedF = "";
		}

		$html .= <<<HTML
		</select>
		<label for="encadrement_histep_fonction">Fonction de direction ou encadrement ?</label>
		<select name="encadrement_histep_fonction" id="encadrement_histep_fonction" required>
			<option value=" "></option>
HTML;

		$direction = [
			"Direction",
			"Co-direction",
			"Encadrant",
			"Co-encadrant"
		];

		$selectedD = "";
		for ($i = 0; $i < count($direction); $i++){
			if($direction[$i] === $selectedDirection){
				$selectedD = "selected";
			}
			$html .= <<<HTML
			<option value="$direction[$i]" $selectedD>$direction[$i]</option>
HTML;
			$selectedD = "";
		}

		$html .= <<<HTML
		</select>
		<button type="submit" name="submit10">Envoyer</button>
	</form>
HTML;
		return $html;
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
				sanitize_text_field($_POST["encadrement_pd_nom"]),
				sanitize_text_field($_POST["encadrement_pd_prenom"]),
				$_POST["encadrement_pd_hf"],
				date("m/Y", strtotime($_POST["encadrement_pd_date_entree"])),
				date("m/Y", strtotime($_POST["encadrement_pd_date_sortie"])),
				$_POST["encadrement_pd_annee_naissance"],
				sanitize_text_field($_POST["encadrement_pd_employeur"])
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
			<input type="text" name="encadrement_pd_nom" id="encadrement_pd_nom" value="$encadrement_pd_nom" required>
		<label for="encadrement_pd_prenom">Prénom</label>
			<input type="text" name="encadrement_pd_prenom" id="encadrement_pd_prenom" value="$encadrement_pd_prenom" required>
		<label for="encadrement_pd_hf">H/F</label>
			<select id="encadrement_pd_hf" name="encadrement_pd_hf" required>
				<option value=""></option>
				<option value="Homme" $homme>Homme</option>
				<option value="Femme" $femme>Femme</option>
			</select>
		<label for="encadrement_pd_date_entree">Date d'entrée (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_entree" id="encadrement_pd_date_entree" value="$encadrement_pd_date_entree" required>
		<label for="encadrement_pd_date_sortie">Date de sortie (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_sortie" id="encadrement_pd_date_sortie" value="$encadrement_pd_date_sortie" required>
		<label for="encadrement_pd_annee_naissance">Année de naissance</label>
			<input type="number" name="encadrement_pd_annee_naissance" min="1900" max="$year" id="encadrement_pd_annee_naissance" value="$encadrement_pd_annee_naissance" required>
		<label for="encadrement_pd_employeur">Etablissement ou organisme employeur</label>
			<input type="text" name="encadrement_pd_employeur" id="encadrement_pd_employeur" value="$encadrement_pd_employeur" required>
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
				sanitize_text_field($_POST["distinction_intitule"]),
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
			<input type="text" name="distinction_intitule" id="distinction_intitule" value="$distinction_intitule" required>
		<label for="distinction_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="distinction_annee" id="distinction_annee" value="$distinction_annee" required>
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
				sanitize_text_field($_POST["iuf_intitule"]),
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
			<input type="text" name="iuf_intitule" id="iuf_intitule" value="$iuf_intitule" required>
		<label for="iuf_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="iuf_annee" id="iuf_annee" value="$iuf_annee" required>
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
				sanitize_text_field($_POST["sejour_lieu"]),
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
			<input type="text" name="sejour_lieu" id="sejour_lieu" value="$sejour_lieu" required>
		<label for="sejour_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="sejour_annee" id="sejour_annee" value="$sejour_annee" required>
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
				sanitize_text_field($_POST["organisation_nom"]),
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
			<input type="text" name="organisation_nom" id="organisation_nom" value="$organisation_nom" required>
		<label for="organisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="organisation_annee" id="organisation_annee" value="$organisation_annee" required>
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
				sanitize_text_field($_POST["societe_savantes_nom"]),
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
			<input type="text" name="societe_savantes_nom" id="societe_savantes_nom" value="$societe_savantes_nom" required>
		<label for="societe_savantes_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="societe_savantes_annee" id="societe_savantes_annee" value="$societe_savantes_annee" required>
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
				sanitize_text_field($_POST["responsabilite1_region_nom"]),
				"|",
				$_POST["responsabilite1_national_montant"],
				sanitize_text_field($_POST["responsabilite1_national_nom"]),
				"|",
				$_POST["responsabilite1_international_montant"],
				sanitize_text_field($_POST["responsabilite1_international_nom"]),
				"|",
				$_POST["responsabilite1_partenariat_montant"],
				sanitize_text_field($_POST["responsabilite1_partenariat_nom"])
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

		if(getCell(104) !== " "){
			$responsabilite1_national_montant = getCell(104);
		}

		if(getCell(105) !== " "){
			$responsabilite1_national_nom = getCell(105);
		}

		if(getCell(107) !== " "){
			$responsabilite1_international_montant = getCell(107);
		}

		if(getCell(108) !== " "){
			$responsabilite1_international_nom = getCell(108);
		}

		if(getCell(110) !== " "){
			$responsabilite1_partenariat_montant = getCell(110);
		}

		if(getCell(111) !== " "){
			$responsabilite1_partenariat_nom = getCell(111);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h4>Responsabilité de projets de recherche (ou tasks indépendantes)</h4>
		
		<h5>Régional et local</h5>
		<label for="responsabilite1_region_montant">montant (k€)</label>
			<input type="number" min="0" name="responsabilite1_region_montant" id="responsabilite1_region_montant" value="$responsabilite1_region_montant" required>
		<label for="responsabilite1_region_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_region_nom"  id="responsabilite1_region_nom" value="$responsabilite1_region_nom" required>
		
		<h5>National</h5>
		<label for="responsabilite1_national_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_national_montant"  min="0" id="responsabilite1_national_montant" value="$responsabilite1_national_montant" required>
		<label for="responsabilite1_national_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_national_nom"  id="responsabilite1_national_nom" value="$responsabilite1_national_nom" required>
		
		<h5>International</h5>
		<label for="responsabilite1_international_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_international_montant" min="0" id="responsabilite1_international_montant" value="$responsabilite1_international_montant" required>
		<label for="responsabilite1_international_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_international_nom"  id="responsabilite1_international_nom" value="$responsabilite1_international_nom" required>
		
		<h5>Partenariat (industrie, EPIC)</h5>
		<label for="responsabilite1_partenariat_montant">montant (k€)</label>
			<input type="number" name="responsabilite1_partenariat_montant"  min="0" id="responsabilite1_partenariat_montant" value="$responsabilite1_partenariat_montant" required>
		<label for="responsabilite1_partenariat_nom">Nom projet (titre et acronyme) Fonction (PI, co-PI, partenaire, participant)</label>
			<input type="text" name="responsabilite1_partenariat_nom"  id="responsabilite1_partenariat_nom" value="$responsabilite1_partenariat_nom" required>
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
				sanitize_text_field($_POST["responsabilite2_locale_intitule"]),
				$_POST["responsabilite2_locale_annee"],
				"|",
				sanitize_text_field($_POST["responsabilite2_regional_intitule"]),
				$_POST["responsabilite2_regional_annee"],
				"|",
				sanitize_text_field($_POST["responsabilite2_international_intitule"]),
				$_POST["responsabilite2_international_annee"]
			];
			replace_or_pushes_values(113, $data);
		}

		$responsabilite2_locale_intitule = null;
		$responsabilite2_locale_annee = null;
		$responsabilite2_regional_intitule = null;
		$responsabilite2_regional_annee = null;
		$responsabilite2_international_intitule = null;
		$responsabilite2_international_annee = null;

		if(getCell(113) !== " "){
			$responsabilite2_locale_intitule = getCell(113);
		}

		if(getCell(114) !== " "){
			$responsabilite2_locale_annee = getCell(114);
		}

		if(getCell(116) !== " "){
			$responsabilite2_regional_intitule = getCell(116);
		}

		if(getCell(117) !== " "){
			$responsabilite2_regional_annee = getCell(117);
		}

		if(getCell(119) !== " "){
			$responsabilite2_international_intitule = getCell(119);
		}

		if(getCell(120) !== " "){
			$responsabilite2_international_annee = getCell(120);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h4>Responsabilités, Expertises & administration de la recherche</h4>
		
		<h5>Locale</h5>
		<label for="responsabilite2_locale_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_locale_intitule" id="responsabilite2_locale_intitule" value="$responsabilite2_locale_intitule" required>
		<label for="responsabilite2_locale_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_locale_annee" min="1900" id="responsabilite2_locale_annee" value="$responsabilite2_locale_annee" required>
		
		<h5>Régional</h5>
		<label for="responsabilite2_regional_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_regional_intitule" id="responsabilite2_regional_intitule" value="$responsabilite2_regional_intitule" required>
		<label for="responsabilite2_regional_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_regional_annee" min="1900" id="responsabilite2_regional_annee" value="$responsabilite2_regional_annee" required>
		
		<h5>Internationale</h5>
		<label for="responsabilite2_international_intitule">Intitulé de l'élément et fonction</label>
			<input type="text" name="responsabilite2_international_intitule" id="responsabilite2_international_intitule" value="$responsabilite2_international_intitule" required>
		<label for="responsabilite2_international_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite2_international_annee" min="1900" id="responsabilite2_international_annee" value="$responsabilite2_international_annee" required>
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
				sanitize_text_field($_POST["responsabilite3_intitule"]),
				$_POST["responsabilite3_annee"]
			];
			replace_or_pushes_values(122, $data);
		}

		$responsabilite3_intitule = null;
		$responsabilite3_annee = null;

		if(getCell(122) !== " "){
			$responsabilite3_intitule = getCell(122);
		}

		if(getCell(123) !== " "){
			$responsabilite3_annee = getCell(123);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Responsabilités & administration de la formation/enseignement</h5>
		<label for="responsabilite3_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="responsabilite3_intitule" id="responsabilite3_intitule" value="$responsabilite3_intitule" required>
		<label for="responsabilite3_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite3_annee" min="1900" id="responsabilite3_annee" value="$responsabilite3_annee" required>
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
				sanitize_text_field($_POST["vulgarisation_intitule"]),
				$_POST["vulgarisation_annee"]
			];
			replace_or_pushes_values(125, $data);
		}

		$vulgarisation_intitule = null;
		$vulgarisation_annee = null;

		if(getCell(125) !== " "){
			$vulgarisation_intitule = getCell(125);
		}

		if(getCell(126) !== " "){
			$vulgarisation_annee = getCell(126);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Vulgarisation, dissémination scientifique</h5>
		<label for="vulgarisation_intitule">Intitulé de l'élément (évènement, vidéo, livre, …) et fonction</label>
			<input type="text" name="vulgarisation_intitule" id="vulgarisation_intitule" value="$vulgarisation_intitule" required>
		<label for="vulgarisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="vulgarisation_annee"  min="1900" id="vulgarisation_annee" value="$vulgarisation_annee" required>
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
				sanitize_text_field($_POST["rayonnement"])
			];
			replace_or_pushes_values(128, $data);
		}

		$rayonnement = null;

		if(getCell(128) !== " "){
			$rayonnement = getCell(128);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Rayonnement / résultats majeurs sur la période à mettre en avant <label for="rayonnement"></label> </h5>
			<input type="text" id="rayonnement" name="rayonnement" value="$rayonnement" required>
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
				sanitize_text_field($_POST["brevet_intitule"]),
				$_POST["brevet_annee"]
			];
			replace_or_pushes_values(130, $data);
		}

		$brevet_intitule = null;
		$brevet_annee = null;

		if(getCell(130) !== " "){
			$brevet_intitule = getCell(130);
		}

		if(getCell(131) !== " "){
			$brevet_annee = getCell(131);
		}

		return <<<HTML
	<form method="POST" class="data-table-form">
		<h5>Brevet</h5>
		<label for="brevet_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="brevet_intitule" id="brevet_intitule" value="$brevet_intitule" required>
		<label for="brevet_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="brevet_annee" id="brevet_annee" value="$brevet_annee" required>
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