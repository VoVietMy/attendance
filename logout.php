<?php

//logout.php

session_start();

session_destroy();

header('location:login_teacher.php');

?>