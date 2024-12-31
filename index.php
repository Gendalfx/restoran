<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Restaurants</h1>
    <div class="restaurants">
        <?php
        $sql = "SELECT * FROM Stores";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='restaurant'>
                        <h2>" . $row["name"] . "</h2>
                        <p>" . $row["address"] . "</p>
                        <p>" . $row["phone_number"] . "</p>
                        <a href='restaurant.php?id=" . $row["id"] . "'>Переглянути меню</a>
                        <a href='orders.php?store_id=" . $row["id"] . "'>Переглянути замовлення</a>
                      </div>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
