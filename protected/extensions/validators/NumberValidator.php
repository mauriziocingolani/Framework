<?php

/**
 * Validator per campi numerici. Estende CNumberValidator aggiungendo
 * la possibilità di specificare il numero di decimali obbligatori e il carattere
 * separatore dei decimali.
 *
 * @author Maurizio Cingolani
 * @version 1.0.1
 */
class NumberValidator extends CNumberValidator {

    /** Numero di decimali obbligatori */
    public $decimals;

    /** Carattere separatore per i decimali (default: virgola) */
    public $separator = ',';

    /**
     * Se è stato impostato un numero obbligatorio di decimali restituisce il 
     * pattern per la validazione tenendo conto del numero di cifre dopo il punto,
     * altrimenti restituisce il pattern di default.
     * @return string Pattern per i numeri float
     */
    public function getNumberPattern() {
        return '/^\s*[-+]?[0-9]*' .
                ($this->separator === '.' ? '\.' : $this->separator) .
                '?[0-9]' .
                ((int) $this->decimals > 0 ? '{' . (int) $this->decimals . '}' : '+') .
                '([eE][-+]?[0-9]+)?\s*$/';
    }

}

/* End of file NumberValidator.php */
