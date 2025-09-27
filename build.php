<?php
// build.php - Version corrigée avec la bonne méthode d'ajout

try {
    $pharFile = 'MVC-Generator.phar';

    if (ini_get('phar.readonly')) {
        throw new Exception('La création de PHAR est désactivée (phar.readonly = Off). Activez le dans php.ini de la version PHP dans la console.');
    }

    if (file_exists($pharFile)) {
        unlink($pharFile);
    }

    // --- Étape 1 : On trouve les fichiers (logique inchangée et correcte) ---

    $basePath = realpath(__DIR__);
    $regex = '/^(generate\.php|template[\\/\\\\].*)$/';

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($basePath, FilesystemIterator::SKIP_DOTS)
    );

    $filesToAdd = [];
    foreach ($iterator as $file) {
        if (in_array($file->getFilename(), ['build.php', $pharFile])) {
            continue;
        }

        $relativePath = str_replace($basePath . DIRECTORY_SEPARATOR, '', $file->getPathname());

        if (preg_match($regex, $relativePath)) {
            $filesToAdd[$relativePath] = $file->getRealPath();
        }
    }

    if (empty($filesToAdd)) {
        throw new Exception("Aucun fichier à ajouter n'a été trouvé. Vérifiez la structure de vos dossiers.");
    }

    // --- Étape 2 : On construit l'archive ---

    $phar = new Phar($pharFile);
    $phar->startBuffering();

    // CORRECTION : On remplace buildFromArray par une boucle et addFile
    foreach ($filesToAdd as $relativePathInPhar => $realPathOnDisk) {
        $phar->addFile($realPathOnDisk, $relativePathInPhar);
    }

    // --- Étape 3 : On finalise l'archive ---

    $stub = $phar->createDefaultStub('generate.php');
    $stubWithShebang = "#!/usr/bin/env php \n" . $stub;
    $phar->setStub($stubWithShebang);

    $phar->stopBuffering();

    if (PHP_OS_FAMILY !== 'Windows') {
        chmod($pharFile, 0755);
    }

    echo "✅ Le générateur '$pharFile' a été créé avec succès !\n";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}