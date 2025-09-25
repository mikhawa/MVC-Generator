# MVC-Generator
Générateur MVC en PHP avec un .phar

## Description
Ce projet est un générateur MVC en PHP qui permet de créer rapidement la structure de base d'une application web en utilisant le modèle MVC (Modèle-Vue-Contrôleur). Le générateur est empaqueté dans un fichier .phar pour une distribution facile.

## Installation
1. Téléchargez le fichier `MVC-Generator.phar` depuis le dépôt GitHub.
2. Placez le fichier dans le répertoire de votre choix.
3. Assurez-vous que vous avez PHP installé sur votre machine.
4. Modifier le fichier `php.ini` pour activer l'extension Phar si ce n'est pas déjà fait.
```ini
;phar.readonly = On
;En remplaçant par sans le point-virgule
phar.readonly = Off
```