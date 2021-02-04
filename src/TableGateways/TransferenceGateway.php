<?php
namespace Src\TableGateways;

class TransferenceGateway
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
                *
            FROM
                transference;
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
                *
            FROM
                transference
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
            INSERT INTO transference 
                (amount, origin, receiver)
            VALUES
                (:amount, :origin, :receiver);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'amount' => $input['amount'],
                'origin'  => $input['origin'],
                'receiver' => $input['receiver'] 
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
    
    public function update($id, Array $input)
    {
        $statement = "
            UPDATE transference
            SET 
                amount = :amount
            WHERE id =: id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' =>  $id,
                'amount' => $input['amount']
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM transference
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}
?>