<!DOCTYPE html>
<html>
<head>
<title>Login form</title>
<link rel="stylesheet" type="text/css" href="stylelog.css" >
</head>
<body>
    <div class="div-login">
      <div class="login-text">Login</div>
        <div class="container">
          <form action="index.php" method="post">
            <input type="text" name="username" placeholder="Username" required autofocus><br>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login">
          </form>
          <form action="register.php" method="get">
        <button type="submit">Registrovať sa</button>
    </form>
  </div>
  <?php
session_start();   //otvorenie session
   
//kontrola ci uz bol potvrdeny formular a ci boli vyplnene obidva udaje aj username aj password
if (isset($_POST['login']) && !empty($_POST['username']) 
    && !empty($_POST['password'])) {

    //connect string do DB
    $servername = "localhost";
    $username = "simo3a2";
    $password = "14837944,Aa";
    $dbname = "simo";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    //vyber hesla z DB podla usera, ktory sa prihlasuje
    $sql = "SELECT * FROM t_user where password ='".$_POST['password']."'";
    $result = $conn->query($sql);

    //ak vrati select viac ako 0 riadkov, user existuje
    if ($result->num_rows > 0) {
        // output data of each row
        $row = $result->fetch_assoc();
        if($row["password"]==$_POST['password']) {
            //if(password_verify($_POST['password'],$row["password"])) {
            $_SESSION['valid'] = true; //ulozenie session
            $_SESSION['timeout'] = time();
            $_SESSION['username'] = $_POST['username'];

            //presmerovanie na dalsiu stranku
            header("Location: welcome.php", true, 301);
            exit();
        } else {
            echo "<p class='error'>Nesprávne heslo alebo login</p>";
        }
    } else {
        echo "<p class='error'>Nesprávne heslo alebo login</p>";
    }

    $conn->close();
}     
?>
</div>
</body>
</html>
