<?php
 
class DbOperation
{
    private $con;
 
    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

	//METHOD : GET
	//RETOURNE LA LISTE DE TOUTES LES PERSONNES
	public function getPersonnes(){
		$stmt = $this->con->prepare("SELECT id, nom, prenom FROM personne");
		$stmt->execute();
		$stmt->bind_result($id, $nom, $prenom);
		$personnes = array();
		
		while($stmt->fetch()){
			$temp = array(); 
			$temp['id'] = $id; 
			$temp['nom'] = $nom; 
			$temp['prenom'] = $prenom; 
			array_push($personnes, $temp);
		}
		return $personnes; 
	}

	//METHOD : POST
	//RETOURNE LE MOT DE PASSE POUR UN IDENTIFIANT DONNÉ
	public function getPassword($identifiant){
		$stmt = $this->con->prepare("SELECT code_secu FROM utilisateur WHERE identifiant = ?");
		$stmt->bind_param("s", $identifiant);
		$stmt->execute();
		$stmt->bind_result($password);
		$passwordResult = array();
		
		while($stmt->fetch()){
			$temp = array(); 
			$temp['password'] = $password; 
			array_push($passwordResult, $temp);
		}
		return $passwordResult; 
	}

	//METHOD : POST
	//RETOURNE LES INFORMATIONS D'UN UTILISATEUR POUR UN IDENTIFIANT DONNÉ
	public function getUser($identifiant) {
		$stmt = $this->con->prepare("SELECT u.id, p.id, p.nom, p.prenom, p.rib FROM utilisateur u, personne p WHERE u.id_personne = p.id AND u.identifiant = ?");
		$stmt->bind_param("s", $identifiant);
		$stmt->execute();
		$stmt->bind_result($user_id, $personne_id, $name, $first_name, $rib);
		$user = array();

		while ($stmt->fetch()) {
			$temp = array();
			$temp['user_id'] = $user_id;
			$temp['personne_id'] = $personne_id;
			$temp['name'] = $name;
			$temp['first_name'] = $first_name;
			$temp['rib'] = $rib;
			array_push($user, $temp);
		}
		return $user;
	}

	//METHOD : POST
    //RETOURNE LE NOMBRE DE COMPTES POUR UN IDENTIFIANT DONNÉ
    public function getAccountsCount($identifiant) {
        $stmt = $this->con->prepare("SELECT COUNT(c.id) FROM compte c, utilisateur u WHERE c.id_utilisateur = u.id AND u.identifiant = ?");
        $stmt->bind_param("s", $identifiant);
        $stmt->execute();
        $stmt->bind_result($accounts_number);
        $accounts_count = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['accounts_count'] = $accounts_number;
            array_push($accounts_count, $temp);
        }
        return $accounts_count;
    }

    //METHOD : POST
    //CRÉE UN COMPTE PAR DÉFAUT INTITULÉ "COMPTE COURANT DE <NOM> <PRENOM>"
    public function createDefaultAccount($identifiant) {
        $user = $this->getUser($identifiant);
        $name = $user[0]['name'];
        $first_name = $user[0]['first_name'];
        $user_id = $user[0]['user_id'];
        $account_name = "Compte courant de " . $first_name . " " . $name;
        $stmt = $this->con->prepare("INSERT INTO compte (nom, solde, id_utilisateur) VALUES (?, 1000, ?)");
        $stmt->bind_param("si", $account_name, $user_id);
        $stmt->execute();
    }

    //METHOD : POST
    //CRÉE UN COMPTE AVEC UN NOM POUR UN IDENTIFIANT DONNÉ
    //TODO : Empêcher la création d'un compte si le nom donné existe déjà
    public function createAccount($user_id, $account_name) {
        $stmt = $this->con->prepare("INSERT INTO compte (nom, id_utilisateur) VALUES (?, ?)");
        $stmt->bind_param("si", $account_name, $user_id);
        $stmt->execute();
    }

    //METHOD : GET
    //RÉCUPÈRE LES INFORMATIONS DE LA CARTE DONNÉE
    //TODO : Revoir à quoi est liée la carte (compte ou utilisateur) => changer le paramètre de la fonction
    public function getCard($account_name) {
        $stmt = $this->con->prepare("SELECT ca.plafond, ca.actif, ca.en_opposition, ca.id_compte FROM carte ca, compte co WHERE co.id = ca.id_compte AND co.nom = ?");
        $stmt->bind_param("s", $account_name);
        $stmt->execute();
        $stmt->bind_result($ceiling, $locked_status, $opposition_status, $account_id);
        $card = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['ceiling'] = $ceiling;
            $temp['locked_status'] = $locked_status;
            $temp['opposition_status'] = $opposition_status;
            $temp['account_id'] = $account_id;
            array_push($card, $temp);
        }
        return $card;
    }

    //METHOD : POST
    //VERROUILLE / DÉVEROUILLE LA CARTE POUR UN COMPTE DONNÉ
    //TODO : Revoir à quoi est liée la carte (compte ou utilisateur) => changer le paramètre de la fonction
    public function switchCardLockedStatus($account_name) {
        $locked_status = $this->getCard($account_name)[0]['locked_status'];
        $account_id = $this->getCard($account_name)[0]['account_id'];
        $set_locked_status = 0;
        if ($locked_status == 0) {
            $set_locked_status = 1;
        }
        $stmt = $this->con->prepare("UPDATE carte SET actif = ? WHERE id_compte = ?");
        $stmt->bind_param("ii", $set_locked_status, $account_id);
        $stmt->execute();
    }

    //METHOD : POST
    //CHANGE L'ÉTAT "EN OPPOSITION" DE LA CARTE POUR UN COMPTE DONNÉ
    //TODO : Revoir à quoi est liée la carte (compte ou utilisateur) => changer le paramètre de la fonction
    public function switchCardOppositionStatus($account_name) {
        $opposition_status = $this->getCard($account_name)[0]['opposition_status'];
        $account_id = $this->getCard($account_name)[0]['account_id'];
        $set_opposition_status = 0;
        if ($opposition_status == 0) {
            $set_opposition_status = 1;
        }
        $stmt = $this->con->prepare("UPDATE carte SET en_opposition = ? WHERE id_compte = ?");
        $stmt->bind_param("ii", $set_opposition_status, $account_id);
        $stmt->execute();
    }

    //METHOD : POST
    //CHANGE LE PLAFOND DE LA CARTE POUR UN COMPTE DONNÉ
    //TODO : Revoir à quoi est liée la carte (compte ou utilisateur) => changer le paramètre de la fonction
    public function setCardCeiling($ceiling, $account_name) {
        $account_id = $this->getCard($account_name)[0]['account_id'];
        $stmt = $this->con->prepare("UPDATE carte SET plafond = ? WHERE id_compte = ?");
        $stmt->bind_param("ii", $ceiling, $account_id);
        $stmt->execute();
    }
}

