<?php
session_start();



// If user not logged in, redirect to home/login
if (!isset($_SESSION['user_id'])) {
  header("Location: /creativityfreaks/index.php");
  exit;
}