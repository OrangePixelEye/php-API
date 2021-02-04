<?php
namespace Src\Controller;

use Src\TableGateways\AccountsGateway;

class AccountsController {

    private $db;
    private $requestMethod;
    private $userId;
    private $password;

    private $accountsGateway;

    public function __construct($db, $requestMethod, $userId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;
        //$this->password = $password;

        $this->accountsGateway = new AccountsGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getAccount($this->userId);
                } else {
                    $response = $this->getAllAccounts();
                };
                break;
            case 'POST':
                if($this->userId)
                {
                    $response = $this->verifyAccountFromRequest($this->userId);
                }
                else
                {
                    $response = $this->createAccountFromRequest();
                }
                break;
            case 'PUT':
                $response = $this->updateAccountFromRequest($this->userId);
                break;
            case 'DELETE':
                $response = $this->deleteAccount($this->userId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllAccounts()
    {
        $result = $this->accountsGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getAccount($id)
    {
        $result = $this->accountsGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createAccountFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateAccount($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->accountsGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function verifyAccountFromRequest($id)
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $result = $this->accountsGateway->verify($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function updateAccountFromRequest($id)
    {
        $result = $this->accountsGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateAccount($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->accountsGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteAccount($id)
    {
        $result = $this->accountsGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->accountsGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateAccount($input)
    {
        if (! isset($input['password'])) {
            return false;
        }
        if (! isset($input['id_client'])) {
            return false;
        }
        if (! isset($input['type'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}