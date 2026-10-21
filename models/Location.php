<?php
namespace App\Models;
use App\Models\CRUD;

/**
 * Modèle Location : table location.
 * Table de liaison de la relation N-N entre client et voiture.
 */
class Location extends CRUD{
    protected $table = 'location';
    protected $primaryKey = 'id';
    protected $fillable = ['client_id', 'voiture_id', 'date_debut', 'date_fin'];

    // Retourne toutes les locations avec le client et la voiture (double jointure)
    // La durée en jours calculée par MySQL (DATEDIFF) et le prix par jour de la voiture
    public function selectAvecDetails():array{
        $sql = "SELECT location.*,
                       client.prenom, client.nom,
                       voiture.marque, voiture.modele, voiture.prix_jour,
                       DATEDIFF(location.date_fin, location.date_debut) AS jours
                FROM $this->table
                INNER JOIN client ON location.client_id = client.id
                INNER JOIN voiture ON location.voiture_id = voiture.id
                ORDER BY location.date_debut DESC";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }
}