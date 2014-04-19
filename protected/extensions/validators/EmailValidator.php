<?php

/**
 * Estende CEmailValidator e aggiunge il controllo dell'esistenza del dominio.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
class EmailValidator extends CEmailValidator {

    public function validateValue($value) {
        if (parent::validateValue($value)) :
            list($address, $host) = explode('@', $value);
            return checkdnsrr($host);
        endif;
        return false;
    }

}
