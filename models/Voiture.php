<?php
namespace App\Models;
use App\Models\CRUD;

/**
 * Modèle Voiture : table voiture.
 * Clé étrangère categorie_id vers la table categorie (relation 1-N).
 */
class Voiture extends CRUD{
    protected $table = 'voiture';
    protected $primaryKey = 'id';
    protected $fillable = ['marque', 'modele', 'annee', 'prix_jour', 'categorie_id'];

    // Retourne toutes les voitures avec le nom de leur catégorie (jointure)
    public function selectAvecCategorie():array{
        $sql = "SELECT voiture.*, categorie.nom AS categorie
                FROM $this->table
                INNER JOIN categorie ON voiture.categorie_id = categorie.id
                ORDER BY voiture.marque, voiture.modele";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    // Retourne une voiture avec le nom de sa catégorie, ou false si elle n'existe pas
    public function selectIdAvecCategorie(int|string $id):bool|array{
        $sql = "SELECT voiture.*, categorie.nom AS categorie
                FROM $this->table
                INNER JOIN categorie ON voiture.categorie_id = categorie.id
                WHERE voiture.id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        if($stmt->rowCount() == 1){
            return $stmt->fetch();
        }else{
            return false;
        }
    }

    // Vérifie si une voiture a des locations (on ne peut pas la supprimer dans ce cas)
    public function aDesLocations(int|string $id):bool{
        $sql = "SELECT COUNT(*) FROM location WHERE voiture_id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}