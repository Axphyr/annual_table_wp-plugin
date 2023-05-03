<?php
/*
Plugin Name: Semi-annual to annual data table in csv for ISTeP
Plugin URI: https://wpusermanager.com/
Description: Crée et gère un tableau de données pour l'ISTeP
Author: Robin Simonneau, Arbër Jonuzi
Version: 1.0
Author URI: https://robin-sim.fr/
*/
error_reporting(E_ALL); ini_set('display_errors', '1');

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
			"Intitulé de l'élément et votre fonction", 'Année ou période (début MM/AAAA - fin MM/AAAA)', '|'),
		array('John Doe', 'john@example.com', '123-456-7890'),
		array('Jane Smith', 'jane@example.com', '555-555-5555'),
		array('Bob Johnson', 'bob@example.com', '111-222-3333')
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

add_shortcode('add_istep_annual_table_form','form_annual_table');

function form_annual_table(): string
{
	$html = <<<HTML
	<h4>Formulaire ajout ligne à table</h4>

	<form method="POST" class="data-table-form">
	
	<h5>Informations générales</h5>
		<label for="last_name">Nom
			<input type="text" name="last_name" required>
		</label>
		<label for="name">Prénom
			<input type="text" name="name" required>
		</label>
		<label for="equipe1">Equipe 2017-2022</label>
		<select name="equipe1">
			<option value="0"></option>
			<option value="1">DEMO</option>
			<option value="2">PGM2</option>
			<option value="3">PPB</option>
		</select>
		<label for="equipe2">Equipe 2022-2025</label>
		<select name="equipe2">
			<option value="0"></option>
			<option value="1">PETRODYN</option>
			<option value="2">TECTO</option>
			<option value="3">TERMER</option>
		</select>
		<label for="equipe3">Equipe 2025-…</label>
		<select name="equipe3">
			<option value="0"></option>
			<option value="1">PETRODYN</option>
			<option value="2">TECTO</option>
			<option value="3">TERMER</option>
			<option value="4">PRISME</option>
		</select>
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
			
		<h5>Discipline</h5>
		<label for="discipline1">Discipline 1</label>
			<input type="text" name="discipline1" required>
		<label for="discipline2">Discipline 2</label>
			<input type="text" name="discipline2" required>
			
		<h5>Thèmes de recherche (80 mots max)</h5>
			<input type="text" name="theme_recherche" required>
		
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
		
		<h5>Enseignement</h5>
		<label for="enseignement1">nb heures enseignées 2022-2023</label>
			<input type="number" name="enseignement1" required>
		<label for="enseignement2">nb heures enseignées 2023-2024</label>
			<input type="number" name="enseignement2" required>
		<label for="enseignement3">nb heures enseignées 2024-2025</label>
			<input type="number" name="enseignement3" required>
		<label for="enseignement4">nb heures enseignées 2025-2026</label>
			<input type="number" name="enseignement4" required>
		
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
		
		<h5>Encadrement thèse ISTeP à partir de 2022</h5>
		<label for="encadrement_istep_nom">Nom</label>
			<input type="text" name="encadrement_istep_nom" required>
		<label for="encadrement_istep_prenom">Prénom</label>
			<input type="text" name="encadrement_istep_prenom" required>
		<label for="encadrement_istep_hf">H/F</label>
			<select name="sexe">
				<option value="1">Homme</option>
				<option value="2">Femme</option>
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
		
		<h5>Encadrement thèse hors ISTeP à partir de 2022</h5>
		<label for="encadrement_histep_nom">Nom</label>
			<input type="text" name="encadrement_histep_nom" required>
		<label for="encadrement_histep_prenom">Prénom</label>
			<input type="text" name="encadrement_histep_prenom" required>
		<label for="encadrement_histep_hf">H/F</label>
			<select name="sexe">
				<option value="1">Homme</option>
				<option value="2">Femme</option>
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
		
		<h5>Encadrement de post-doctorats à partir de 2022</h5>
		<label for="encadrement_pd_nom">Nom</label>
			<input type="text" name="encadrement_pd_nom" required>
		<label for="encadrement_pd_prenom">Prénom</label>
			<input type="text" name="encadrement_pd_prenom" required>
		<label for="encadrement_pd_hf">H/F</label>
			<select name="sexe">
				<option value="1">Homme</option>
				<option value="2">Femme</option>
			</select>
		<label for="encadrement_pd_date_entree">Date d'entrée (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_entree" required>
		<label for="encadrement_pd_date_sortie">Date de sortie (MM/AAAA)</label>
			<input type="date" name="encadrement_pd_date_sortie" required>
		<label for="encadrement_pd_annee_naissance">Année de naissance</label>
			<input type="date" name="encadrement_pd_annee_naissance" required>
		<label for="encadrement_pd_employeur">Etablissement ou organisme employeur</label>
			<input type="text" name="encadrement_pd_employeur" required>
		
		<h5>Prix ou distinctions scientifiques</h5>
		<label for="distinction_intitule">Intitulé de l'élément de distinction (nom du prix par exemple)</label>
			<input type="text" name="distinction_intitule" required>
		<label for="distinction_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="distinction_annee" required>
		
		<h5>Appartenance à l'IUF</h5>
		<label for="iuf_intitule">Intitulé de l'élément (membre, fonction …)</label>
			<input type="text" name="iuf_intitule" required>
		<label for="iuf_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="iuf_annee" required>
		
		<h5>Séjours dans des laboratoires étrangers</h5>
		<label for="sejour_lieu">Lieu, fonction</label>
			<input type="text" name="sejour_lieu" required>
		<label for="sejour_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="sejour_annee" required>
		
		<h5>Organisations de colloques/congrès internationaux</h5>
		<label for="organisation_nom">Nom de l'évènement, fonction</label>
			<input type="text" name="organisation_nom" required>
		<label for="organisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="organisation_annee" required>
		
		<h5>Responsabilités dans des sociétés savantes</h5>
		<label for="societe_savantes_nom">Nom de la société, fonction</label>
			<input type="text" name="societe_savantes_nom" required>
		<label for="societe_savantes_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="societe_savantes_annee" required>
		
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
		
		<h5>Responsabilités & administration de la formation/enseignement</h5>
		<label for="responsabilite3_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="responsabilite3_intitule" required>
		<label for="responsabilite3_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="responsabilite3_annee" required>
		
		<h5>Vulgarisation, dissémination scientifique</h5>
		<label for="vulgarisation_intitule">Intitulé de l'élément (évènement, vidéo, livre, …) et fonction</label>
			<input type="text" name="vulgarisation_intitule" required>
		<label for="vulgarisation_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="vulgarisation_annee" required>
		
		<h5>Rayonnement / résultats majeurs sur la période à mettre en avant</h5>
			<input type="text" name="rayonnement" required>
		
		<h5>Brevet</h5>
		<label for="brevet_intitule">Intitulé de l'élément et votre fonction</label>
			<input type="text" name="brevet_intitule" required>
		<label for="brevet_annee">Année ou période (début MM/AAAA - fin MM/AAAA)</label>
			<input type="text" name="brevet_annee" required>
			
	</form>
HTML;

	return $html;
}