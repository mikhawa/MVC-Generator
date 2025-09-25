# MVC-Generator
Générateur MVC en PHP avec un .phar

## Description
Ce projet est un générateur MVC en PHP qui permet de créer rapidement la structure de base d'une application web en utilisant le modèle MVC (Modèle-Vue-Contrôleur). Le générateur est empaqueté dans un fichier .phar pour une distribution facile.

## Installation
1. Téléchargez le fichier `MVC-Generator.phar` depuis le dépôt GitHub.
2. Placez le fichier dans le répertoire de votre choix.
3. Assurez-vous que vous avez PHP (minimum 7.2 et plus) installé sur votre machine en mode console.
4. Modifier le fichier `php.ini` de votre version PHP (`php -v`) dans la console pour activer l'extension Phar si ce n'est pas déjà fait.
```ini
;phar.readonly = On
;En remplaçant par sans le point-virgule
phar.readonly = Off
```
5. Redémarrez votre serveur web si nécessaire et fermez puis rouvrez votre terminal.
6. Créez un nouveau répertoire pour votre projet MVC.
7. Mettez-y `MVC-Generator.phar`.
8. Ouvrez un terminal dans le répertoire de votre projet.
9. Exécutez la commande suivante pour générer la structure MVC :
```bash
php MVC-Generator.phar
```

## Modification
Vous pouvez modifier les fichiers générés selon vos besoins. Il suffira de mettre la structure voulue dans le dossier `template`, puis de re-générer le .phar avec la commande :
```bash
php build.php
```
