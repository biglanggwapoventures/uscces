<?php

function is_valid_date($date, $format = 'Y-m-d') {
    if (!is_array($date)) {
        $date = (array) $date;
    }
    return array_filter($date, function($var) use($format) {
                $d = DateTime::createFromFormat($format, $var);
                return $d && $d->format($format) == $var;
            }) === $date;
}

function format_date($date, $format = 'd-M-Y')
{
	return date_create($date)->format($format);
}
