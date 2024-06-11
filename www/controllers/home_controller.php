<?php
include_once "controller_base.php";

class HomeController extends BaseController
{
    public function do_get()
    {
        $this->goto_view();
    }
}