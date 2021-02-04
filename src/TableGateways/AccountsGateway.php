<?php
namespace Src\TableGateways;

class AccountsGateway
{
    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, id_client, money, type
            FROM
                accounts;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, id_client, money, type
            FROM
                accounts
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO accounts 
                (id_client, password, money, type)
            VALUES
                (:id_client, :password, :money, :type);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id_client' => $input['id_client'],
                'password'  => $input['password'],
                'money' => $input['money'] ?? null,
                'type' => $input['type']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function verify($id, Array $input)
    {
        $statement = "
            SELECT 
                id, id_client, money, type
            FROM
                accounts
            WHERE id = :id AND password = :password;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int)$id,
                'password'  => (int)$input['password']
            ));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            //$statement->debugDumpParams();
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }   
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE accounts
            SET 
                password = :password,
                money = :money
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'password' => $input['password'],
                'money'  => $input['money']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM accounts
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}
?>