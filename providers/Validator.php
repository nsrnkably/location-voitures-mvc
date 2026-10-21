<?php
namespace App\Providers;

/**
 * Classe Validator
 * Valide les données d'un formulaire avant leur enregistrement.
 * Utilisation (chaînage) : $val->field('marque', $data['marque'], 'La marque')->required()->max(45);
 * Pour chaque champ, seule la première erreur trouvée est conservée.
 */
class Validator {

    private $errors = array();
    private string $key;
    private string $value;
    private string $name;

    // Choisit le champ à valider. $name : nom affiché dans le message d'erreur.
    public function field(string $key, string $value, $name = null){
        $this->key = $key;
        $this->value = trim($value);
        if($name == null){
            $this->name = ucfirst($key);
        }else{
            $this->name = ucfirst($name);
        }
        return $this;
    }

    // Enregistre une erreur pour le champ courant (seulement s'il n'en a pas déjà une)
    private function addError(string $message){
        if(!isset($this->errors[$this->key])){
            $this->errors[$this->key] = $message;
        }
    }

    // ---------- Règles ----------

    // Le champ est obligatoire
    public function required(){
        if($this->value === ''){
            $this->addError("$this->name est obligatoire.");
        }
        return $this;
    }

    // Nombre minimum de caractères (mb_strlen compte correctement les accents)
    public function min(int $length){
        if($this->value !== '' && mb_strlen($this->value) < $length){
            $this->addError("$this->name doit contenir au moins $length caractères.");
        }
        return $this;
    }

    // Nombre maximum de caractères
    public function max(int $length){
        if(mb_strlen($this->value) > $length){
            $this->addError("$this->name doit contenir au maximum $length caractères.");
        }
        return $this;
    }

    // Nombre entier
    public function int(){
        if($this->value !== '' && filter_var($this->value, FILTER_VALIDATE_INT) === false){
            $this->addError("$this->name doit être un nombre entier.");
        }
        return $this;
    }

    // Nombre (entier ou décimal)
    public function number(){
        if($this->value !== '' && !is_numeric($this->value)){
            $this->addError("$this->name doit être un nombre.");
        }
        return $this;
    }

    // Valeur minimum (pour un nombre)
    public function valeurMin($min){
        if(is_numeric($this->value) && $this->value < $min){
            $this->addError("$this->name doit être d'au moins $min.");
        }
        return $this;
    }

    // Valeur maximum (pour un nombre)
    public function valeurMax($max){
        if(is_numeric($this->value) && $this->value > $max){
            $this->addError("$this->name ne doit pas dépasser $max.");
        }
        return $this;
    }

    // Adresse courriel valide
    public function email(){
        if($this->value !== '' && !filter_var($this->value, FILTER_VALIDATE_EMAIL)){
            $this->addError("$this->name n'a pas un format valide.");
        }
        return $this;
    }

    // Date valide au format AAAA-MM-JJ
    public function date($format = 'Y-m-d'){
        if($this->value !== ''){
            $date = \DateTime::createFromFormat($format, $this->value);
            if(!$date || $date->format($format) !== $this->value){
                $this->addError("$this->name n'est pas une date valide.");
            }
        }
        return $this;
    }

    // Ajoute une erreur personnalisée (ex. : une règle vérifiée dans le contrôleur)
    public function custom(string $key, string $message){
        $this->key = $key;
        $this->addError($message);
        return $this;
    }

    // ---------- Résultat ----------

    // true s'il n'y a aucune erreur
    public function isSuccess(){
        return empty($this->errors);
    }

    // Retourne le tableau des erreurs ['champ' => 'message']
    public function getErrors(){
        return $this->errors;
    }
}