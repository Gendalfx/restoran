<?php include 'db.php'; ?>
<?php
// Проверяем, передан ли параметр 'id'
if (!isset($_GET['id'])) {
    echo "Ідентифікатор замовлення не передано!";
    exit;
}

// Получаем id заказа из параметра GET
$order_id = intval($_GET['id']);

// Проверяем, что id корректный
if ($order_id <= 0) {
    echo "Невірний ідентифікатор замовлення: " . htmlspecialchars($_GET['id']);
    exit;
}

// Удаляем связанные записи из Order_Items
$sql = "DELETE FROM Order_Items WHERE order_id=$order_id";
if ($conn->query($sql) !== TRUE) {
    echo "Помилка при видаленні пов'язаних записів: " . $conn->error;
    $conn->close();
    exit;
}

// Удаляем сам заказ
$sql = "DELETE FROM Orders WHERE id=$order_id";
if ($conn->query($sql) === TRUE) {
    echo "Замовлення видалено!";
} else {
    echo "Помилка при видаленні замовлення: " . $conn->error;
}

// Закрываем соединение с базой данных
$conn->close();
?>
<a href="index.php">Назад до списку ресторанів</a>
