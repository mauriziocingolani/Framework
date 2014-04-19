<?php

/**
 * Realizza un paragrafo con il messaggio di errore (ERRORE: impossibile...) ed
 * eventualmente l'errore restituito dal server (in base al valore di YII_DEBUG).
 *
 * @property string $message Messaggio di errore.
 * @property CdbException $error Errore restituito dal server.
 * @author Maurizio Cingolani
 * @version 1.0
 */
class DbErrorParagraphWidget extends CWidget {

    public $error;
    public $message;

    public function run() {
        $this->render('application.components.views.widgets.dbErrorParagraphWidget');
    }

}

/* End of file ErrorParagraphWidget.php */
