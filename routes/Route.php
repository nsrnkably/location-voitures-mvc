<?php

namespace App\Routes;

use App\Providers\View;

/**
 * Routeur : enregistre les routes (GET et POST) déclarées dans web.php,
 * puis appelle la bonne méthode du bon contrôleur selon l'adresse demandée.
 */
class Route {
    
    // Liste de toutes les routes enregistrées
    private static $routes = [];

    // Enregistre une route GET (affichage d'une page)
    static public function get(string $url, string $controller):void{
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'GET'];
    }

    // Enregistre une route POST (envoi d'un formulaire)
    static public function post(string $url, string $controller):void{
        self::$routes[] = ['url' => $url, 'controller' => $controller, 'method' => 'POST'];
    }

    // Cherche la route qui correspond à l'adresse et à la méthode demandées
    static public function dispatch(){
        // On sépare le chemin (/voiture/show) des paramètres (id=3)
        $url = $_SERVER['REQUEST_URI'];
        $urlSegments = explode('?', $url);
        $urlPath = $urlSegments[0];
        $method = $_SERVER['REQUEST_METHOD'];

        foreach(self::$routes as $route){

            if(BASE.$route['url'] == $urlPath && $route['method'] == $method){
                // 'VoitureController@index' -> contrôleur VoitureController, méthode index
                $controllerSegments = explode('@', $route['controller']);
                $controllerName = 'App\\Controllers\\'.$controllerSegments[0];
                $methodName = $controllerSegments[1];

                $controllerInstance = new $controllerName();

                if($method == "GET"){
                    // Les paramètres de l'adresse sont envoyés à la méthode sous forme de tableau
                    if(isset($urlSegments[1])){
                        parse_str($urlSegments[1], $queryParams);
                        $controllerInstance->$methodName($queryParams);
                    }else{
                        $controllerInstance->$methodName();
                    }
                    
                }elseif($method == "POST"){
                    // Les données du formulaire ($_POST), et les paramètres de l'adresse s'il y en a
                    if(isset($urlSegments[1])){
                        parse_str($urlSegments[1], $queryParams);
                        $controllerInstance->$methodName($_POST, $queryParams);
                    }else{
                        $controllerInstance->$methodName($_POST);
                    }
                    
                }
                return;
            }

        }
        // Aucune route trouvée : page 404 avec le design du site
        http_response_code(404);
        return View::render('error', ['message' => 'Cette page est introuvable (erreur 404).']);

    }

}