<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Orders</h1>
    <?php
    $store_id = $_GET['store_id'];
    
    // Отримуємо інформацію про ресторан
    $sql_store = "SELECT name FROM Stores WHERE id=$store_id";
    $result_store = $conn->query($sql_store);
    $store = $result_store->fetch_assoc();
    echo "<h2>Ресторан: " . $store['name'] . "</h2>";

    // Отримуємо всі замовлення для цього ресторану
    $sql_orders = "SELECT * FROM Orders WHERE store_id=$store_id";
    $result_orders = $conn->query($sql_orders);

    if ($result_orders->num_rows > 0) {
        echo "<table>
                <thead>
                    <tr>
                        <th>Номер замовлення</th>
                        <th>Дата</th>
                        <th>Ім'я клієнта</th>
                        <th>Телефон</th>
                        <th>Страви</th>
                        <th>Дії</th>
                    </tr>
                </thead>
                <tbody>";
        while ($order = $result_orders->fetch_assoc()) {
            echo "<tr>
                    <td>" . $order['id'] . "</td>
                    <td>" . $order['order_date'] . "</td>
                    <td>" . $order['customer_name'] . "</td>
                    <td>" . $order['customer_phone'] . "</td>
                    <td>";

            // Отримуємо список страв для замовлення
            $order_id = $order['id'];
            $sql_items = "SELECT Menu.name FROM Order_Items 
                          JOIN Menu ON Order_Items.menu_item_id = Menu.id 
                          WHERE Order_Items.order_id=$order_id";
            $result_items = $conn->query($sql_items);

            if ($result_items->num_rows > 0) {
                echo "<ul>";
                while ($item = $result_items->fetch_assoc()) {
                    echo "<li>" . $item['name'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "Немає страв";
            }

            echo "</td>
                  <td>
                      <a href='edit_order.php?id=$order_id'>Редагувати</a> | 
                      <a href='delete_order.php?id=$order_id' onclick='return confirm(\"Ви впевнені, що хочете видалити це замовлення?\");'>Видалити</a>
                  </td>
                  </tr>";
        }
        echo "</tbody>
              </table>";
    } else {
        echo "<p>Немає замовлень для цього ресторану.</p>";
    }

    $conn->close();
    ?>
    <a href="index.php">Повернутись до списку ресторанів</a>
</body>
</html>
