<?php
require_once('model_connect_class.php');

class userDatabase
    {
        private $bdd;


        public function __construct()
        {
            $bdd = new Database('socialmedia');
            $this->bdd = $bdd->getter();
        }

        public function updateUtilisateur($nom, $prenom, $age, $date_naissance, $id)
        {
            $req = $this->bdd->prepare("UPDATE utilisateur SET nom= :nom, prenom= :prenom, age= :age, date_naissance= :date_naissance WHERE id= :id");
            $req->bindParam(":nom", $nom);
            $req->bindParam(":prenom", $prenom);
            $req->bindParam(":age", $age);
            $req->bindParam(":date_naissance", $date_naissance);
            $req->bindParam(":id", $id);
            $req->execute();
        }

        public function isFriend($id, $user) {
            if ($id == $user)
                return false;
            $req = $this->bdd->prepare("SELECT * FROM utilisateur WHERE id= :id");
            $req->bindParam(":id", $id);
            $req->execute();
            $reponse = $req->fetch(PDO::FETCH_OBJ);
            if (strstr($reponse['amis'], "," . $user . ","))
                return true;
            else
                return false;
        }

        public function getWall($id) {
            $req = $this->bdd->prepare("SELECT * FROM mur WHERE id_mur= :id ORDER BY id DESC");
            $req->bindParam(":id", $id);
            $req->execute();
            $reponses = $req->fetchAll(PDO::FETCH_ASSOC);
            return $reponses;
        }

        public function insertWall($to, $message, $from) {
            $req = $this->bdd->prepare("INSERT INTO mur SET id_mur = :mur, id_utilisateur= :utilisateur, message = :message, heure= NOW(), id_message = NULL ");
            $req->bindParam(":mur", $to);
            $req->bindParam(":utilisateur", $from);
            $req->bindParam(":message", $message);
            $req->execute();
        }
        
        public function getUserById($id) {
            $req = $this->bdd->prepare("SELECT * FROM utilisateur WHERE id= :id");
            $req->bindParam(":id", $id);
            $req->execute();
            $reponse = $req->fetch(PDO::FETCH_OBJ);
            return $reponse;
        }
        
        public function findFriends($id) {
            $req = $this->bdd->prepare("SELECT * FROM utilisateur WHERE id= :id");
            $req->bindParam(":id", $id);
            $req->execute();            
            $reponse = $req->fetch(PDO::FETCH_OBJ);
            $friends = $reponse['amis'];
            if ($friends == "")
                return false;
            else {
                $friendlist = explode(",", $friends);
                $usertab = [];
                $i = 0;
                foreach ($friendlist as $friend) {
                    $usertab[$i] = $this->getUserById($friend);
                    $i = $i + 1;
                }
                return $usertab;
            }
        }

        public function searchUser($search) {
            $req = $this->bdd->prepare("SELECT * FROM utilisateur WHERE email LIKE '%". $search ."%' OR nom LIKE '%". $search ."%' OR prenom LIKE '%". $search ."%' OR pays LIKE '%". $search ."%' OR ville LIKE '%". $search ."%'");
            $req->execute();
            $reponses = $req->fetchAll(PDO::FETCH_OBJ);
            return $reponses;            
        }

        public function login($username, $password){
             $req = $this->bdd->prepare('SELECT * FROM compte WHERE username = :username');
             $req->execute([':username' => $username]);
             $user= $req->fetch();

             if(password_verify($password, $user['password'])){
                 return $user;
             }
             else {
                 return false;
             }

        }

        public function manageEmail($id, $email){
            $req = $this->bdd->prepare('UPDATE utilisateur SET email= :email WHERE id= :id');
            $req->bindParam(':id', $id);
            $req->bindParam(':email', $email);
            $req->execute();
        }

        public function manageDate_naissance($id, $date_naissance){
            $req = $this->bdd->prepare('UPDATE utilisateur SET date_naissance= :date_naissance WHERE id= :id');
            $req->bindParam(':id', $id);
            $req->bindParam(':date_naissance', $date_naissance);
            $req->execute();
        }

        public function managePays($id, $pays){
            $req = $this->bdd->prepare('UPDATE utilisateur SET pays= :pays WHERE id= :id');
            $req->bindParam(':id', $id);
            $req->bindParam(':pays', $pays);
            $req->execute();
        }

        public function manageVille($id, $ville){
            $req = $this->bdd->prepare('UPDATE utilisateur SET ville= :ville WHERE id= :id');
            $req->bindParam(':id', $id);
            $req->bindParam(':ville', $ville);
            $req->execute();
        }

        public function manageDescription($id, $description){
            $req = $this->bdd->prepare('UPDATE utilisateur SET description= :description WHERE id= :id');
            $req->bindParam(':id', $id);
            $req->bindParam(':description', $description);
            $req->execute();
        }

    }







