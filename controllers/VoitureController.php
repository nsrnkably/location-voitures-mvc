<?php
namespace App\Controllers;

use App\Models\Voiture;
use App\Models\Categorie;
use App\Providers\View;
use App\Providers\Validator;

/**
 * Contrôleur des voitures : gère les actions du CRUD de la table voiture
 * (liste, détails, ajout, modification, suppression).
 */
class VoitureController {

    // Liste de toutes les voitures avec le nom de leur catégorie
    public function index(){
        $voiture = new Voiture;
        $voitures = $voiture->selectAvecCategorie();
        return View::render('voiture/index', ['voitures' => $voitures]);
    }

    // Détails d'une voiture (voiture/show?id=...)
    public function show($data = []){
        if(isset($data['id']) && $data['id'] != null){
            $voiture = new Voiture;
            $selectId = $voiture->selectIdAvecCategorie($data['id']);
            if($selectId){
                return View::render('voiture/show', ['voiture' => $selectId]);
            }
            return View::render('error', ['message' => 'Cette voiture est introuvable.']);
        }
        return View::render('error', ['message' => 'Aucune voiture sélectionnée.']);
    }

    // Formulaire d'ajout
    public function create(){
        $categorie = new Categorie;
        $categories = $categorie->select('nom');
        return View::render('voiture/create', ['categories' => $categories]);
    }

    // Enregistrement d'une nouvelle voiture (POST voiture/create)
    public function store($data = []){
        $val = $this->valider($data);

        if($val->isSuccess()){
            $voiture = new Voiture;
            $insert = $voiture->insert($data);
            if($insert){
                $_SESSION['message'] = 'La voiture a été ajoutée.';
                return View::redirect('voitures');
            }
            return View::render('error', ['message' => "La voiture n'a pas pu être ajoutée."]);
        }else{
            // On réaffiche le formulaire avec les erreurs et les valeurs saisies
            $categorie = new Categorie;
            $categories = $categorie->select('nom');
            return View::render('voiture/create', ['errors' => $val->getErrors(), 'voiture' => $data, 'categories' => $categories]);
        }
    }

    // Formulaire de modification (voiture/edit?id=...)
    public function edit($data = []){
        if(isset($data['id']) && $data['id'] != null){
            $voiture = new Voiture;
            $selectId = $voiture->selectId($data['id']);
            if($selectId){
                $categorie = new Categorie;
                $categories = $categorie->select('nom');
                return View::render('voiture/edit', ['voiture' => $selectId, 'categories' => $categories]);
            }
            return View::render('error', ['message' => 'Cette voiture est introuvable.']);
        }
        return View::render('error', ['message' => 'Aucune voiture sélectionnée.']);
    }

    // Enregistrement des modifications (POST voiture/edit?id=...)
    public function update($data = [], $get = []){
        if(isset($get['id']) && $get['id'] != null){
            $val = $this->valider($data);

            if($val->isSuccess()){
                $voiture = new Voiture;
                $update = $voiture->update($data, $get['id']);
                if($update){
                    $_SESSION['message'] = 'La voiture a été modifiée.';
                    return View::redirect('voitures');
                }
                return View::render('error', ['message' => "La voiture n'a pas pu être modifiée."]);
            }else{
                $categorie = new Categorie;
                $categories = $categorie->select('nom');
                return View::render('voiture/edit', ['errors' => $val->getErrors(), 'voiture' => $data, 'categories' => $categories]);
            }
        }
        return View::render('error', ['message' => 'Aucune voiture sélectionnée.']);
    }

    // Suppression (POST voiture/delete) : refusée si la voiture a des locations
    public function delete($data = []){
        if(isset($data['id']) && $data['id'] != null){
            $voiture = new Voiture;
            if($voiture->aDesLocations($data['id'])){
                $_SESSION['erreur'] = 'Impossible de supprimer cette voiture : elle a déjà des locations.';
                return View::redirect('voiture/show?id='.$data['id']);
            }
            if($voiture->delete($data['id'])){
                $_SESSION['message'] = 'La voiture a été supprimée.';
                return View::redirect('voitures');
            }
            return View::render('error', ['message' => "La voiture n'a pas pu être supprimée."]);
        }
        return View::render('error', ['message' => 'Aucune voiture sélectionnée.']);
    }

    // Règles de validation communes à l'ajout et à la modification
    private function valider($data){
        $anneeMax = date('Y') + 1;

        $val = new Validator;
        $val->field('marque', $data['marque'], 'La marque')->required()->min(2)->max(45);
        $val->field('modele', $data['modele'], 'Le modèle')->required()->max(45);
        $val->field('annee', $data['annee'], "L'année")->required()->int()->valeurMin(1990)->valeurMax($anneeMax);
        $val->field('prix_jour', $data['prix_jour'], 'Le prix par jour')->required()->number()->valeurMin(1)->valeurMax(9999);
        $val->field('categorie_id', $data['categorie_id'], 'La catégorie')->required()->int();

        // La catégorie choisie doit exister dans la base (clé étrangère)
        if($data['categorie_id'] != '' && !(new Categorie)->selectId($data['categorie_id'])){
            $val->custom('categorie_id', "La catégorie choisie n'existe pas.");
        }
        return $val;
    }
}