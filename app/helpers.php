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
        case '11':
            return 'Noviembre';
        break;
    }
}