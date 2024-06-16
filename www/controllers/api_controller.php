<?php
include_once "controller_base.php";

class ApiController extends BaseController
{
    public function do_get() 
    {
        global $action, $id;

        switch ($action) {
            case 'shop' : switch($id) {
                case 'install' : return $this->shop_install();
                case 'dbs' : return $this->shop_databases();
            }
        }

        $this->send_json(null, 404, 'path not released');
    }

    private function shop_install()
    {
        $db = $this->get_db_or_exit();

        $query = "CREATE TABLE IF NOT EXISTS `shop_products` (
            `id`	CHAR(36)	PRIMARY KEY DEFAULT(UUID()),
            `name`	TEXT NOT NULL,
            `description` TEXT NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `img_url`	TEXT
            ) ENGINE = InnoDB, DEFAULT CHARSET = utf8mb4";

        try 
        {
            $db->query($query);
        }
        catch(PDOException $ex) 
        {
            $this->log_error(
                __CLASS__ . 
                ' ' . __LINE__ . 
                ' ' . $ex->getMessage() .
                ' ' . $query);
            
            $this->send_json(null, 503, 'Internal error. See server logs.');
        }

        $this->send_json('Table `shop_products` install');
    }

    private function shop_databases()
    {
        $db = $this->get_db_or_exit();
        
        $query = "SHOW DATABASES";

        try 
        {
            $res = $db->query($query);
            // дан! з БД передаються "по рядку" - не всe одразу 
            $data = [];
            while($row = $res->fetch(PDO::FETCH_NUM)) 
            {
                $data[] = $row[0] ;
            }

            // PDO::FETCH_NUM - нумерований масив
            // PDO::FETCH_ASSOC - асоц!ативний масив
            // PDO::FETCH_BOTH - Обидва (за замовчанням)

            $res->closeCursor();
        }
        catch(PDOException $ex) 
        {
            $this->log_error(
                __CLASS__ . 
                ' ' . __LINE__ . 
                ' ' . $ex->getMessage() .
                ' ' . $query);
            
            $this->send_json(null, 503, 'Internal error. See server logs.');
        }

        $this->send_json($data);
    }
}