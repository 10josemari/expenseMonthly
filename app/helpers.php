<?php 

/**
 * Nos manda el active al elemento de menú del navbar
 */
function setActive($routeName){
    return request()->routeIs($routeName) ? 'active' : '';
}

/**
 * 
 */
function getMonth($month){
    switch ($month) {
        case '1':
            return 'Enero';
        break;
        case '2':
            return 'Febrero';
        break;
        case '3':
            return 'Marzo';
        break;
        case '4':
            return 'Abril';
        break;
        case '5':
            return 'Mayo';
        break;
        case '6':
            return 'Junio';
        break;
        case '7':
            return 'Julio';
        break;
        case '8':
            return 'Agosto';
        break;
        case '9':
            return 'Septiembre';
        break;
        case '10':
            return 'Octubre';
        break;
        case '11':
            return 'Noviembre';
        break;
        case '12':
            return 'Diciembre';
        break;
    }
}

/**
 * Obtenemos el mes siguiente
 */
function getNextMonth($month){
    switch ($month) {
        case '1':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '2':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '3':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '4':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '5':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '6':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '7':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '8':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '9':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '10':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '11':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '12':
            $month = 1;
            $year = date('Y')+1;
            return getMonth($month)." - ".$year;
        break;
    }
}