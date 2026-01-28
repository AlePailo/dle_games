<?php

function dd($val) {
    formatted_dump($val);
    die();
}

function formatted_dump($val) {
    echo "<pre>";
    var_dump($val);
    echo "</pre>";
}