<?php include 'db.php'; ?>
<?php
// Используем параметр 'id' из URL
if (!isset($_GET['id'])) {
    echo "Ідентифікатор замовлення не передано!";
    exit;
}

$order_id = intval($_GET['id']);  // Используем 'id', а не 'order_id'

// Обработка формы POST запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и безопасно экранируем данные из формы
    $customer_name = $conn->real_escape_string($_POST['customer_name']);
    $customer_phone = $conn->real_escape_string($_POST['customer_phone']);

    // Обновляем данные заказа в базе
    $sql = "UPDATE Orders SET customer_name='$customer_name', customer_phone='$customer_phone' WHERE id=$order_id";

    // Выполняем запрос и проверяем результат
    if ($conn->query($sql) === TRUE) {
        echo "Замовлення оновлено!";
    } else {
        echo "Помилка: " . $conn->error;
    }
}

// Получаем данные заказа для отображения в форме
$sql = "SELECT * FROM Orders WHERE id=$order_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
} else {
    echo "Замовлення не знайдено!";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування замовлення</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Редагування замовлення</h1>
    <form method="post">
        <label>Ім'я клієнта:</label>
        <input type="text" name="customer_name" value="<?php echo htmlspecialchars($order['customer_name']); ?>" required>
        <label>Телефон клієнта:</label>
        <input type="text" name="customer_phone" value="<?php echo htmlspecialchars($order['customer_phone']); ?>" required>
        <input type="submit" value="Оновити">
    </form>
    <a href="orders.php?store_id=<?php echo $order['store_id']; ?>">Назад до замовлень</a>
</body>
</html>
