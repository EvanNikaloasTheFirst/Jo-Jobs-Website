<?php
namespace as2;
class DatabaseTable {
    private $pdo;
    private $table;
    private $primaryKey;

    public function __construct($pdo, $table, $primaryKey) {
        $this->pdo = $pdo;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
    }

    public function find($field, $value) {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE ' . $field . ' = :value');

        $criteria = [
            'value' => $value
        ];
        $stmt->execute($criteria);

        return $stmt->fetchAll();
    }


    public function findAll() {
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function insert($record) {
        $keys = array_keys($record);

        $values = implode(', ', $keys);
        $valuesWithColon = implode(', :', $keys);

        $query = 'INSERT INTO ' . $this->table . ' (' . $values . ') VALUES (:' . $valuesWithColon . ')';

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($record);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare('DELETE FROM ' . $this->table . ' WHERE ' . $this->primaryKey . ' = :id');
        $criteria = [
            'id' => $id
        ];
        $stmt->execute($criteria);
    }


    public function save($record) {
        try {
            if (empty($record[$this->primaryKey])) {
                unset($record[$this->primaryKey]);
            }
            $this->insert($record);
        }
        catch (\Exception $e) {
            $this->update($record);
        }
    }

    public function update($record) {

        $query = 'UPDATE ' . $this->table . ' SET ';

        $parameters = [];
        foreach ($record as $key => $value) {
            $parameters[] = $key . ' = :' .$key;
        }

        $query .= implode(', ', $parameters);
        $query .= ' WHERE ' . $this->primaryKey . ' = :primaryKey';

        $record['primaryKey'] = $record[$this->primaryKey];

        $stmt = $this->pdo->prepare($query);

        $stmt->execute($record);
    }

    function getJobsByCategory() {
        $jobs = $this->pdo->prepare('SELECT j.*, c.name as catName FROM job j JOIN category c ON c.id = j.categoryId WHERE j.categoryId = :categoryId AND Archived = :N');
        $jobs->execute(["categoryId" => $_GET["categoryId"], "N" => 'N']);
        return $jobs->fetchAll();

    }



    function archive($variable){
        $query = $this->pdo->prepare('UPDATE '.$this->table  .' SET '. $variable . " = 'Y' WHERE id = :id");
        $query->execute(["id" => $_GET["id"]]);
    }


    function unarchive($variable){
            $query = $this->pdo->prepare('UPDATE '.$this->table  .' SET '. $variable . " = 'N' WHERE id = :id");
            $query->execute(["id" => $_GET["id"]]);
        }

    function uniqueValues($variable){
        $query = ('SELECT DISTINCT ' . $variable .' FROM ' . $this->table) ;
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findX($variable) {

        $variable = 'location';
        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE '. $variable. '  = :value');

        $criteria = [
            'value' => $_GET[$variable]
        ];
        $stmt->execute($criteria);

        return $stmt->fetchAll();
    }

    public function findOtherJobs($variable,$condition,$variable2) {

        $stmt = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE '. $variable.' '.$condition . '   :value');

        $criteria = [
            'value' => $variable2
        ];
        $stmt->execute($criteria);

        return $stmt->fetchAll();
    }

    function grantAccess($table1,$column){

        $query = $this->pdo->prepare("UPDATE ". $table1 . " SET ". $column . "= 'Y' WHERE id = :id");
        $query->execute(["id" => $_GET["id"]]);
        return $query->fetchAll();
    }

    function removeAccess($table1,$column){

        $query = $this->pdo->prepare("UPDATE ". $table1 . " SET ". $column . "= 'N' WHERE id = :id");
        $query->execute(["id" => $_GET["id"]]);
        return $query->fetchAll();
    }

    function endingSoon($orderBy,$variable1){
        $query = $this->pdo->prepare("SELECT * FROM " .$this->table . " ORDER BY " . $orderBy.  " ". $variable1." LIMIT 10");
        $query->execute();
        return $query->fetchAll();

    }

}
