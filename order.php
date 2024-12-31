<?php
include 'db.php';

$store_id = intval($_POST['store_id']);
$customer_name = $conn->real_escape_string($_POST['customer_name']);
$customer_phone = $conn->real_escape_string($_POST['customer_phone']);
$menu_items = $_POST['menu_items']; // массив ID позиций меню
$order_date = date('Y-m-d H:i:s');

// Вставляем заказ в таблицу Orders
$sql = "INSERT INTO Orders (store_id, order_date, customer_name, customer_phone) 
        VALUES ($store_id, '$order_date', '$customer_name', '$customer_phone')";

if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id; // Получаем ID добавленного заказа

    // Вставляем каждую позицию в таблицу Order_Items
    foreach ($menu_items as $menu_item_id) {
        $menu_item_id = intval($menu_item_id); // Преобразуем ID в число
        $sql = "INSERT INTO Order_Items (order_id, menu_item_id) 
                VALUES ($order_id, $menu_item_id)";
        if (!$conn->query($sql)) {
            echo "Ошибка: " . $conn->error;
        }
    }
    echo "Замовлення успішно оформлено!";
} else {
    echo "Помилка: " . $conn->error;
}

$conn->close();
?>
<a href="index.php">Повернутися на головну сторінку</a>
