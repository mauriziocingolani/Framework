<?php

/**
 * Crea un tag <div> (id: "main-modal") da usare per il messaggio modale di attesa
 * durante le chiamate Ajax o i redirect.
 * Lo <span> con id="main-modal-title" consente di impostare il titolo del dialog,
 * mentre la <div> con id="main-modal-content" il contenuto.
 *
 * @author Maurizio Cingolani
 */
class ModalDialogWidget extends CWidget {

    public function run() {
        $this->render('application.components.views.widgets.modalDialogWidget');
    }

}

/* End of file ModalDialog.php */
