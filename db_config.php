<?php

    //Create connection
    $connect = mysqli_connect("localhost","root","","demofinal");

    //Check connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }