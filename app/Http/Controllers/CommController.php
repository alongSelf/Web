<?php

namespace App\Http\Controllers;

class CommController extends Controller
{
    public function checkPhone($phone)
    {
        if(preg_match('/^0?1[3|4|5|8][0-9]\d{8}$/', $phone)){
            return true;
        }else{
            return false;
        }
    }
    public function checkMail($mail)
    {
        if(preg_match('/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/', $mail)){
            return true;
        }else{
            return false;
        }
    }
    public function checkStr($val)
    {
        if(preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/", $val)){
            return true;
        }else{
            return false;
        }
    }
    
    public function isLogIn()
    {
        if (session('user')){
            return true;
        }else{
            return false;
        }
    }

    public function numPerPage()
    {
        return 10;
    }

    public function getID()
    {
        return 'L'.strtoupper(uniqid());
    }

    public function pswMin()
    {
        return 6;
    }
    public function pswMax()
    {
        return 12;
    }
}
