<?php
session_start();
echo "<script type='text/javascript'>alert('Logging out');</script>";
session_unset();
session_destroy();
?>