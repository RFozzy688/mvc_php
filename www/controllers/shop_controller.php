<?php
include_once "controller_base.php";

class ShopController extends BaseController
{  
    public function do_get()
    {
        $this->goto_view();
    }
}