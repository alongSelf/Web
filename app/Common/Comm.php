<?php

function rtnMsg($code, $msg)
{
    return $data = [
        'status' => $code,
        'msg' => $msg,
    ];
}

function errLogin()
{
    return 10000;
}

