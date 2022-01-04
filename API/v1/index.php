<?php 

	require_once '../includes/DbOperation.php';
	
	$response = array(); 
	
	if(isset($_GET['op'])){
		
		switch($_GET['op']){
			
			case 'getPersonnes':
				$db = new DbOperation();
				$personne = $db->getPersonnes();
				if (count($personne) <= 0 ) {
					$response['error'] = true; 
					$response['message'] = 'No results';
				} else {
					$response['error'] = false; 
					$response['personnes'] = $personne;
				}
			    break;

			case 'getPassword':
				if (isset($_POST['identifiant'])) {
					$db = new DbOperation();
					$password = $db->getPassword($_POST['identifiant']);
					if ($password) {
						if (count($password) <= 0 ) {
							$response['error'] = true; 
							$response['message'] = 'No results';
						} else {
							$response['error'] = false; 
							$response['personnes'] = $password;
						}
					} 
				} else {
					$response['error'] = true; 
					$response['message'] = 'Missing required parameters';
				}			
			    break;

            case 'getUser':
                if (isset($_POST['identifiant'])) {
                    $db = new DbOperation();
                    $user = $db->getUser($_POST['identifiant']);
                    if (count($user) <= 0) {
                        $response['error'] = true;
                        $response['message'] = 'No results';
                    } else {
                        $response['error'] = false;
                        $response['user'] = $user;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'getAccountsCount':
                if (isset($_POST['identifiant'])) {
                    $db = new DbOperation();
                    $accounts_count = $db->getAccountsCount($_POST['identifiant']);
                        if (count($accounts_count) <= 0 ) {
                            $response['error'] = true;
                            $response['message'] = 'No results';
                        } else {
                            $response['error'] = false;
                            $response['accounts_count'] = $accounts_count;
                        }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'createDefaultAccount':
                if (isset($_POST['identifiant'])) {
                    $db = new DbOperation();
                    $db->createDefaultAccount($_POST['identifiant']);
                    $response['error'] = false;
                    $response['message'] = 'Default account created successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'createAccount':
                if (isset($_POST['user_id']) && isset($_POST['account_name'])) {
                    $db = new DbOperation();
                    $db->createAccount($_POST['user_id'], $_POST['account_name']);
                    $response['error'] = false;
                    $response['message'] = 'Account created successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'getCard':
                if (isset($_POST['account_name'])) {
                    $db = new DbOperation();
                    $card = $db->getCard($_POST['account_name']);
                    if (count($card) <= 0) {
                        $response['error'] = false;
                        $response['message'] = 'No results';
                    } else {
                        $response['error'] = false;
                        $response['message'] = $card;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'switchCardLockedStatus':
                if (isset($_POST['account_name'])) {
                    $db = new DbOperation();
                    $db->switchCardLockedStatus($_POST['account_name']);
                    $response['error'] = false;
                    $response['message'] = 'Locked status changed successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'switchCardOppositionStatus':
                if (isset($_POST['account_name'])) {
                    $db = new DbOperation();
                    $db->switchCardOppositionStatus($_POST['account_name']);
                    $response['error'] = false;
                    $response['message'] = 'Opposition status changed successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'setCardCeiling':
                if (isset($_POST['ceiling']) && isset($_POST['account_name'])) {
                    $db = new DbOperation();
                    $db->setCardCeiling($_POST['ceiling'], $_POST['account_name']);
                    $response['error'] = false;
                    $response['message'] = 'Card ceiling set to ' . $_POST['ceiling'];
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            default:
				$response['error'] = true;
				$response['message'] = 'No operation to perform';
			
		}
		
	}else{
		$response['error'] = false; 
		$response['message'] = 'Invalid Request';
	}
	
	echo json_encode($response);