<?php
header('Content-Type: application/json');

// Проверка на прямой доступ
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не разрешен']);
    exit;
}

// Получение данных
$data = [
    'name' => trim($_POST['name'] ?? ''),
    'phone' => trim($_POST['phone'] ?? ''),
    'form_type' => trim($_POST['form_type'] ?? 'unknown')
];

// Валидация
if (empty($data['name']) || empty($data['phone'])) {
    echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
    exit;
}

// Настройки письма
$to = 'progreSSite@ya.ru';
$subject = 'Новая заявка: ' . ($data['form_type'] === 'consultation' ? 'Консультация' : 'Обратный звонок');
$message = "
    <h2>Новая заявка</h2>
    <p><strong>Тип:</strong> {$subject}</p>
    <p><strong>Имя:</strong> {$data['name']}</p>
    <p><strong>Телефон:</strong> {$data['phone']}</p>
    <p><strong>Время:</strong> " . date('d.m.Y H:i') . "</p>
";

$headers = [
    'From: no-reply@' . $_SERVER['HTTP_HOST'],
    'Content-type: text/html; charset=utf-8',
    'X-Mailer: PHP/' . phpversion()
];

// Отправка письма
$mailSent = mail($to, $subject, $message, implode("\r\n", $headers));

if ($mailSent) {
    echo json_encode(['success' => true, 'message' => 'Спасибо! Мы скоро свяжемся с вами.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ошибка при отправке. Попробуйте позже.']);
}
?>