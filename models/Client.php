<?php
namespace App\Models;
use App\Models\CRUD;

/**
 * Modèle Client : table client.
 * Le courriel est unique. Un client peut avoir plusieurs locations (relation 1-N avec location).
 */
class Client extends CRUD{
    protected $table = 'client';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'prenom', 'courriel', 'telephone'];

    // Vérifie si un courriel est déjà utilisé par un autre client (courriel unique).
    // $idExclu : l'id du client qu'on modifie, pour ne pas le compter lui-même.
    public function courrielExiste(string $courriel, int|string $idExclu = 0):bool{
        $sql = "SELECT COUNT(*) FROM $this->table WHERE courriel = :courriel AND id != :id";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":courriel", $courriel);
        $stmt->bindValue(":id", $idExclu);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // Vérifie si un client a des locations (on ne peut pas le supprimer dans ce cas)
    public function aDesLocations(int|string $id):bool{
        $sql = "SELECT COUNT(*) FROM location WHERE client_id = :id";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}