
# HCERES

Plugin WordPress qui permet la gestion d'une ou de plusieurs tables de données pour récolter des données d'utilisateurs.
Créée sur-mesure pour l'ISTeP.
## Auteur

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

<strong> Comment récupérer des données dans le code?</strong>

C'est grâce à la fonction <span style="color:orange;">getCell($champ, $décalage);</span>

Pour un utilisateur WordPress Arbër Jonuzi, si j'utilise cette fonction pour récupérer l'année de naissance par exemple, on aura donc le code suivant : 

```bash
getCell("Annee Naissance");
```

Cette fonction retournera donc :

```bash
2003
```

<strong> Attention : </strong> Ne pas donner de décalage si non nécessaire. Dans ce cas là, le décalage n'est utilie que lorqu'on a des catégories influant plusieurs lignes, comme pour la catégorie "<strong>Detail des publications par annee depuis 2022</strong>" où donc le décalage est l'année selon la ligne à laquelle on se situe.

Par exemple, pour cet utilisateur :

<pre>
+----------------------------------------------------------+
│           │  Informations Générales │                       ...
+----------------------------------------------------------+
│    Nom    │          Prénom         │ Année de naissance    ...
+----------------------------------------------------------+
│  Jonuzi   │           Arbër         │        2003           ...
+----------------------------------------------------------+
│  Jonuzi   │           Arbër         │                       ...
+----------------------------------------------------------+
</pre>

On a deux lignes parce qu'il a deux parties à un formulaire plus loin dans la table de données.

Ses réponses à ce formulaire plus loin seront donc quelque chose du genre : 

<pre>
+-----------------------------------------------------------------------------------------+
│                                        │  Detail des publications par annee depuis 2022    ...
+-----------------------------------------------------------------------------------------+
│    Nombre de publications de rang A    │        Nombre d'articles en 1er auteur            ...
+-----------------------------------------------------------------------------------------+
│                   45                   │                        22                         ...
+-----------------------------------------------------------------------------------------+
│                   53                   │                        36                         ...
+-----------------------------------------------------------------------------------------+
</pre>

L'utilisation de la fonction <span style="color:orange;">getCell("Nombre de publications de rang A", 1);</span>, dans ce cas mà, nous donnera :

```bash
53
```

Tandis qu'une utilisation sans décalage (<span style="color:orange;">getCell("Nombre de publications de rang A");</span>) donnerait : 

```bash
45
```

<strong>Comment ajouter un champ ?</strong>

Pour ajouter un champ au tableau de données, il faut donc rajouter son nouveau champ dans la bonne liste de données. Pour cela, regardez la liste des données dans la fonction d'activation du plugin (fonction <span style="color:orange;">annual_data_table_install</span>), c'est la variable <strong>$data</strong>, sa troisième liste qui contient les champs.

Or, il faut également utiliser la fonction <span style="color:orange;">getCell("Nom du champ");</span> dans la bonne fonction (celle où est affiché le formulaire de la catégorie dans laquelle vous insérez le nouveau champ). Mais également à rajouter ce nouveau champ dans le formulaire (suivez la façon dont j'ai fais les autres champs).

Normalement, tout est fait pour que vous n'ayez pas à toucher à autre chose.

<strong> Comment changer les rôles des personnes qui sont inscrites dans le tableau de ceux qui n'ont pas  encore repli le tableau ? </strong>

Allez dans la fonction <span style="color:orange;">panel()</span> et changez la variable <strong> $roles </strong>, metttez-y les rôles qui doivent figurer dans cette liste.
<strong> Attention </strong> : Veillez à bien mettre le bon nom du rôle, non pas le nom de rôle affiché, mais celui que WordPress aura enregistré. 
Pour ça allez dans les rôles WordPress et copiez le dans le tableau. 
Généralement, un rôle comme ceci : "Chef de projet" aura comme réel nom "chef_de_projet".

<strong> Comment changer la liste des rôles qui ont accès à tous les champs du tableau ? </strong>

Allez dans la fonction <span style="color:orange;">summary()</span> et modifiez la variable <strong> $hceresUsers </strong> en y rentrant la liste des nouveaux rôles ayant accès au reste des formulaires. Attention à toujours respecter les bons noms des rôles utilisés comme signalé ci-dessus.