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
                if (isset($_POST['user_id'])) {
                    $db = new DbOperation();
                    $card = $db->getCard($_POST['user_id']);
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

            case 'switchCardDistanceStatus':
                if (isset($_POST['account_name'])) {
                    $db = new DbOperation();
                    $db->switchCardDistanceStatus($_POST['account_name']);
                    $response['error'] = false;
                    $response['message'] = 'Distance status changed successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'switchCardForeignStatus':
                if (isset($_POST['account_name'])) {
                    $db = new DbOperation();
                    $db->switchCardForeignStatus($_POST['account_name']);
                    $response['error'] = false;
                    $response['message'] = 'Foreign status changed successfully';
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

            case 'getTransaction':
                if (isset($_POST['account_id'])) {
                    $db = new DbOperation();
                    $transactions = $db->getTransaction($_POST['account_id']);
                    if (count($transactions) <= 0) {
                        $response['error'] = false;
                        $response['message'] = 'No results';
                    } else {
                        $response['error'] = false;
                        $response['message'] = $transactions;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'doTransaction':
                if (isset($_POST['amount']) && isset($_POST['account_id'])) {
                    $db = new DbOperation();
                    if (!isset($_POST['intern_account_id'])) {
                        $transaction = $db->doTransaction($_POST['amount'], $_POST['person_id'], $_POST['account_id'], null);
                    } else {
                        $transaction = $db->doTransaction($_POST['amount'], null, $_POST['account_id'], $_POST['intern_account_id']);
                    }
                    if ($transaction == true) {
                        $response['error'] = false;
                        $response['message'] = 'Transaction done successfully';
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'Not enough money on the account';
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'getBeneficiaires':
                if (isset($_POST['user_id'])) {
                    $db = new DbOperation();
                    $beneficiaires = $db->getBeneficiaires($_POST['user_id']);
                    if (count($beneficiaires) <= 0) {
                        $response['error'] = false;
                        $response['message'] = 'No results';
                    } else {
                        $response['error'] = false;
                        $response['message'] = $beneficiaires;
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'addBeneficiaire':
                if (isset($_POST['user_id']) && isset($_POST['name']) && isset($_POST['first_name']) && isset($_POST['rib'])) {
                    $db = new DbOperation();
                    $db->addBeneficiaire($_POST['user_id'], $_POST['name'], $_POST['first_name'], $_POST['rib']);
                    $response['error'] = false;
                    $response['message'] = 'Beneficiaire created successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'deleteBeneficiaire':
                if (isset($_POST['person_id']) && isset($_POST['user_id'])) {
                    $db = new DbOperation();
                    $db->deleteBeneficiaire($_POST['person_id'], $_POST['user_id']);
                    $response['error'] = false;
                    $response['message'] = 'Beneficiaire deleted successfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Missing required parameters';
                }
                break;

            case 'getAccounts':
                if (isset($_POST['user_id'])) {
                    $db = new DbOperation();
                    $accounts = $db->getAccounts($_POST['user_id']);
                    if (count($accounts) <= 0) {
                        $response['error'] = false;
                        $response['message'] = 'No results';
                    } else {
                        $response['error'] = false;
                        $response['message'] = $accounts;
                    }
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