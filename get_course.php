<?php
if ($argc != 3 || explode("=", $argv[1])[0]  != "--from" || explode("=", $argv[2])[0]  != "--to"){
?>
  Возможно вы ошиблись, вот пример:
  <?php echo $argv[0]; ?> --from=USD --to=EUR
<?php
error_reporting(E_ALL);
} else {
    $from = explode("=", $argv[1]);
    $to = explode("=", $argv[2]);

    if (isset($to[1]) && $to[1] == "BYN"){
        $fromCurrency = getData(explode("=", $argv[1]));
        $result = $fromCurrency->Cur_OfficialRate;
        echo round($result, 2);
    }elseif (isset($from[1]) && $from[1] == "BYN"){
        $toCurrency = getData(explode("=", $argv[2]));
        $two = $toCurrency->Cur_OfficialRate;
        $toCureScale = $toCurrency->Cur_Scale;
        $result = $toCureScale / $two;
        echo round($result, 2);
    }else if (isset($from[1]) && isset($to[1])){
        $fromCurrency = getData(explode("=", $argv[1]));
        $toCurrency = getData(explode("=", $argv[2]));
        $one = $fromCurrency->Cur_OfficialRate;
        $two = $toCurrency->Cur_OfficialRate;
        $fromCureScale = $fromCurrency->Cur_Scale;
        $toCureScale = $toCurrency->Cur_Scale;
        $result = ($one / $fromCureScale) / ($two / $toCureScale);
        echo round($result, 2);
    } else {
        echo "Возможно, где-то ошибка, проверьте аргументы";
    }
}

function getData($val){
    $option = array(
        'parammode' => '2',
    );
    $url = 'https://www.nbrb.by/api/exrates/rates/'. $val[1];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url.'?'.http_build_query($option));

    $response = curl_exec($ch);
    if(!$response){die("Ошибка соединения");}
    $data = json_decode($response, false);
    curl_close($ch);
    if (json_last_error() != JSON_ERROR_NONE){
        die("Сервер вернул неверные данные, возможно, где-то ошибка.");
    }
    return $data;
}
?>

