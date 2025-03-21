<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';

$mail = new PHPMailer(true);
$mail->CharSet = 'UTF-8';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];
// $resume = $_POST['resume'];
// $portfolio = $_POST['portfolio'];

$body = '';
$domain = '';
if (isset($_SERVER['HTTP_REFERER'])) {
    $refererUrl = $_SERVER['HTTP_REFERER'];
    $body .= "Форма со страницы: {$refererUrl}\n";
    $domain = $_SERVER['HTTP_HOST'];
}


$formEntity = [
    'Имя' => $name,
    'Email' => $email,
    'Телефон' => $phone,
    'Сообщение' => $message,
    // 'Резюме' => $resume,
    // 'Портфолио' => $portfolio
];

foreach ($formEntity as $key => $value) {
    if(!empty($value) && $value !== 'undefined') {
        $body .= "{$key}: {$value}\n";
    }
}

$theme = "Заявка с формы";


 try {
    // $mail->addAddress('info@techno-vid.ru');
  $mail->addAddress('klochkovd@gefestcapital.com');
    $mail->Subject = $theme;
    $mail->Body = $body;
    $mail->setFrom('info@techno-vid.ru', 'TechoVid');


    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

try {
    if (strpos($email, '@') === false) {
        $phone = $email;
    }

    if ($message == 'undefined') {
        $message = '';
    }
    $roistatData = array(
        'roistat' => isset($_COOKIE['roistat_visit']) ? $_COOKIE['roistat_visit'] : 'nocookie',
        'key'     => 'MGU3MmQ5OGIzMjA1NjRlMjJjZDMxMjFiMzg2YTkwYjM6MjYzMzE1', // Ключ для интеграции с CRM, указывается в настройках интеграции с CRM.
        'title'   => "Форма со страницы: $domain", // Название сделки
        'comment' => $message, // Комментарий к сделке
        'name'    => $name, // Имя клиента
        'email'   => $email, // Email клиента
        'phone'   => $phone, // Номер телефона клиента
        'sync'    => '0', //
        'is_need_check_order_in_processing' => '1', 
        'is_need_check_order_in_processing_append' => '1', // 
//        'fields'  => array(
//            // Массив дополнительных полей. Если дополнительные поля не нужны, оставьте массив пустым.
//            // Примеры дополнительных полей смотрите в таблице ниже.
//            // Помимо массива fields, который используется для сделки, есть еще массив client_fields, который используется для установки полей контакта.
//            "charset" => "Windows-1251", // Сервер преобразует значения полей из указанной кодировки в UTF-8.
//        ),
    );

    file_get_contents("https://cloud.roistat.com/api/proxy/1.0/leads/add?" . http_build_query($roistatData));
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}