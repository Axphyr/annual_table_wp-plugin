
# HCERES

Plugin WordPress qui permet la gestion d'une ou de plusieurs tables de données pour récolter des données d'utilisateurs.
Créée sur-mesure pour l'ISTeP.
## Author

- [@Axphyr](https://github.com/Axphyr)


## Installation

Cloner ce dépôt dans le dossier des plugins de WordPress

```bash
cd wp-content/plugins/
git clone https://github.com/Axphyr/annual_table_wp-plugin.git
```
    
## Architecture

Où trouver le fichier CSV ? 

```bash
wp-admin/hceres/
```

Où trouver les backups ?

```bash
wp-admin/backup-hceres/
```

## Fonctionnement fichier CSV

Lorsqu'on veut créer un fichier CSV à partir de PHP, il faut tenir en compte que si l'on veut faire en sorte que les champs sont placés bien en dessous de leur catégorie, il faut que la catégorie à laquelle ils appartiennent soit bien aligné.

Pour se faire, on utilise des tableaux, par exemple :
```bash
array(
  array("Champ 1", "Champ 2", "Champ 3")
  );
```
Donnera un tableau du genre :

<pre>
+------------------------------------+
│  Champ 1  │   Champ 2    │ Champ 3 │
+------------------------------------+
</pre>
Or, si l'on veut les mettre sous une catégorie, on devrait faire ça :

```bash
array(
  array('', 'Catégorie 1', ''),
  array("Champ 1", "Champ 2", "Champ 3")
  );
```
<span style="color:red;">On rajoute des '' ou des "" pour faire en sorte d'aligner la catégorie au milieu.</span>

Ça donnerait donc : 

<pre>
+------------------------------------+
│           │  Catégorie 1 │         │
+------------------------------------+
│  Champ 1  │   Champ 2    │ Champ 3 │
+------------------------------------+
</pre>
