<?php
  if (empty($_SESSION['_token'])) {
  if (function_exists('random_bytes')) {
    $_SESSION['_token'] = bin2hex(random_bytes(32));
  } else if (function_exists('mcrypt_create_iv')) {
    $_SESSION['_token'] = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
  } else {
    $_SESSION['_token'] = bin2hex(openssl_random_pseudo_bytes(32));
  }
  }

  // validate token for all post request
  if($_SERVER['REQUEST_METHOD']==='POST'){
    //csrf protection
    if (!hash_equals($_SESSION['_token'], $_POST['_token'])) {
      echo 'Invalid CSRF.';
    }
    else{
      unset($_SESSION['_token']);
    }
  }
?>
