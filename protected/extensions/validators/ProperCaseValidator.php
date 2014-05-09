<?php

/**
 * Non è un vero e proprio validator, ma un semplice placeholder per poter
 * assegnare al controllo l'attributo 'data-propercase'.
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0.1
 */
class ProperCaseValidator extends CValidator {

    /**
     * Restituisce sempre true.
     * @param type $object Modello della form
     * @param type $attribute Attributo del modello della form
     * @return boolean true 
     */
    protected function validateAttribute($object, $attribute) {
        return true;
    }

}
