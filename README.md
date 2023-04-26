# TUTOMARKS

Ce projet est ouvert à la contribution de tous. Vous pouvez contribuer en signalant des bugs, en proposant de nouvelles fonctionnalités ou en améliorant le code existant.

## Comment forker le projet

1. Cliquez sur le bouton "Fork" en haut de la page pour copier le projet dans votre compte GitHub.

2. Clonez le projet depuis votre compte GitHub vers votre ordinateur.

```bash
git clone https://github.com/votre-nom-d-utilisateur/tutomarks.git
```

3. Créez une branche pour vos modifications.

```bash
git checkout -b nom-de-votre-branche
```

## Installation

Pour installer le projet, vous aurez besoin de Symfony 6.1, de PHP 8.1, de Composer et de docker installés sur votre ordinateur.


1. Configurez votre base de données en renseignant les informations d'accès en copiant le fichier `.env` et en le renommant `.env.local`.

```bash
cp .env .env.local
```

2. Installez les dépendances du projet en utilisant Composer.

```bash
composer install
```

3. Créez la base de données.

```bash
php bin/console doctrine:database:create
```

4. Effectuez les migrations.

```bash
php bin/console doctrine:migrations:migrate
```

5. Lancez le serveur.

```bash
docker compose up -d
```
```bash
symfony server:start -d
```
Ou en utilisant **make** :
```bash
make all-start
```

6. Accédez à l'application en ouvrant votre navigateur et en accédant à l'URL renseigné par symfony, exemple : `http://localhost:8000`.

## Comment contribuer

1. Effectuez les modifications souhaitées.

2. Vérifiez que les standards de code soit respectés.

```bash
make stan
```
```bash
make lint-php
```
```bash
make fix-php
```

3. Effectuez une pull request vers le projet principal, décrivez vos modifications.

4. Attendez que votre pull request soit revue et acceptée.