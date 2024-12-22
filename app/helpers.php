<?php
if (!function_exists('pre')) {
    function pre($array)
    {
        foreach ($array as $item) {
            echo "<pre>";
            print_r($item);
            echo "</pre>";
        }
    }
}
