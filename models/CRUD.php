<?php
namespace App\Models;

/**
 * Classe CRUD (abstraite)
 * Classe parente de tous les modèles. Elle hérite de PDO :
 * elle ouvre la connexion à la base de données et contient les méthodes communes.
 * Chaque modèle enfant définit $table, $primaryKey et $fillable.
 */
abstract class CRUD extends \PDO {

    // Propriétés définies dans chaque modèle enfant (Voiture, Client...)
    protected $table;
    protected $primaryKey;
    protected $fillable = [];

    // Connexion à la base de données
    final public function __construct(){
        parent::__construct('mysql:host=localhost; dbname=location_voitures; port=3306; charset=utf8', 'root', 'admin');
    }

    // Retourne toutes les lignes de la table, triées (par défaut selon la clé primaire)
    final public function select($field = null, $order = "ASC"):array{
        if($field == null){
            $field = $this->primaryKey;
        }
        $sql = "SELECT * FROM $this->table ORDER BY $field $order";
        $stmt = $this->query($sql);
        return $stmt->fetchAll();
    }

    // Retourne une seule ligne selon la clé primaire, ou false si elle n'existe pas
    final public function selectId(int|string $value):bool|array{
        $sql = "SELECT * FROM $this->table WHERE $this->primaryKey = :$this->primaryKey";
        $stmt = $this->prepare($sql);
        $stmt->bindValue(":$this->primaryKey", $value);
        $stmt->execute();
        $count = $stmt->rowCount();
        if($count == 1){
            return $stmt->fetch();
        }else{
            return false;
        }
    }

    // Ajoute une ligne. Seuls les champs listés dans $fillable sont enregistrés.
    // Retourne l'id de la nouvelle ligne, ou false en cas d'erreur.
    final public function insert(array $data){
        $data_keys = array_fill_keys($this->fillable, '');
        $data = array_intersect_key($data, $data_keys);

        $fieldName = implode(', ', array_keys($data));
        $fieldBindValue = ":".implode(', :', array_keys($data));
        $sql = "INSERT INTO $this->table ($fieldName) VALUES ($fieldBindValue);";
        $stmt = $this->prepare($sql);

        foreach($data as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }
        if($stmt->execute()){
            return $this->lastInsertId();
        }else{
            return false;
        }
    }

    // Modifie la ligne $id. Seuls les champs listés dans $fillable sont modifiés.
    // Retourne true si la modification a fonctionné, false sinon.
    final public function update(array $data, $id){
        $data_keys = array_fill_keys($this->fillable, '');
        $data = array_intersect_key($data, $data_keys);
        $data[$this->primaryKey] = $id;

        $fieldName = null;
        foreach($data as $key=>$value){
            $fieldName .= "$key = :$key, ";
        }
        $fieldName = rtrim($fieldName, ', ');
        $sql = "UPDATE $this->table SET $fieldName WHERE $this->primaryKey = :$this->primaryKey;";

        $stmt = $this->prepare($sql);
        foreach($data as $key=>$value){
            $stmt->bindValue(":$key", $value);
        }
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    // Supprime la ligne $id si elle existe.
    // Retourne true si la suppression a fonctionné, false sinon.
    final public function delete($id){
        if($this->selectId($id)){
            $sql = "DELETE FROM $this->table WHERE $this->primaryKey = :$this->primaryKey";
            $stmt = $this->prepare($sql);
            $stmt->bindValue(":$this->primaryKey", $id);
            if($stmt->execute()){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}