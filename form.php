<?php

//incluid heare name fealds
if(isset($_POST['nheader'])){ $text = $_POST['nheader'];}
if(isset($_POST['nbrief'])) { $text = $_POST['nbrief']; }
if(isset($_POST['neditor1'])) { $text = $_POST['neditor1'];}

if($text){

    $errorCode = array(1 => 'Слова нет в словаре.',
        2 => 'Повтор слова.',
        3 => 'Неверное употребление прописных и строчных букв.',
        4 => 'Текст содержит слишком много ошибок. При этом приложение может отправить Яндекс.Спеллеру оставшийся непроверенным текст в следующем запросе.'
    );

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://speller.yandex.net/services/spellservice.json/checkText',
        CURLOPT_USERAGENT => 'ctrlv cURL Request',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(

            text => "$text",
            format => 'html'
        )
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    if (isset($response)) {
        $errors = json_decode($response);
        $counterrors = count($errors);
        if ($counterrors > 0) {

            $html = "";
            foreach ($errors as $error) {
                $html .= "<strong>" . $error->word . "</strong> - " . $errorCode[$error->code] . "</br>";
                if (isset($error->s)) {

                    foreach ($error->s as $key => $value) {
                        $html .= " -" . $value . " </br>";
                    }
                }
            }
            $html .= "Ошибок: <b>" . $counterrors . "</b>";
            echo $html;
        } else {
            echo "true";
        }

    }
}
			 else { echo "bug )";}