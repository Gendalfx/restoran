<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Menu</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
    $store_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($store_id > 0) {
        $store_id = $conn->real_escape_string($store_id);
        $sql = "SELECT * FROM Stores WHERE id=$store_id";
        $result = $conn->query($sql);
        $store = $result->fetch_assoc();
    }

    // Функція для валідації введених даних
    function validate_input($name, $phone) {
        // Перевірка імені (лише літери та пробіли)
        if (empty($name) || !preg_match("/^[a-zA-Zа-яА-Яіїєґ' ]+$/u", $name)) {
            return "Невірне ім'я. Ім'я повинно містити лише літери.";
        }

        // Перевірка телефону (формат +380123456789)
        if (empty($phone) || !preg_match("/^\+380\d{9}$/", $phone)) {
            return "Невірний телефонний номер. Використовуйте формат +380xxxxxxxxx.";
        }

        return true; // Всі перевірки пройдено
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Санітарна очистка вводу: видалення зайвих пробілів
        $customer_name = trim($_POST['customer_name']);
        $customer_phone = trim($_POST['customer_phone']);

        // Форматування номера телефону: додаємо +380, якщо номер не починається з нього
        if (substr($customer_phone, 0, 4) !== '+380') {
            $customer_phone = '+380' . preg_replace('/^\+?38?/', '', $customer_phone);  // видаляємо старий код, якщо він є
        }

        // Очищення від пробілів і спеціальних символів для безпеки
        $customer_name = $conn->real_escape_string($customer_name);
        $customer_phone = $conn->real_escape_string($customer_phone);

        // Валідація введених даних
        $validation_result = validate_input($customer_name, $customer_phone);
        if ($validation_result !== true) {
            echo $validation_result;
        } else {
            // Ваш SQL запит для додавання замовлення
            $sql = "INSERT INTO Orders (store_id, customer_name, customer_phone) VALUES ($store_id, '$customer_name', '$customer_phone')";
            if ($conn->query($sql) === TRUE) {
                echo "Замовлення успішно додано!";
            } else {
                echo "Помилка: " . $conn->error;
            }
        }
    }
    ?>
    
    <?php if (isset($store)): ?>
        <h1><?php echo htmlspecialchars($store['name']); ?></h1>
        <p><?php echo htmlspecialchars($store['address']); ?></p>
        <p><?php echo htmlspecialchars($store['phone_number']); ?></p>
        <h2>Menu</h2>
        <form action="order.php" method="post">
            <input type="hidden" name="store_id" value="<?php echo htmlspecialchars($store_id); ?>">
            <div class="menu-items">
                <?php
                $sql = "SELECT * FROM Menu WHERE store_id=$store_id";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<div class='menu-item'>
                                <h3>" . htmlspecialchars($row["name"]) . "</h3>
                                <p>" . htmlspecialchars($row["description"]) . "</p>
                                <p>Price: " . htmlspecialchars($row["price"]) . "</p>
                                <input type='checkbox' name='menu_items[]' value='" . intval($row["id"]) . "'> Select
                              </div>";
                    }
                } else {
                    echo "0 results";
                }
                ?>
            </div>
            <input type="text" name="customer_name" placeholder="Your Name" required>
            <input type="text" name="customer_phone" placeholder="Your Phone" required>
            <input type="submit" value="Place Order">
        </form>
    <?php else: ?>
        <p>Store not found or invalid ID.</p>
    <?php endif; ?>
</body>
</html>
