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

    //METHOD : AUCUNE (FONCTION INTERNE)
    //RETOURNE LES INFORMATIONS D'UN UTILISATEUR POUR SON ID
    public function getUserFromId($user_id) {
        $stmt = $this->con->prepare("SELECT p.id, p.nom, p.prenom, p.rib FROM utilisateur u, personne p WHERE u.id_personne = p.id AND u.id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($personne_id, $name, $first_name, $rib);
        $user = array();

        while ($stmt->fetch()) {
            $temp = array();
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
    //RETOURNE TOUS LES COMPTES POUR UN UTILISATEUR DONNÉ
    public function getAccounts($user_id) {
        $stmt = $this->con->prepare("SELECT c.id, c.nom, c.solde, c.id_utilisateur FROM compte c, utilisateur u WHERE c.id_utilisateur = u.id AND u.id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($account_id, $account_name, $account_amount, $account_user_id);
        $accounts = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['account_id'] = $account_id;
            $temp['account_name'] = $account_name;
            $temp['account_amount'] = $account_amount;
            $temp['account_user_id'] = $account_user_id;
            array_push($accounts, $temp);
        }
        return $accounts;
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

    //METHOD : AUCUNE (FONCTION INTERNE)
    //RÉCUPÈRE LE SOLDE POUR UN COMPTE DONNÉ
    public function getAccountAmount($account_id) {
        $stmt = $this->con->prepare("SELECT solde FROM compte WHERE id = ?");
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $stmt->bind_result($amountResult);
        $amount = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['amount'] = $amountResult;
            array_push($amount, $temp);
        }
        return $amount;
    }

    //METHOD : POST
    //EFFECTUE UN VIREMENT D'UN COMPTE VERS UNE PERSONNE
    public function doTransaction($amount, $person_id, $account_id, $intern_account_id) {
        $account_amount = $this->getAccountAmount($account_id)[0]['amount'];
        if ($amount < $account_amount) {
            $date = date("Y-m-d");
            if ($intern_account_id == null) {
                $stmt = $this->con->prepare("INSERT INTO transaction (montant, date, id_personne, id_compte) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $amount, $date, $person_id, $account_id);
                $stmt->execute();
                $this->removeAmount($amount, $account_id);
                $this->addAmount($amount, $person_id);
            } else {
                $stmt = $this->con->prepare("INSERT INTO transaction (montant, date, id_compte, id_compte_interne) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isii", $amount, $date, $account_id, $intern_account_id);
                $stmt->execute();
                $this->removeAmount($amount, $account_id);
                $this->addInternalAmount($amount, $intern_account_id);
            }
            return true;
        } else {
            return false;
        }
    }

    //METHOD : AUCUNE (FONCTION INTERNE)
    //RETIRE L'ARGENT DU COMPTE CRÉDITÉ
    public function removeAmount($amount, $account_id) {
        $stmt = $this->con->prepare("UPDATE compte SET solde = solde - ? WHERE id = ?");
        $stmt->bind_param("ii", $amount, $account_id);
        $stmt->execute();
    }

    //METHOD : AUCUNE (FONCTION INTERNE)
    //RÉCUPÈRE L'ID DU USER DE LA PERSONNE DONNÉE (RENVOIE 0 SI LE USER N'EXISTE PAS)
    public function getUserId($person_id) {
        $stmt = $this->con->prepare("SELECT IFNULL((SELECT u.id FROM utilisateur u, personne p WHERE u.id_personne = p.id AND p.id = ?), 0) as user");
        $stmt->bind_param("i", $person_id);
        $stmt->execute();
        $stmt->bind_result($user_id_result);
        $user_id = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['user_id'] = $user_id_result;
            array_push($user_id, $temp);
        }
        return $user_id;
    }

    //METHOD : AUCUNE (FONCTION INTERNE)
    //RETOURNE LE COMPTE COURANT POUR UN USER ID DONNÉ
    public function getDefaultAccount($user_id) {
        $user = $this->getUserFromId($user_id);
        $account_name = "Compte courant de " . $user[0]['first_name'] . " " . $user[0]['name'];
        $stmt = $this->con->prepare("SELECT id, solde FROM compte WHERE nom = ?");
        $stmt->bind_param("s", $account_name);
        $stmt->execute();
        $stmt->bind_result($account_id, $account_amount);
        $account = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['account_id'] = $account_id;
            $temp['account_amount'] = $account_amount;
            array_push($account, $temp);
        }
        return $account;
    }

    //METHOD : AUCUNE (FONCTION INTERNE)
    //AJOUTE L'ARGENT AU COMPTE
    public function addAmount($amount, $person_id) {
        $user_id = $this->getUserId($person_id)[0]['user_id'];
        if ($user_id != 0) {
            $account_id = $this->getDefaultAccount($user_id)[0]['account_id'];
            $stmt = $this->con->prepare("UPDATE compte SET solde = solde + ? WHERE id = ?");
            $stmt->bind_param("ii", $amount, $account_id);
            $stmt->execute();
        }
    }

    //METHOD : AUCUNE (FONCTION INTERNE)
    //AJOUTE L'ARGENT À UN COMPTE INTERNE
    public function addInternalAmount($amount, $inter_account_id) {
        $stmt = $this->con->prepare("UPDATE compte SET solde = solde + ? WHERE id = ?");
        $stmt->bind_param("ii", $amount, $inter_account_id);
        $stmt->execute();
    }

    //METHOD : POST
    //AFFICHE LA LISTE DE TOUTES LES TRANSACTIONS SORTANTES (PAIEMENT) D'UN COMPTE
    public function getTransaction($account_id) {
        $stmt = $this->con->prepare("SELECT t.montant, t.date, t.id_personne FROM transaction t, compte c WHERE t.id_compte = c.id AND c.id = ?");
        $stmt->bind_param("i", $account_id);
        $stmt->execute();
        $stmt->bind_result($montant, $date, $person_id);
        $transactions = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['montant'] = $montant;
            $temp['date'] = $date;
            $temp['person_id'] = $person_id;
            array_push($transactions, $temp);
        }
        return $transactions;
    }

    //METHOD : POST
    //RETOURNE LA LISTE DES BÉNÉFICIAIRES POUR UN USER DONNÉ
    public function getBeneficiaires($user_id) {
        $stmt = $this->con->prepare("SELECT p.nom, p.prenom, p.rib FROM personne p, utilisateur u, beneficiaires b WHERE b.id_personne = p.id AND b.id_utilisateur = u.id AND u.id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($name, $first_name, $rib);
        $beneficiaires = array();
        while($stmt->fetch()){
            $temp = array();
            $temp['name'] = $name;
            $temp['first_name'] = $first_name;
            $temp['rib'] = $rib;
            array_push($beneficiaires, $temp);
        }
        return $beneficiaires;
    }

    //METHOD : AUCUNE (FONCTION INTERNE)
    //RÉCUPÈRE L'ID DE LA PERSONNE POUR LES INFORMATIONS DONNÉES (RENVOIE 0 SI LA PERSONNE N'EXISTE PAS)
    public function getPersonFromData($name, $first_name, $rib) {
        $stmt = $this->con->prepare("SELECT IFNULL((SELECT id FROM personne WHERE nom = ? AND prenom = ? AND rib = ?), 0) as user");
        $stmt->bind_param("sss", $name, $first_name, $rib);
        $stmt->execute();
        $stmt->bind_result($person_id_result);
        $person_id = array();

        while($stmt->fetch()){
            $temp = array();
            $temp['person_id'] = $person_id_result;
            array_push($person_id, $temp);
        }
        return $person_id;
    }

    //METHOD : POST
    //AJOUTE UN BENEFICIAIRE POUR UN USER DONNÉ
    public function addBeneficiaire($user_id, $name, $first_name, $rib) {
        $personne = $this->getPersonFromData($name, $first_name, $rib);
        if ($personne[0]['person_id'] == 0) {
            $this->createPersonne($name, $first_name, $rib);
            $personne = $this->getPersonFromData($name, $first_name, $rib);
        }
        $stmt = $this->con->prepare("INSERT INTO beneficiaires (id_personne, id_utilisateur) VALUES (?, ?)");
        $stmt->bind_param("ii", $personne[0]['person_id'], $user_id);
        $stmt->execute();
    }

    //METHOD : AUCUNE (FONCTION INTERNE)
    //CRÉE UNE PERSONNE
    public function createPersonne($name, $first_name, $rib) {
        $stmt = $this->con->prepare("INSERT INTO personne (nom, prenom, rib) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $first_name, $rib);
        $stmt->execute();
    }

    //METHOD : POST
    //SUPPRIME UN BENEFICIAIRE
    public function deleteBeneficiaire($person_id, $user_id) {
        $stmt = $this->con->prepare("DELETE FROM beneficiaires WHERE id_personne = ? AND id_utilisateur = ?");
        $stmt->bind_param("ii", $person_id, $user_id);
        $stmt->execute();
    }
}

