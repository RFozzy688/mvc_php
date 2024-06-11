<?php

class BaseController{

    public function serve($action)
    {
        $this->action = $action;

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

        if(is_readable($view_path)){
            include $view_path;
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
}