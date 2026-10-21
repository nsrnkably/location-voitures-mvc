<?php
namespace App\Controllers;

use App\Models\Location;
use App\Models\Client;
use App\Models\Voiture;
use App\Providers\View;
use App\Providers\Validator;

/**
 * Contrôleur des locations : liste, ajout, modification, suppression.
 * Une location relie un client et une voiture (relation N-N).
 */
class LocationController {

    // Liste des locations avec le client, la voiture, la durée et le montant
    public function index(){
        $location = new Location;
        $locations = $location->selectAvecDetails();
        return View::render('location/index', ['locations' => $locations]);
    }

    // Formulaire d'ajout
    public function create(){
        return View::render('location/create', $this->listes());
    }

    // Enregistrement d'une nouvelle location (POST location/create)
    public function store($data = []){
        $val = $this->valider($data);

        if($val->isSuccess()){
            $location = new Location;
            if($location->insert($data)){
                $_SESSION['message'] = 'La location a été ajoutée.';
                return View::redirect('locations');
            }
            return View::render('error', ['message' => "La location n'a pas pu être ajoutée."]);
        }
        return View::render('location/create', array_merge($this->listes(), ['errors' => $val->getErrors(), 'location' => $data]));
    }

    // Formulaire de modification (location/edit?id=...)
    public function edit($data = []){
        if(isset($data['id']) && $data['id'] != null){
            $location = new Location;
            $selectId = $location->selectId($data['id']);
            if($selectId){
                return View::render('location/edit', array_merge($this->listes(), ['location' => $selectId]));
            }
            return View::render('error', ['message' => 'Cette location est introuvable.']);
        }
        return View::render('error', ['message' => 'Aucune location sélectionnée.']);
    }

    // Enregistrement des modifications (POST location/edit?id=...)
    public function update($data = [], $get = []){
        if(isset($get['id']) && $get['id'] != null){
            $val = $this->valider($data);

            if($val->isSuccess()){
                $location = new Location;
                if($location->update($data, $get['id'])){
                    $_SESSION['message'] = 'La location a été modifiée.';
                    return View::redirect('locations');
                }
                return View::render('error', ['message' => "La location n'a pas pu être modifiée."]);
            }
            return View::render('location/edit', array_merge($this->listes(), ['errors' => $val->getErrors(), 'location' => $data]));
        }
        return View::render('error', ['message' => 'Aucune location sélectionnée.']);
    }

    // Suppression (POST location/delete)
    public function delete($data = []){
        if(isset($data['id']) && $data['id'] != null){
            $location = new Location;
            if($location->delete($data['id'])){
                $_SESSION['message'] = 'La location a été supprimée.';
                return View::redirect('locations');
            }
            return View::render('error', ['message' => "La location n'a pas pu être supprimée."]);
        }
        return View::render('error', ['message' => 'Aucune location sélectionnée.']);
    }

    // Clients et voitures pour les deux menus déroulants (clés étrangères)
    private function listes(){
        $client = new Client;
        $voiture = new Voiture;
        return [
            'clients' => $client->select('nom'),
            'voitures' => $voiture->select('marque')
        ];
    }

    // Règles de validation communes à l'ajout et à la modification
    private function valider($data){
        $val = new Validator;
        $val->field('client_id', $data['client_id'], 'Le client')->required()->int();
        $val->field('voiture_id', $data['voiture_id'], 'La voiture')->required()->int();
        $val->field('date_debut', $data['date_debut'], 'La date de début')->required()->date();
        $val->field('date_fin', $data['date_fin'], 'La date de fin')->required()->date();

        // Le client et la voiture choisis doivent exister (clés étrangères)
        if($data['client_id'] != '' && !(new Client)->selectId($data['client_id'])){
            $val->custom('client_id', "Le client choisi n'existe pas.");
        }
        if($data['voiture_id'] != '' && !(new Voiture)->selectId($data['voiture_id'])){
            $val->custom('voiture_id', "La voiture choisie n'existe pas.");
        }
        // La date de fin doit être après la date de début
        if($val->isSuccess() && $data['date_fin'] <= $data['date_debut']){
            $val->custom('date_fin', 'La date de fin doit être après la date de début.');
        }
        return $val;
    }
}