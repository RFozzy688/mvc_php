<?php
include_once "controller_base.php";

class ShopController extends BaseController{
  
  public function do_get(){
    echo "I'm do_get() ShopController with {$this->action}";
  }
}