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
        case '01':
            return 'Enero';
        break;
        case '02':
            return 'Febrero';
        break;
        case '03':
            return 'Marzo';
        break;
        case '04':
            return 'Abril';
        break;
        case '05':
            return 'Mayo';
        break;
        case '06':
            return 'Junio';
        break;
        case '07':
            return 'Julio';
        break;
        case '08':
            return 'Agosto';
        break;
        case '09':
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
        case '01':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '02':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '03':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '04':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '05':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '06':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '07':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '08':
            return getMonth($month+1)." - ".date('Y');
        break;
        case '09':
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

/**
 * 
 */
function getRestMonth($month){
    switch ($month) {
        case '01':
            $month = 12;
            $year = date('Y')-1;
            return $month."-".$year;
        break;
        case '02':
            return ($month-1)."-".date('Y');
        break;
        case '03':
            return ($month-1)."-".date('Y');
        break;
        case '04':
            return ($month-1)."-".date('Y');
        break;
        case '05':
            return ($month-1)."-".date('Y');
        break;
        case '06':
            return ($month-1)."-".date('Y');
        break;
        case '07':
            return ($month-1)."-".date('Y');
        break;
        case '08':
            return ($month-1)."-".date('Y');
        break;
        case '09':
            return ($month-1)."-".date('Y');
        break;
        case '10':
            return ($month-1)."-".date('Y');
        break;
        case '11':
            return ($month-1)."-".date('Y');
        break;
        case '12':
            return ($month-1)."-".date('Y');
        break;
    }
}