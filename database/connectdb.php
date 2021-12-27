<?php
try{
    $inventory = new PDO('mysql:host=localhost;dbname=inventory','root','');
    // echo 'Connection Successfull';
} catch(PDOException $f){
    echo $f->getmessage();
}

?>
