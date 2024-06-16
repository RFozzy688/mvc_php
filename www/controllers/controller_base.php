<?php

class BaseController{

    private $db;

    public function __construct()
    {
        $this->db = null;
    }

    public function serve($action)
    {
        $this->action = $action;
        $this->view_data = [
            'title' => 'PHP-221',
        ];

        $method = 'do_' . strtolower($_SERVER['REQUEST_METHOD']);

        if(method_exists($this, $method))
        {
          $this->$method();
        }
        else
        {
          $this->send_error('Method not implemented', 405);
        }
    }

    public function goto_view()
    {
        $class_name = get_class($this);
        $controller_name = strtolower(substr($class_name, 0, strpos($class_name, 'Controller')));
        $view_path = "./views/{$controller_name}/{$this->action}.php";

        if(is_readable($view_path))
        {
            include "./views/_shared/_layout.php";
        }
        else
        {
            echo "No view found '$view_path'";
        }
    }

    public function send_error($message = 'Bad request', $code = 400) 
    {
        echo json_encode([
            'status' => 'error',
            'code' => $code,
            'message' => $message
        ]);
        exit;
    }

    public function get_db()
    {
        if ($this->db === null)
        {
            @include 'db_config.php'; // @ - "тихий режим" без отображения ошибок

            if (!isset($db_config))
            {
                $this->log_error('Конфигурация не подключена');
                return null;
            }

            try 
            {
                $this->db = new PDO(
                    "{$db_config['dbms']}:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['name']}", 
                    $db_config['user'], 
                    $db_config['pass'],
                    [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    ]);
            } 
            catch (PDOException $ex) 
            {
                $this->log_error('Ошибка подключения: ' . $ex->getMessage());
            }
        }

        return $this->db;
    }

    public function get_db_or_exit()
    {
        $db = $this->get_db();

        if ($db === null)
        {
            $this->send_json(null, 503, 'Internal error. See server logs.');
        }

        return $db;
    }

    public function log_error($message)
    {
        file_put_contents(
            '../log/php.log',
            date('d.m.Y h:i:s ') . $message . "\r\n",
            FILE_APPEND | LOCK_EX
        );
    }

    public function send_json($data, $status = 200, $message = "Ok")
    {
        http_response_code($status);
        header('Content-Type: application/json');
        $arr = [ 
          'status' => $status,
          'message' => $message,
          'data' => $data,
        ];

        echo json_encode($arr);
        exit;
    }
}