<HTML>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!--</head>-->
    </head>

<BODY>
<?php
include "fungsi.php";
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");
$reset_pin_qr = mysqli_query($con,"UPDATE `dosen` SET `pin`='$pin',`qr`='$qr' WHERE `nip`=$_COOKIE[user]");
//logout();
unset ($_COOKIE['user_login']);
unset ($_COOKIE['userpassword']);
setcookie("user_login","");
setcookie("userpassword","");
unset ($_COOKIE['user']);
unset ($_COOKIE['pass']);
setcookie("user","");
setcookie("pass","");
echo "
<script>
  window.location.href=\"login.php?st=new\";
</script>
";
?>
<script type="text/javascript" src="jquery.min.js"></script>
<script>
    history.pushState(null, null, null);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, null);
    });
</script>
<!--</body></html>-->
</BODY>
</HTML>
