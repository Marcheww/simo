<?php
    $servername = "localhost";
    $username = "simo3a2";
    $password = "14837944,Aa";
    $dbname = "simo";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category_action'])) {
        if ($_POST['category_action'] == 'add') {
            $category_name = mysqli_real_escape_string($conn, trim($_POST['category_name']));
            if (!empty($category_name)) {
                $sql = "INSERT INTO categories (name) VALUES ('$category_name')";
                if ($conn->query($sql) === TRUE) {
                    echo '<div class="success-message">Kategória bola úspešne pridaná.</div>';
                } else {
                    echo '<div class="error-message">Error: ' . $conn->error . '</div>';
                }
            } else {
                echo '<div class="error-message">Názov kategórie nesmie byť prázdny.</div>';
            }
        } elseif ($_POST['category_action'] == 'delete') {
            $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
            $sql = "DELETE FROM categories WHERE id = $category_id";
            if ($conn->query($sql) === TRUE) {
                echo '<div class="success-message">Kategória bola úspešne vymazaná.</div>';
            } else {
                echo '<div class="error-message">Error: ' . $conn->error . '</div>';
            }
        }
    }

    $sql = "SELECT * FROM categories";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>CMS</title>
    <link rel="stylesheet" type="text/css" href="stylewelcome.css">       
</head>
<body>
<div class="top-buttons">
    <button class="logout-button" onclick="window.location.href='index.php'">Odhlásiť sa</button>
    <button class="eshop-button" onclick="window.location.href='eshop.php'">E-shop</button>
</div>
<div class="container">
    <!-- Panel na správu kategórií -->
    <div class="category-panel">
        <h2>Správa kategórií</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="hidden" name="category_action" value="add">
            Názov kategórie: <input type="text" name="category_name">
            <input type="submit" value="Pridať kategóriu">
        </form>
        <ul>
            <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<li>" . $row["name"] . 
                             " <form style='display:inline;' method='post' action='" . $_SERVER['PHP_SELF'] . "'>
                                 <input type='hidden' name='category_action' value='delete'>
                                 <input type='hidden' name='category_id' value='" . $row["id"] . "'>
                                 <input type='submit' class='delete-button' value='Vymazať'>
                               </form>
                             </li>";
                    }
                } else {
                    echo "Žiadne kategórie.";
                }
            ?>
        </ul>
    </div>

    <!-- Panel na správu produktov -->
    <div class="main">
        <h2>Správa produktov</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <input type="hidden" name="product_action" value="add">
            Názov produktu: <input type="text" name="name">
            Kategória:
            <select name="category_id">
                <?php
                    $category_sql = "SELECT * FROM categories";
                    $category_result = $conn->query($category_sql);
                    if ($category_result->num_rows > 0) {
                        while($category_row = $category_result->fetch_assoc()) {
                            echo "<option value='" . $category_row["id"] . "'>" . $category_row["name"] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Žiadne kategórie</option>";
                    }
                ?>
            </select>
            Cena: <input type="text" name="price">
            Značka: <input type="text" name="brand">
            Výrobca: <input type="text" name="manufacturer">
            Váha: <input type="text" name="weight">
            Skladové množstvo: <input type="text" name="stock_quantity">
            Obrázok: <input type="file" name="image">
            <input type="submit" value="Pridať produkt">
        </form>

        <h2>Existujúce produkty</h2>
        <ul>
            <?php
                $product_sql = "SELECT * FROM products";
                $product_result = $conn->query($product_sql);
                if ($product_result->num_rows > 0) {
                    while($product_row = $product_result->fetch_assoc()) {
                        echo "<li>" . $product_row["name"] . 
                             " <form style='display:inline;' method='post' action='" . $_SERVER['PHP_SELF'] . "'>
                                 <input type='hidden' name='product_action' value='delete'>
                                 <input type='hidden' name='product_id' value='" . $product_row["id"] . "'>
                                 <input type='submit' class='delete-button' value='Vymazať'>
                               </form>
                               <form style='display:inline;' method='post' action='" . $_SERVER['PHP_SELF'] . "' enctype='multipart/form-data'>
                                 <input type='hidden' name='product_action' value='edit'>
                                 <input type='hidden' name='product_id' value='" . $product_row["id"] . "'>
                                 Názov: <input type='text' name='name' value='" . $product_row["name"] . "'>
                                 Kategória: 
                                 <select name='category_id'>";
                        $category_result->data_seek(0); // Reset the pointer to the beginning
                        while($category_row = $category_result->fetch_assoc()) {
                            $selected = ($category_row["id"] == $product_row["category_id"]) ? "selected" : "";
                            echo "<option value='" . $category_row["id"] . "' $selected>" . $category_row["name"] . "</option>";
                        }
                        echo "</select>
                                 Cena: <input type='text' name='price' value='" . $product_row["price"] . "'>
                                 Značka: <input type='text' name='brand' value='" . $product_row["brand"] . "'>
                                 Výrobca: <input type='text' name='manufacturer' value='" . $product_row["manufacturer"] . "'>
                                 Váha: <input type='text' name='weight' value='" . $product_row["weight"] . "'>
                                 Skladové množstvo: <input type='text' name='stock_quantity' value='" . $product_row["stock_quantity"] . "'>
                                 Obrázok: <input type='file' name='image'>
                                 <input type='submit' class='edit-button' value='Upraviť'>
                               </form>
                             </li>";
                    }
                } else {
                    echo "Žiadne produkty.";
                }
            ?>
        </ul>
    </div>
</div>
</body>
</html>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_action'])) {
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
        $price = mysqli_real_escape_string($conn, trim($_POST['price']));
        $brand = mysqli_real_escape_string($conn, trim($_POST['brand']));
        $manufacturer = mysqli_real_escape_string($conn, trim($_POST['manufacturer']));
        $weight = mysqli_real_escape_string($conn, trim($_POST['weight']));
        $stock_quantity = mysqli_real_escape_string($conn, trim($_POST['stock_quantity']));
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        if ($_POST['product_action'] == 'add') {
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check !== false) {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    if (!empty($name) && !empty($category_id) && !empty($price) && !empty($brand) && !empty($manufacturer) && !empty($weight) && !empty($stock_quantity) && !empty($image)) {
                        $sql = "INSERT INTO products (name, category_id, price, brand, manufacturer, weight, stock_quantity, image)
                                VALUES ('$name', '$category_id', '$price', '$brand', '$manufacturer', '$weight', '$stock_quantity', '$target_file')";

                        if ($conn->query($sql) === TRUE) {
                            echo '<div class="success-message">Produkt bol úspešne pridaný.</div>';
                            echo '<script>setTimeout(function(){document.querySelector(".success-message").style.display = "none";}, 10000);</script>';
                        } else {
                            echo '<div class="error-message">Error: ' . $conn->error . '</div>';
                        }
                    } else {
                        echo '<div class="error-message">Všetky polia sú povinné.</div>';
                    }
                } else {
                    echo '<div class="error-message">Došlo k chybe pri nahrávaní súboru.</div>';
                }
            } else {
                echo '<div class="error-message">Súbor nie je obrázok.</div>';
            }
        } elseif ($_POST['product_action'] == 'edit') {
            $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
            if (!empty($name) && !empty($category_id) && !empty($price) && !empty($brand) && !empty($manufacturer) && !empty($weight) && !empty($stock_quantity)) {
                if (!empty($image)) {
                    $check = getimagesize($_FILES["image"]["tmp_name"]);
                    if ($check !== false) {
                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            $sql = "UPDATE products SET name='$name', category_id='$category_id', price='$price', brand='$brand', manufacturer='$manufacturer', weight='$weight', stock_quantity='$stock_quantity', image='$target_file' WHERE id='$product_id'";
                        } else {
                            echo '<div class="error-message">Došlo k chybe pri nahrávaní súboru.</div>';
                        }
                    } else {
                        echo '<div class="error-message">Súbor nie je obrázok.</div>';
                    }
                } else {
                    $sql = "UPDATE products SET name='$name', category_id='$category_id', price='$price', brand='$brand', manufacturer='$manufacturer', weight='$weight', stock_quantity='$stock_quantity' WHERE id='$product_id'";
                }

                if ($conn->query($sql) === TRUE) {
                    echo '<div class="success-message">Produkt bol úspešne upravený.</div>';
                    echo '<script>setTimeout(function(){document.querySelector(".success-message").style.display = "none";}, 10000);</script>';
                } else {
                    echo '<div class="error-message">Error: ' . $conn->error . '</div>';
                }
            } else {
                echo '<div class="error-message">Všetky polia sú povinné.</div>';
            }
        } elseif ($_POST['product_action'] == 'delete') {
            $product_id

            = mysqli_real_escape_string($conn, $_POST['product_id']);
            $sql = "DELETE FROM products WHERE id = $product_id";
            if ($conn->query($sql) === TRUE) {
            echo '<div class="success-message">Produkt bol úspešne vymazaný.</div>';
            } else {
            echo '<div class="error-message">Error: ' . $conn->error . '</div>';
            }
            }
            }
            $conn->close();
            ?>
            
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var successMessage = document.querySelector(".success-message");
                    if (successMessage) {
                        setTimeout(function() {
                            successMessage.style.display = "none";
                        }, 10000);
                    }
                });
            </script>