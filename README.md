# Tutomarks

## Informations sur le rôle du site:

Site destiné aux futurs développeurs (et aux anciens ;) ) qui regroupe des liens vers des tutoriaux.

Il s'agit bien **uniquement de liens proposés par la communauté** et absolument pas une tentative de voler le travail des autres.

## Roadmap:

### Todo :
- [ ] Pagination des résultats de recherche sans rechargement de la page
- [ ] Dashboard: ajouter "les mieux notés"
- [ ] Système de statistiques : "les plus consultés"
- [ ] Logo pour les Tags, les Catégories
- [ ] Avatars utilisateurs
- [ ] Définition des catégories
- [ ] Traduction du site en anglais
- [ ] Système de Badge de "contributeur"
- [ ] Système de badges pour les utilisateurs (1 ière contribution, ...)
- [ ] "Notes personnelles" (= post-it) sur les vidéos

### Terminé :
- [x] Envois de mail lors de l'inscription + mot de passe oublié
- [x] Affichage connection réussie
- [x] Mot de passe oublié
- [x] Ajouter "contributeur" sur la fiche (show) des tutos
- [x] ~~Page~~ Formulaire Contact
- [x] Logo pour le site :p
- [x] Pages d'erreur personnalisées
- [x] Système de chargement des commentaires "à la volée" et non pas au chargement de la page
- [x] Système de partage sur les réseaux sociaux de ses publications ou de tutos préférés
- [x] Contrôle des doublons (fiches tutos => lien)
- [x] Ajouter une section "Mes contributions"
- [x] Garder la dernière recherche si retour
- [x] SEO du site => mots clés en desc, accroche plus complète, ...
- [x] Retour sur la page/fiche en cours si demande de connexion depuis un bouton (ex: page tuto/show => connexion pour laisser un commentaire ou noter le tuto)
- [x] Connexions Oauth
    - [x] Google
    - [x] Github
- [x] Signaler un lien mort
- [x] Miniatures pour les liens youtube
- [x] Pouvoir marquer un tuto comme "Favoris" (= épingler)
  - [x] Faire entity + affichage sur tutos
  - [x] Recherche par épingle
- [x] Option "Marquer comme vu" pour les utilisateurs
  - [x] Recherche par "Marquer comme vu"
- [x] Système de "Fiche auteur" pour faciliter la recherche par "Créateur du tuto"

## Coté technique :

- Symfony 5.1
- MySQL
- Bootstrap 4 + thème Bootswatch LUX =>  https://bootswatch.com/lux/
- Un peu de React aussi

## Lancer l'application en dev local

Le dépot contient une image Docker très simple contenant uniquement un Mysql. 

Après avoir récupéré le dépot en local, il suffit de se positionner dans le répertoire ./docker et de lancer:
`docker-compose up -d`
  
Note MySql: Les informations de connexion sont disponible dans le fichier docker/.env


Pour le serveur web, j'utilise le server interne de Symfony. (https://symfony.com/doc/5.1/setup/symfony_server.html#enabling-php-fpm).

Note PHP: j'utilise une version 7.3 de PHP, il faut donc le préciser dans un fichier .php-version en racine du projet
  
Pour démarrer le serveur Web, dans un terminal, il faut lancer depuis la racine du projet, la commande: `symfony serve`

Par défaut, le serveur sera accessible à l'adresse: http://127.0.0.1:8000
