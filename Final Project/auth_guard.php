<?php
session_start();
if (empty($_SESSION['loggedin'])) {
  http_response_code(403);
  header('Location: login.php?err=1');
  exit;
}