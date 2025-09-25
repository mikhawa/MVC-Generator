<?php
// build.php mis à jour

try {
    $pharFile = 'MVC-Generator.phar';

    if (file_exists($pharFile)) {
        unlink($pharFile);
    }

    $phar = new Phar($pharFile);
    $phar->startBuffering();

    // On ajoute le script de génération et le dossier des templates
    $phar->buildFromDirectory('.', '/^(generate\.php|template\/.*)$/');

    // LE PLUS IMPORTANT : Le stub doit maintenant pointer vers notre script de génération
    $stub = $phar->createDefaultStub('generate.php');
    $stubWithShebang = "#!/usr/bin/env php \n" . $stub;
    $phar->setStub($stubWithShebang);

    $phar->stopBuffering();

    // Rendre le fichier directement exécutable (pour Linux/macOS)
    chmod($pharFile, 0755);

    echo "✅ Le générateur '$pharFile' a été créé et rendu exécutable !";

} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage();
}
