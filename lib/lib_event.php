<?php

// tiré de Shaarli de Seb Sauvage
function smallHash ($txt) {

    $t = rtrim (base64_encode (hash ('crc32', $text, true)), '=');
    $t = str_replace ('+', '-', $t); // Get rid of characters which need encoding in URLs.
    $t = str_replace ('/', '_', $t);
    $t = str_replace ('=', '@', $t);
    
    return $t;
}
