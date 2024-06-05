<!DOCTYPE html>
<html>
<head>
    <title>E-shop</title>
    <link rel="stylesheet" type="text/css" href="styleeshop.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        
        .container {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
        }

        
        .category-panel {
            width: 250px;
            background-color: #f8f8f8;
            padding: 20px;
            border-right: 1px solid #ddd;
            box-sizing: border-box;
        }

        .category-panel h2 {
            margin-top: 0;
        }

        .category-panel ul {
            list-style: none;
            padding: 0;
        }

        .category-panel ul li {
            margin: 10px 0;
        }

        .category-panel ul li a {
            text-decoration: none;
            color: #fff; 
            font-weight: bold;
            display: block; 
            padding: 10px; 
            background-color: #28a745; 
            border-radius: 8px;
            text-align: center; 
        }

        .category-panel ul li a:hover {
            background-color: #218838; 
        }
        
        .products {
            flex: 1; 
            padding: 20px;
            box-sizing: border-box;
        }

        
        .search-container {
            margin: 20px 0;
            width: 100%; 
        }

        .search-container input[type="text"] {
            width: 80%;
            padding: 10px;
            font-size: 16px;
        }

        .search-container input[type="submit"],
        .back-link {
            padding: 10px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px; 
            text-decoration: none; 
            display: inline-block; 
        }

        .search-container input[type="submit"]:hover,
        .back-link:hover {
            background-color: #218838;
        }

        
        .products-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between; 
        }

        .products-list .product {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px; 
            border-radius: 5px;
            width: calc(33.33% - 20px); 
            box-sizing: border-box;
        }

        .products-list .product img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }

        
        @media (max-width: 768px) {
            .products-list .product {
                width: calc(50% - 20px); 
            }
        }

        @media (max-width: 480px) {
            .products-list .product {
                width: 100%; 
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="category-panel">
        <h2>Kategórie</h2>
        <ul>
            <li><a href="eshop.php">Všetky produkty</a></li>
            <?php
            $servername = "localhost";
            $username = "simo3a2";
            $password = "14837944,Aa";
            $dbname = "simo";
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM categories";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li><a href='eshop.php?category_id=" . $row["id"] . "'>" . $row["name"] . "</a></li>";
                }
            } else {
                echo "No categories available.";
            }

            $conn->close();
            ?>
        </ul>
    </div>

    <div class="products">
        <div class="search-container">
            <form action="#" method="get">
                <input type="text" placeholder="Vyhľadávanie..." name="search" value="<?php if(isset($_GET['search'])) echo $_GET['search']; ?>">
                <input type="submit" value="Vyhľadať">
                <?php if(isset($_GET['search'])): ?>
                    <a href="eshop.php" class="back-link">Späť na všetky produkty</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="products-list">
            <?php
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT * FROM products";
            
            if(isset($_GET['category_id'])){
                $category_id = $_GET['category_id'];
                $sql .= " WHERE category_id = $category_id";
            }

            if(isset($_GET['search'])){
                $search = $_GET['search'];
                $sql .= (strpos($sql, 'WHERE') !== false ? " AND" : " WHERE") . " name LIKE '%$search%'";
            }

            $sql .= " ORDER BY price ASC";

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<h2>" . $row["name"] . "</h2>";
                    echo "<p>Brand: " . $row["brand"] . "</p>";
                    echo "<p>Manufacturer: " . $row["manufacturer"] . "</p>";
                    echo "<p>Price: $" . $row["price"] . "</p>";
                    echo "<p>Weight: " . $row["weight"] . " g</p>";
                    echo "<p>Stock Quantity: " . $row["stock_quantity"] . "</p>";
                    echo "<img src='" . $row["image"] . "' alt='Product Image'>";
                    echo "</div>";
                }
            } else {
                echo "No products available.";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

</body>
</html>
