<?php

/**
 * Eccezione sollevata quando da un'azione di controller che riceve come parametro
 * un id di oggetto database non valido. 
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
class InvalidDatabaseObjectException extends CHttpException {

    /**
     * Crea una nuova istanza della classe, inizializzando il messaggio di errore.
     * @param string $description Nome dell'oggetto cui fa riferimento la pagina (es. "La ditta", "Il referente")
     * @param boolean $isFemale Indica se l'oggetto deve avere suffisso femminile
     */
    public function __construct($description, $isFemale = false) {
        $o = $isFemale === true ? 'a' : 'o';
        parent::__construct(410, "$description richiest$o non esiste o potrebbe essere stat$o eliminat$o nel frattempo. " .
                "Per evitare questo tipo di errori sei pregato di utilizzare sempre i link che trovi nelle pagine e di non " .
                "modificare mai manualmente i valori riportati nella barra dell'indirizzo.");
    }

}
