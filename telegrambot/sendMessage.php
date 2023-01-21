<?php
    include('vendor/autoload.php'); //Подключаем библиотеку
    use Telegram\Bot\Api; 

    $telegram = new Api('токен'); //Устанавливаем токен, полученный у BotFather
    $result = $telegram -> getWebhookUpdates(); //Передаем в переменную $result полную информацию о сообщении пользователя
    
    $text = $result["message"]["text"]; //Текст сообщения
    $chat_id = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
    $name = $result["message"]["from"]["username"]; //Юзернейм пользователя

    $status = 0;
    if($text){
        if($status == 0){
             $login = $text;
             $reply = "Введите пароль:";
             $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
             $status = 1;
        }else{
             if($status == 1){
                 $password = $text;
                 session_start();
                 require_once 'connect.php';//проверить запрос на добавление
                 $check_user = mysqli_query($connect, "SELECT * FROM `registeredusers` WHERE `login` = '$login' AND `password`= '$password'");
                 if(mysqli_num_rows($check_user)>0){
                 $user = mysqli_fetch_assoc($check_user);
                 }
                 $name = $user['name'];
                 if($user['login' == $login]){
                     $reply = "Укахите дату посещения (гггг-мм-дд):";
                     $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                     $status = 2;
                 }else{
                     $reply = "Пользователь не найден";
                     $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                     $status = 0;
                 }
             }else{
                  if($status == 2){
                    $regExp = '[0-9]{4}\\-[0-9]{2}\\-[0-9]{2}';
                       //проверка даты результат check_date 1 - корректная дата, 2 - дата в прошлом, 3 - неверный формат
                       //$today = date("Y-m-d H:i:s");
                       //$result=(strtotime($date1)<strtotime($date2)); //$result === true
                       if(preg_match($regExp,$text)){
                         $today = date("Y-m-d");
                         if ((strtotime($text) >= strtotime($today))) {
                            $date = $text;
                            $reply = "Укахите время начала посещения (чч:мм):";
                            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                            $status = 3;
                         }else{
                            $reply = "Указанная дата уже прошла. Укахите дату больше текущей:";
                            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                            $status = 2;
                         }
                       }else{
                            $reply = "Неверный формат даты. Укахите дату посещения (гггг-мм-дд):";
                            $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                            $status = 2;
                       }
                  }else{
                       if($status == 3){
                         $regExpt = '[0-2]{1}[0-9]{1}:[0-6]{1}[0-9]{1}';
                         if(preg_match($regExpt,$text)){
                              $todayt = date("H:i");
                              if ((strtotime($text)>strtotime($todayt)) || (strtotime($date) > strtotime($today))) {
                                   $time_start = $text;
                                   $reply = "Укахите время конца посещения (чч:мм):";
                                   $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                   $status = 4;
                              }else{
                                   $reply = "Указанное время уже прошло. Укахите время больше текущего:";
                                   $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                   $status = 3;
                              }
                         }else{
                              $reply = "Неверный формат времени. Укахите время начала посещения (чч:мм):";
                              $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                              $status = 3;
                         }
                       }
                       }else{
                            if($status == 4){
                              $regExpt = '[0-2]{1}[0-9]{1}:[0-6]{1}[0-9]{1}';
                              if(preg_match($regExpt,$text)){
                                   if ((strtotime($text)>strtotime($time_start))) {
                                        $time_end = $text;
                                        $reply = "Введите номер машины:";
                                        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                        $status = 5;
                                   }else{
                                        $reply = "Время меньше времени начала. Укахите время больше времени начала:";
                                        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                        $status = 4                                   }
                              }else{
                                   $reply = "Неверный формат времени. Укахите время начала посещения (чч:мм):";
                                   $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                   $status = 4;
                              }
                            }else{
                                 if($status == 5){//ввод номера машины
                                   $regExpN = '[а-я,А-Я]{1}[0-9]{3}[а-я,А-Я]{2}';
                                   if (preg_match($regExpN, $text)) {
                                        $number = $text;
                                        $reply = "Введите информацию о посетителях и цели поездки:";
                                        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                        $status = 6;
                                   }else{
                                        $reply = "Неправильный формат номера машины. Введите номер машины в правильном формате (а000аа):";
                                        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                        $status = 5;
                                   }
                                 }else{
                                   if ($status = 6) {
                                        $description = $text;
                                        $reply = $name + ". Ваши гости записаны на прохождение через КПП на " + $date + '. Период действия пропуска с ' + $time_start + ' до ' + $time_end + '. Гостевой номер машины: ' + $number + '. При отсутствии документов удостоверяющих личность проход будет запрещен. ' + $description + '. \\n Вы указали верные данные?';
                                        $telegram->sendMessage([ 'chat_id' => $chat_id, 'text' => $reply]);
                                        $status = 7;
                                   }
                                 }else{
                                   if ($status = 7) {
                                        if (($text == "Да")||($text == "да")) {
                                             session_start();
                                             require_once 'connect.php';
                                             //написать правильные запрос на добавление
                                             mysqli_query($connect, "INSERT INTO `registeredusers` (`id`, `name`, `login`, `car_number`, `password`) VALUES (NULL, '$name', '$login', '$car_number', '$password')");
                                             $status = 0;
                                        }else{
                                             $status = 0;
                                        }
                                   }
                                 }


                            }
                       }
                  }

             }
        }

?>
