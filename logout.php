<?php
include 'config.php';
session_destroy();
header("location: admin_login.php");