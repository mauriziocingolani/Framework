<?php

/**
 * Aggiunge la proprietà {@link pattern} a un generico CValidator
 * per eseguire la validazione di un orario.
 *
 * @author Maurizio Cingolani
 * @version 1.0.1
 */
class TimeValidator extends CValidator {

    /** Pattern di validazione dell'oraio in formato HH:MM */
    public $pattern = '^(1?[0-9]|2[0-3]):[0-5][0-9]$';

    /** Implementazione vuota (restituisce sempre true) del metodo
     * astratto della suprclass.
     * @param type $object Modello della form
     * @param type $attribute Attributo del modello della form
     * @return boolean True
     */
    protected function validateAttribute($object, $attribute) {
        return true;
    }

}

/* End of file TimeValidator.php */
