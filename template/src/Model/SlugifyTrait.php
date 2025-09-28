<?php
// création du namespace
namespace model;

use Exception;

// création du trait
trait SlugifyTrait{

    public function slugify(string $text, $prefix=true, string $separator = '-'): string
    {
        // 1. Remplacer les caractères non alphabétiques ou numériques par le séparateur
        $text = preg_replace('~[^\pL\d]+~u', $separator, $text);


        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // 3. Supprimer les caractères qui ne sont ni des tirets, ni alphanumériques
        $text = preg_replace('~[^-\w]+~', '', $text);

        // 4. Supprimer les séparateurs en début et fin de chaîne
        $text = trim($text, $separator);

        // 5. Supprimer les séparateurs en double
        $text = preg_replace('~-+~', $separator, $text);

        // 6. Mettre toute la chaîne en minuscules
        $text = strtolower($text);

        // si pas de texte valide
        if (empty($text)) {
            throw new Exception("Slugify failed");
        }

        // 7. Ajouter un préfixe aléatoire si demandé
        if($prefix===true) {
            $text = bin2hex(random_bytes(2)) . "-" . $text;
        }

        // 8. On retourne la chaîne
        return $text;
    }
}