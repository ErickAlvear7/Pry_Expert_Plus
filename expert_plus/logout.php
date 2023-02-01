<?php
    session_start();
    session_destroy();
    header("Location: index"); //deberia ser el login
?>