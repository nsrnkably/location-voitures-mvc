<?php
namespace App\Providers;

use Twig\Loader\FilesystemLoader;
use Twig\Environment;

/**
 * Classe View : affiche les vues Twig et fait les redirections.
 */
class View {

    // Affiche la vue $template (dossier views/) avec les données $data
    static public function render(string $template, $data = []){
        $loader = new FilesystemLoader('views');
        $twig = new Environment($loader);

        // Variables disponibles dans toutes les vues : {{ base }} et {{ asset }}
        $twig->addGlobal('asset', ASSET);
        $twig->addGlobal('base', BASE);

        // Messages « flash » : on les lit dans la session, on les envoie à la vue,
        // puis on les efface. Ils ne s'affichent donc qu'une seule fois.
        $twig->addGlobal('message', $_SESSION['message'] ?? null);
        $twig->addGlobal('erreur', $_SESSION['erreur'] ?? null);
        unset($_SESSION['message'], $_SESSION['erreur']);

        echo $twig->render($template.".php", $data);

    }

    // Redirige vers une autre page du site (ex. : View::redirect('voitures'))
    static public function redirect(string $url){
        header('location:'.BASE.'/'.$url);
        exit;
    }

}