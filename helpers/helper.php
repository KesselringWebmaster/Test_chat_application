<?php

if (!function_exists('d')) {

    function d($var = 'Step 1', $die = true) {
        $callers = debug_backtrace();
        // print_r($callers);
        echo "==" . @$callers[1]['file'] . "==== " . @$callers[1]['class'] . " :  " . @$callers[1]['function'] . "()    Line: " . @$callers[1]['line'] . "=";
        echo '<pre style="font-size:140%;font-weight:bold;">';
        var_dump($var);
        echo '</pre>';
        echo '=========================================================';
        if ($die)
            exit;
    }

}

if (!function_exists('is_empty')) {

    function is_empty($object) {
        $tmp = (array) $object;
        return (empty($tmp));
    }

}