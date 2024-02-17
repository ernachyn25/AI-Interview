<?php
@$page = $_GET['page'];

if($page == '' || !$page || $page == 'home')
{
    include 'home.php';
}
else if($page == 'interview')
{
    include 'interview.php';
}
?>