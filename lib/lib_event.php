<?php

// tiré de Shaarli de Seb Sauvage
function small_hash ($txt) {

    $t = rtrim (base64_encode (hash ('crc32', $txt, true)), '=');
    $t = str_replace ('+', '-', $t); // Get rid of characters which need encoding in URLs.
    $t = str_replace ('/', '_', $t);
    $t = str_replace ('=', '@', $t);
    
    return $t;
}

// vérifie que tous les éléments de $required sont renseignés
// return les éléments qui ne sont pas renseignés
function check_missing (array $required) {
	$missing = array ();
	
	foreach ($required as $key => $elt) {
		if ($elt == false) {
			$missing[] = $key;
		}
	}
	
	return $missing;
}

// permet de parser un nom utilisateur type "Prénom <adresse@mail.com>"
function parse_user ($user_tmp) {
	$user = array ();
	$pattern = '/(\S+) <([a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)*\@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)*\.[a-zA-Z]{2,4})>/';
	
	if (preg_match ($pattern, $user_tmp)) {
		$user['name'] = preg_replace ($pattern, '\\1', $user_tmp);
		$user['mail'] = preg_replace ($pattern, '\\2', $user_tmp);
		$user['avatar'] = 'https://www.gravatar.com/avatar/' . md5 (strtolower (trim ($user['mail']))) . '?d=' . urlencode ('http://marienfressinaud.fr/avatar_default.png') . '&s=42';
	} else {
		$user['name'] = htmlspecialchars ($user_tmp);
		$user['avatar'] = 'http://marienfressinaud.fr/avatar_default.png';
	}
	
	return $user;
}

// transforme un timestamp en date "normale"
function timestamptodate ($t, $hour = true) {
	$jour = date ('d', $t);
	$mois = date ('m', $t);
	$annee = date ('Y', $t);
	
	switch ($mois) {
	case 01:
		$mois = 'janvier';
		break;
	case 02:
		$mois = 'février';
		break;
	case 03:
		$mois = 'mars';
		break;
	case 04:
		$mois = 'avril';
		break;
	case 05:
		$mois = 'mai';
		break;
	case 06:
		$mois = 'juin';
		break;
	case 07:
		$mois = 'juillet';
		break;
	case 08:
		$mois = 'août';
		break;
	case 09:
		$mois = 'septembre';
		break;
	case 10:
		$mois = 'octobre';
		break;
	case 11:
		$mois = 'novembre';
		break;
	case 12:
		$mois = 'décembre';
		break;
	}
	
	$date = $jour . ' ' . $mois . ' ' . $annee;
	if ($hour) {
		return $date . date (' \à H\:i', $t);
	} else {
		return $date;
	}
}
