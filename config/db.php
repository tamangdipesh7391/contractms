<?php

class Database
{
    private $_connection = null;
    private static $_instance = null;

    public function __construct()
    {
        $this->Connection();
    }

    private function Connection()
    {
        $this->_connection = new PDO('mysql:host=127.0.0.1;dbname=cms', 'root', '');
        // $this->_connection = new PDO('mysql:host=127.0.0.1;dbname=mounteve_wp', 'mounteve_wp', 'srijan');

        $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    }

    public static function Instance()
    {
        if (!isset(self::$_instance)) {
            return self::$_instance = new Database();
        } else {
            return self::$_instance;
        }

    }

    public function Insert($tableName = '', $data = array())
    {
        if (empty($tableName) || empty($data)) throw new PDOException('Table name and data is required');

        $columns = implode(',', array_keys($data));
        $setColumns = '';
        $increment = 1;
        foreach ($data as $value) {
            $setColumns .= '?';
            if ($increment < count($data)) {
                $setColumns .= ",";
            }
            $increment++;
        }
        $query = "INSERT INTO {$tableName}($columns) VALUES($setColumns)";
        // echo $query;

        $preStatement = $this->_connection->prepare($query);
        // echo $preStatement;
        try {
           $preStatement->execute(array_values($data));
            return $this->_connection->lastInsertId();
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
        return  $this->_connection->lastInsertId();
    }

    public function Update($tableName = '', $data = array(), $criteria = '', $bindValue = array())
    {
        // print_r($data);
        $setKey = '';
        $increment = 1;
        foreach ($data as $key => $value) {
            $setKey .= $key . '=?';
            if ($increment < count($data)) {
                $setKey .= ',';
            }
            $increment++;
        }
        // print_r($setKey);
        $query = "UPDATE {$tableName} SET $setKey WHERE $criteria=?";
        // echo $query;
        $preStatement = $this->_connection->prepare($query);
        $mergeData = array_merge(array_values($data), $bindValue);

        try {
            return $preStatement->execute($mergeData);
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
        return  $this->_connection->lastInsertId();
    }

    public function Delete($tableName = '', $criteria = '', $bindValue = array())
    {
        $query = "DELETE FROM {$tableName} WHERE {$criteria}=?";
        $preStatement = $this->_connection->prepare($query);
        try {
            return $preStatement->execute($bindValue);
        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
    }
    public function Select($tableName = '', $column = '*', $criteria = '', $bindValue = array(), $clause = '')
    {
        if (empty($column)) {
            $column .= "*";
        }

        $query = "SELECT {$column} FROM {$tableName}";
        if (!empty($criteria)) {
            $query .= " WHERE {$criteria}=?";
        }

        if (!empty($clause)) {
            $query .= " {$clause}";
        }
// echo $query; 
        $preStatement = $this->_connection->prepare($query);
        try {
            $preStatement->execute($bindValue);
            return $preStatement->fetchAll(PDO::FETCH_ASSOC);


        } catch (PDOException $exception) {
            die($exception->getMessage());
        }
        

    }
      public function Query($query)
    {
    //   echo $query;

        $preStatement = $this->_connection->prepare($query);
        try {
            $preStatement->execute([]);
            return $preStatement->fetchAll(PDO::FETCH_CLASS);


        } catch (PDOException $exception) {
            die($exception->getMessage());
        }


    }
    public function ArrQuery($query)
    {
    //   echo $query;

        $preStatement = $this->_connection->prepare($query);
        try {
            $preStatement->execute([]);
            return $preStatement->fetchAll(PDO::FETCH_ASSOC);


        } catch (PDOException $exception) {
            die($exception->getMessage());
        }


    }
    
    
}
$obj=Database::Instance();


?>
