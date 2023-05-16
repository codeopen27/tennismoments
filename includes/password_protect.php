<?php


$LOGIN_INFORMATION = array(
  'root',
  'testpass',
  'passwd'
);

$LOGIN_INFORMATION = array(
  'admin' => 'tennisball',
  'admin' => 'adminpass'
);

// request login? true - show login and password boxes, false - password box only
define('USE_USERNAME', true);

// User will be redirected to this page after logout
define('LOGOUT_URL', 'https://www.tennismoments.center/');

// time out after NN minutes of inactivity. Set to 0 to not timeout
define('TIMEOUT_MINUTES', 0);

// This parameter is only useful when TIMEOUT_MINUTES is not zero
// true - timeout time from last activity, false - timeout time from login
define('TIMEOUT_CHECK_ACTIVITY', true);

##################################################################
#  SETTINGS END
##################################################################


///////////////////////////////////////////////////////
// do not change code below
///////////////////////////////////////////////////////

// show usage example
if(isset($_GET['help'])) {
  die('Include following code into every page you would like to protect, at the very beginning (first line):<br>&lt;?php include("' . str_replace('\\','\\\\',__FILE__) . '"); ?&gt;');
}

// timeout in seconds
$timeout = (TIMEOUT_MINUTES == 0 ? 0 : time() + TIMEOUT_MINUTES * 60);

// logout?
if(isset($_GET['logout'])) {
  setcookie("verify", '', $timeout, '/'); // clear password;
  header('Location: ' . LOGOUT_URL);
  exit();
}

if(!function_exists('showLoginPasswordProtect')) {

// show login form
function showLoginPasswordProtect($error_msg) {
?>
<html>
<head>
  <title>Please enter password to access this page</title>
  <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
  <META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
  <link rel="stylesheet" type="text/css" href="stylesheet.css">
  <style type="text/css">
  body {
    background-color: #000000;
    background-image: url();
    background-repeat: no-repeat;
}
  body,td,th {
    color: #57F30D;
    font-family: Consolas, "Andale Mono", "Lucida Console", "Lucida Sans Typewriter", Monaco, "Courier New", monospace;
}
  a:link {
    color: #FCFB00;
}
a:visited {
    color: #FCFB00;
}
a:hover {
    color: #FF0004;
}
a:active {
    color: #FCFB00;
}
  </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body link="#FCFB00" vlink="#FCFB00" alink="#FCFB00">
  <style>
    input { border: 1px solid black; }
  </style>
  <div style="width: 500px; margin-left: auto; margin-right: auto; text-align: center; color: #57F30D; font-family: Consolas, 'Andale Mono', 'Lucida Console', 'Lucida Sans Typewriter', Monaco, 'Courier New', monospace; font-style: normal; font-size: larger;">
  <form method="post">
     <h2>&nbsp;</h2>
     <h2>&nbsp;</h2>
     <p>&nbsp;</p>
     <p>&nbsp;</p>
     <h2>Welcome to Tennis Moments<br>
       Tennis Matches on videos
     </h2>
    <h3>Please enter password to access this Website</h3>
    <h6>If you attended this event, but do not have a password, <br>
      you can contact  via email by<A HREF="mailto:tennismoments27@gmail.com"> clicking here</A><br>
    <br>
    Password :</h6>
    <p><font color="red"><?php echo $error_msg; ?></font><br />
  <?php if (USE_USERNAME) echo 'Login:<br /><input type="input" name="access_login" /><br />Password:<br />'; ?>
      <input type="password" name="access_password" />
    </p>
    <p>
      <input type="submit" name="Submit" value="Submit" />
    </p>
  </form>
    <br>
    <h4>to go back to the Tennis Matches , <a href="https://www.tennismoments.center/">CLICK HERE</a></h4>
  <a style="font-size:9px; color: #B0B0B0; font-family: Verdana, Arial;" href="https://www.tennismoments.center" title="">Powered by Tennis Fans</a>
  </div>
</body>
</html>

<?php
  // stop at this point
  die();
}
}

// user provided password
if (isset($_POST['access_password'])) {

  $login = isset($_POST['access_login']) ? $_POST['access_login'] : '';
  $pass = $_POST['access_password'];
  if (!USE_USERNAME && !in_array($pass, $LOGIN_INFORMATION)
  || (USE_USERNAME && ( !array_key_exists($login, $LOGIN_INFORMATION) || $LOGIN_INFORMATION[$login] != $pass ) ) 
  ) {
    showLoginPasswordProtect("Incorrect password.");
  }
  else {
    // set cookie if password was validated
    setcookie("verify", md5($login.'%'.$pass), $timeout, '/');
    
    // Some programs (like Form1 Bilder) check $_POST array to see if parameters passed
    // So need to clear password protector variables
    unset($_POST['access_login']);
    unset($_POST['access_password']);
    unset($_POST['Submit']);
  }

}

else {

  // check if password cookie is set
  if (!isset($_COOKIE['verify'])) {
    showLoginPasswordProtect("");
  }

  // check if cookie is good
  $found = false;
  foreach($LOGIN_INFORMATION as $key=>$val) {
    $lp = (USE_USERNAME ? $key : '') .'%'.$val;
    if ($_COOKIE['verify'] == md5($lp)) {
      $found = true;
      // prolong timeout
      if (TIMEOUT_CHECK_ACTIVITY) {
        setcookie("verify", md5($lp), $timeout, '/');
      }
      break;
    }
  }
  if (!$found) {
    showLoginPasswordProtect("");
  }

}

?>