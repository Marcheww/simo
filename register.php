<!DOCTYPE html>
<html>
<head>
    <title>Registrácia</title>
    <link rel="stylesheet" type="text/css" href="stylereg.css" >
</head>
<body>
    <div class="div-register">
      <div class="register-text">Registrácia</div>
  
    
          <div class="container">
            <form method="post" action="register.php">
              <label for="meno">Meno :</label><br>
              <input type="text" id="meno" name="meno" required><br>
              <label for="heslo">Heslo :</label><br>
              <input type="password" id="heslo" name="heslo" required><br>
              <label for="email">Email :</label><br>
              <input type="email" id="email" name="email" required><br>
              <input type="submit" value="Registrovať">
            </form>
            <form action="index.php" method="get">
        <button type="submit">Prihlásiť sa</button>
    </form>
          </div>
          <?php
$servername = "localhost";
$username = "simo3a2";
$password = "14837944,Aa";
$dbname = "simo";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meno = $_POST['meno'];
    $heslo = $_POST['heslo'];
    $email = $_POST['email'];

    $check_sql = "SELECT * FROM t_user WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $meno, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<p class='error'>Používatel s týmito údajmi už existuje</p>";
    } else {
    
        $insert_sql = "INSERT INTO t_user (username, password, email) VALUES (?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sss", $meno, $heslo, $email);

        if ($insert_stmt->execute()) {
            echo "<p class='success'>Registrácia úspešná</p>";
        } else {
            echo "<p class='error'>Registrácia zlyhala: " . $conn->error . "</p>";
        }
    }
}

$conn->close();
?>


    </div> 
</body>
</html>


