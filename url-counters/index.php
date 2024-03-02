<?php
session_start();

if (isset($_SESSION['username']) && isset($_SESSION['usertype'])) {
    header("location: dashboard.php");
    exit;
}

header("location: login.php");
exit;
?>
<?php
// <html>
//     <head>
//         <title>HOME</title>
//     </head>
//     <body>
//         <p>
//             <h1>HOME</h1>
//         </p>
//         <h4><a href="login.php">LOGIN</a></h4>
//         <h4><a href="signup.php">SIGNUP</a></h4>

//     </body>
// </html>