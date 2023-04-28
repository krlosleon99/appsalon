<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function esUltimo(string $actual, string $proximo) : bool {
    if( $actual !== $proximo ) {
        return true;
    } else {
        return false;
    }
}

// Verifica si un usuario está autenticado o no
function isAuth() : void {
	if( !isset($_SESSION['login']) ) {
		header('Location: /');
	}
}

// Verifica si un usuario ADMIN está autenticado o no
function isAdmin() : void {
	if( !isset($_SESSION['admin']) ) {
		header('Location: /');
	}
}