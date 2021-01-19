<?php
  require '../config/config.php';
  $statement=$pdo->prepare('DELETE FROM users WHERE id='.$_GET['id']);
  $statement->execute();
  header('Location:user_list.php');
?>
