<?php

function dd($var = null)
{
    print_r('<pre>');  print_r($var);  print_r('<pre>'); die();
}

$con = mysqli_connect('localhost', 'root', '', 'login/registration');

function escape($string) {
    global $con;
    return mysqli_escape_string($con, $string);
}

function Query($query) {
    global $con;
    return mysqli_query($con, $query);
}

function confirm($result)
{
    global $con;
    if (!$result)
    {
        die('Query Failed'. mysqli_error($con));
    }
}



function fetch_data($result) {
    return mysqli_fetch_assoc($result);
}

function row_count($count) {
    return mysqli_num_rows($count);
}
















?>