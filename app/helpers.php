<?php 

/**
 * Nos manda el active al elemento de menú del navbar
 */
function setActive($routeName){
    return request()->routeIs($routeName) ? 'active' : '';
}

/**
 * Función para devolver ceros al principio
 */
function zero_fill ($valor, $long = 0)
{
    return str_pad($valor, $long, '0', STR_PAD_LEFT);
}

/**
 * Devuelve el número de paginación. Por defecto devuelve 12 pero se puede configurar otro número desde el .env
 */
function numPaginate(){
    return "12";
}

/**
 * Comprobamos si la el mes-año recibido es igual al actual (posibilidad de borrar)
 */
function comprobateDate($month,$year){
    $actually = date('m').date('Y');
    if($month.$year == $actually){
        return true;
    } else {
        return false;
    }
}

/**
 * Retornamos el nombre del mes pasado.
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
 * Retornamos el nombre del mes siguiente al pasado
 */
function getNextMonthTitle($month){
    switch ($month) {
        case '01':
            return getMonth($month+1);
        break;
        case '02':
            return getMonth($month+1);
        break;
        case '03':
            return getMonth($month+1);
        break;
        case '04':
            return getMonth($month+1);
        break;
        case '05':
            return getMonth($month+1);
        break;
        case '06':
            return getMonth($month+1);
        break;
        case '07':
            return getMonth($month+1);
        break;
        case '08':
            return getMonth($month+1);
        break;
        case '09':
            return getMonth($month+1);
        break;
        case '10':
            return getMonth($month+1);
        break;
        case '11':
            return getMonth($month+1);
        break;
        case '12':
            $month = "01";
            return getMonth($month);
        break;
    }
}

/**
 * Retornamos el mes-año anterior al pasado. Lo devolvemos en formato 12-2021
 */
function getRestMonth($month){
    switch ($month) {
        case '01':
            $month = 12;
            $year = date('Y')-1;
            return $month."-".$year;
        break;
        case '02':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '03':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '04':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '05':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '06':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '07':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '08':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '09':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '10':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '11':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
        case '12':
            return zero_fill(($month-1),2)."-".date('Y');
        break;
    }
}

/**
 * Retornamos el mes-año posterior al pasado. Lo devolvemos en formato 12-2021 (posibilidad de borrar)
 */
function getNextMonth($month){
    switch ($month) {
        case '01':
            return ($month+1)." - ".date('Y');
        break;
        case '02':
            return ($month+1)." - ".date('Y');
        break;
        case '03':
            return ($month+1)." - ".date('Y');
        break;
        case '04':
            return ($month+1)." - ".date('Y');
        break;
        case '05':
            return ($month+1)." - ".date('Y');
        break;
        case '06':
            return ($month+1)." - ".date('Y');
        break;
        case '07':
            return ($month+1)." - ".date('Y');
        break;
        case '08':
            return ($month+1)." - ".date('Y');
        break;
        case '09':
            return ($month+1)." - ".date('Y');
        break;
        case '10':
            return ($month+1)." - ".date('Y');
        break;
        case '11':
            return ($month+1)." - ".date('Y');
        break;
        case '12':
            $month = "01";
            $year = date('Y')+1;
            return $month." - ".$year;
        break;
    }
}

/**
 * Retornamos el nombre del mes siguiente al pasado
 */
function getNextYear($month,$year){
    switch ($month) {
        case '12':
            $year = $year + 1;
        break;
    }
    return $year;
}

/**
 * Retornamos la suma de las monedas parala vista hucha
 */
function getTotal($type,$amount){
    return $type * $amount;
}

/**
 * Damos formato a las fechas de base de datos [Y-m-d H:i:s] a [d-m-Y H:i:s]
 */
function getDateFormat($date){
    $data = explode(" ",$date);

    $date = explode("-",$data[0]);
    $date = $date[2]."-".$date[1]."-".$date[0];
    $hour = $data[1];

    return $date." ".$hour;
}

/**
 * Obtenemos el % respecto a un total
 */
function getPercent($value,$total){
    return round(($value * 100) / $total,2);
}

/**
 * Comprobamos si los ids de los salarios son iguales
 */
function comprobateId($idFixed,$idChange,$countSalaries){
    if($idFixed == $idChange && $countSalaries[$idChange][0] < 2){
        return true;
    } else{
        return false;
    }

}