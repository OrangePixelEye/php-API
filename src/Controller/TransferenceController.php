<?php
namespace Src\Controller;

use Src\TableGateways\TransferenceGateway;

class TransferenceController {

    private $db;
    private $requestMethod;
    private $userId;

    private $transferenceGateway;

    public function __construct($db, $requestMethod, $userId)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->userId = $userId;

        $this->transferenceGateway = new TransferenceGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->userId) {
                    $response = $this->getTransference($this->userId);
                } else {
                    $response = $this->getAllTransferences();
                };
                break;
            case 'POST':
                $response = $this->createTransferenceFromRequest();
                break;
            case 'PUT':
                $response = $this->updateTransferenceFromRequest($this->userId);
                break;
            case 'DELETE':
                $response = $this->deleteTransference($this->userId);
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

    private function getAllTransferences()
    {
        $result = $this->transferenceGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getTransference($id)
    {
        $result = $this->transferenceGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createTransferenceFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateTranference($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->transferenceGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateTransferenceFromRequest($id)
    {
        $result = $this->transferenceGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateTranference($input)) {
            return $this->unprocessableEntityResponse();
        }
        $this->transferenceGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteTransference($id)
    {
        $result = $this->transferenceGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->transferenceGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateTranference($input)
    {
        if (! isset($input['origin'])) {
            return false;
        }
        if (! isset($input['receiver'])) {
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