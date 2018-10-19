# mvc_framework_core

#commandes :
 - **./framecomm install** -> install les dépendences, clones les répos git, les répertoirs aux bon endroit et install les logiciels utilitaires sur la machine.
 - **./framecomm update** -> update les repos git, regénère les répertoirs et fichiers supprimés accidentelement, et install les nouveaux packages de composer et npm.
 - **./framecomm sass_compile** -> compile tous les fichiers sass.
 - **./framecomm concat_css** -> concatenne tous les fichiers css en un seul ***main.css*** dans le repertoir ***css/concat***.
 - **./framecomm genere_scss_doc** -> genere la documentation css au format html grace au gestionaire de templates **Blade**. 
 
 # Format de doc css :
 
```
/**
@author Prénom Nom
@date Date
 
@title Titre

@description
Voici une description d'un composant de test Sass
avec des retours à la lignes
et encore.

@modifiers
.modifier1 - Voici un exemple de modifieur
.modifier2 - un 2em exemple

@code-demo
<div class="class">
	<div class="toto {class_modifier}">
		voici du text
	</div>
</div>

<div class="class">
	<div class="toto {class_modifier}">
		mais il peux aussi y avoir des espaces
	</div>
</div>
*/
 ```
 
 ou
 
```
/**
@author Prénom Nom
@date Date

@title Titre

@description
Voici une description d'un composant scss
avec des retours à la lignes
et encore.

@code-demo
<a href="#">voici un lien</a>
*/
```