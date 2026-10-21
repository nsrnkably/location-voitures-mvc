<?php
namespace App\Controllers;

use App\Models\Client;
use App\Providers\View;
use App\Providers\Validator;

/**
 * Contrôleur des clients : liste, ajout, modification, suppression.
 */
class ClientController {

    // Liste de tous les clients triés par nom.
    public function index(){
        $client = new Client;
        $clients = $client->select('nom');
        return View::render('client/index', ['clients' => $clients]);
    }

    // Formulaire d'ajout
    public function create(){
        return View::render('client/create');
    }

    // Enregistrement d'un nouveau client (POST client/create)
    public function store($data = []){
        $val = $this->valider($data);

        if($val->isSuccess()){
            $client = new Client;
            $insert = $client->insert($data);
            if($insert){
                $_SESSION['message'] = 'Le client a été ajouté.';
                return View::redirect('clients');
            }
            return View::render('error', ['message' => "Le client n'a pas pu être ajouté."]);
        }
        return View::render('client/create', ['errors' => $val->getErrors(), 'client' => $data]);
    }

    // Formulaire de modification (client/edit?id=...)
    public function edit($data = []){
        if(isset($data['id']) && $data['id'] != null){
            $client = new Client;
            $selectId = $client->selectId($data['id']);
            if($selectId){
                return View::render('client/edit', ['client' => $selectId]);
            }
            return View::render('error', ['message' => 'Ce client est introuvable.']);
        }
        return View::render('error', ['message' => 'Aucun client sélectionné.']);
    }

    // Enregistrement des modifications (POST client/edit?id=...)
    public function update($data = [], $get = []){
        if(isset($get['id']) && $get['id'] != null){
            $val = $this->valider($data, $get['id']);

            if($val->isSuccess()){
                $client = new Client;
                if($client->update($data, $get['id'])){
                    $_SESSION['message'] = 'Le client a été modifié.';
                    return View::redirect('clients');
                }
                return View::render('error', ['message' => "Le client n'a pas pu être modifié."]);
            }
            return View::render('client/edit', ['errors' => $val->getErrors(), 'client' => $data]);
        }
        return View::render('error', ['message' => 'Aucun client sélectionné.']);
    }

    // Suppression (POST client/delete) : refusée si le client a des locations
    public function delete($data = []){
        if(isset($data['id']) && $data['id'] != null){
            $client = new Client;
            if($client->aDesLocations($data['id'])){
                $_SESSION['erreur'] = 'Impossible de supprimer ce client : il a déjà des locations.';
                return View::redirect('clients');
            }
            if($client->delete($data['id'])){
                $_SESSION['message'] = 'Le client a été supprimé.';
                return View::redirect('clients');
            }
            return View::render('error', ['message' => "Le client n'a pas pu être supprimé."]);
        }
        return View::render('error', ['message' => 'Aucun client sélectionné.']);
    }

    // Règles de validation communes à l'ajout et à la modification.
    // $id : l'id du client modifié (pour qu'il ne compte pas lui-même dans le courriel unique)
    private function valider($data, $id = 0){
        $val = new Validator;
        $val->field('nom', $data['nom'], 'Le nom')->required()->min(2)->max(45);
        $val->field('prenom', $data['prenom'], 'Le prénom')->required()->min(2)->max(45);
        $val->field('courriel', $data['courriel'], 'Le courriel')->required()->email()->max(100);
        $val->field('telephone', $data['telephone'], 'Le téléphone')->max(20);

        // Le courriel doit être unique (contrainte unique_courriel de la base)
        $client = new Client;
        if($data['courriel'] != '' && $client->courrielExiste(trim($data['courriel']), $id)){
            $val->custom('courriel', 'Ce courriel est déjà utilisé par un autre client.');
        }
        return $val;
    }
}