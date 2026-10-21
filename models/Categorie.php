<?php
namespace App\Models;
use App\Models\CRUD;

/**
 * Modèle Categorie : table categorie (Économique, VUS, Luxe...).
 * Utilisé pour le menu déroulant des voitures (relation 1-N avec voiture).
 */
class Categorie extends CRUD{
    protected $table = 'categorie';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'description'];
}