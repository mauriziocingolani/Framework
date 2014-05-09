<?php

/**
 * Superclass di tutti i controller.
 * Espone metodi per la gestione dei files css, dei files js e dei breadcrumbs.
 *
 * @author Maurizio Cingolani
 * @version 1.0.7
 */
class Controller extends CController {

    /** Breadcrumbs */
    private $_breadcrumbs;

    /** Lista dei files css da caricare. Il percorso base è /css */
    private $_css;

    /** Lista dei files js da caricare. Ogni elemento contiene il percorso all'interno della cartella /js */
    private $_js;

    /**
     * Inizializza le proprietà $_breadcrumbs, $_css, $_js e imposta il layout di default a 'main'.
     */
    public function __construct($id, $module = null) {
        parent::__construct($id, $module);
        $this->layout = 'main';
        $this->_breadcrumbs = array();
        $this->_css = array();
        $this->_js = array();
    }

    /**
     * Prima di consentire l'esecuzione dell'azione verifica se non sia una richiesta di cambio lingua 
     * (generata da una form contenente un campo di nome 'language') e in tal caso imposta il valore
     * della variabile di sessione 'language' con il valore inviato dalla form. Inoltre assegna l'indice che
     * il linguaggio attuale occupa all'interno dell'array Yii::app()->params['languages'] alla variabile
     * Yii::app()->session['languageIndex'].
     * Se la richiesta non è una POST e il linguaggio non è ancora stato selezionato, allora imposta
     * il linguaggio di default, assegnato a Yii::app()->params['defaultLanguage'].
     * 
     * @param string $action Azione
     * @return boolean True 
     */
    protected function beforeAction($action) {
        if (parent::beforeAction($action)) :
            if (Yii::app()->request->isPostRequest) :
                if (isset($_POST['language'])) :
                    Yii::app()->session['language'] = $_POST['language'];
                    Yii::app()->session['languageIndex'] = $this->_getLanguageIndex();
                endif;
            else :
                if (!isset(Yii::app()->session['language'])) :
                    Yii::app()->session['language'] = Yii::app()->params['defaultLanguage'];
                    Yii::app()->session['languageIndex'] = $this->_getLanguageIndex();
                endif;
            endif;
            return true;
        endif;
        return false;
    }

    /**
     * Restituisce l'indice al quale si trova il linguaggio attualemente selezionato (elemento 'language'
     * dell'array Yii::app()->session) nell'array dei linguaggi definito tra i parametri (elemento 'languages').
     * Restituisce 0 se l'array dei linguaggi non è definito.
     * @return int Indice del linguaggio attualmente selezionato
     */
    private function _getLanguageIndex() {
        $i = 0;
        if (isset(Yii::app()->params['languages'])) :
            foreach (Yii::app()->params['languages'] as $lang => $language) :
                if (Yii::app()->session['language'] == $lang)
                    break;
                $i++;
            endforeach;
        endif;
        return $i;
    }

    /**
     * Imposta 'accessControl' come filtro predefinito.
     * 
     * @return array Lista dei filtri
     */
    public function filters() {
        return array('accessControl');
    }

    /**
     * Popola la variablie $_css con il nome (o i nomi se l'argomento del metodo è un array)  da caricare.
     * Si intende che i files sono contenuti nella cartella /css.
     * Restituisce l'istanza attuale del Controller in modo da consentire il concatenamento.
     * 
     * @param mixed $css Nome del file (o dei files se l'argomento è un array) da caricare (cartella /css)
     * @return type Istanza attuale
     */
    public function addCss($css = null) {
        if ($css === null)
            return;
        if (is_array($css)) :
            $this->_css = array_merge($this->_css, $css);
        elseif (is_string($css)):
            $this->_css[] = $css;
        endif;
        return $this;
    }

    /**
     * Carica i css comuni, assegnati ai parametri di configurazione (Yii::app()->params['css']),
     * quindi inserisce i files css assegnati alla proprietà $_css nelle singole pagine.
     */
    protected function css() {
        $cs = Yii::app()->getClientScript();
        // css principali
        if (isset(Yii::app()->params['css'])) :
            foreach (Yii::app()->params['css'] as $css) :
                $cs->registerCssFile(substr($css, 0, 2) == '//' ? $css : "/css/$css.css");
            endforeach;
        endif;
        // css aggiuntivi
        foreach ($this->_css as $c) :
            $cs->registerCssFile("/css/$c.css");
        endforeach;
    }

    /**
     * Carica i files css con i Google font specificati.
     * I font da caricare vanno assegnati come parametri dell'applicazione mediante un elemento 'googleFont'
     * contenente la lista dei font. Ogni font è costituito da un array con i seguenti elementi:
     * - 'family' : nome del font (gli spazi verranno sostituiti con dei '+')
     * - 'weight' (opzionale) : array con i bold da caricare
     * - 'italic' (opzionale) : array con gli italic da caricare
     * 
     * Es.:
     *     'googleFonts' => array(
     *         array('family' => 'Revalia', 'weight' => array(100, 200, 300, 400, 500, 900), 'italic' => array(400, 900)),
     *         array('family' => 'Droid Sans Mono'),
     *       ), ...
     * genera nell'<head>:
     *    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Revalia:100,200,300,400,500,900,400italic,900italic" />
     *    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Droid+Sans+Mono" />
     */
    protected function googleFonts() {
        if (isset(Yii::app()->params['googleFonts']) && is_array(Yii::app()->params['googleFonts'])) :
            $cs = Yii::app()->getClientScript();
            foreach (Yii::app()->params['googleFonts'] as $font) :
                $weights = array();
                if ((isset($font['weight']) && is_array($font['weight'])) || (isset($font['italic']) && is_array($font['italic']))) :
                    if (isset($font['weight']) && is_array($font['weight']))
                        $weights = array_merge($weights, $font['weight']);
                    if (isset($font['italic']) && is_array($font['italic']))
                        $weights = array_merge($weights, array_map(function($e) {
                                    return $e . 'italic';
                                }, $font['italic']));
                endif;
                $weights = join(',', $weights);
                $cs->registerCssFile('http://fonts.googleapis.com/css?family=' . preg_replace('/[ ]/', '+', $font['family']) . (strlen($weights) > 0 ? ':' . $weights : ''));
            endforeach;
        endif;
    }

    /**
     * Popola la variabile $_js con il nome (o i nomi se l'argomento è un array) dei files js da caricare.
     * Si intende che i files sono contenuti nella cartella /js/[id controller].
     * Restituisce l'istanza attuale del Controller in modo da consentire il concatenamento.
     * 
     * @param mixed $js Nome del file (o array di nomi) all'interno della sottocartella /js/[id controller] da caricare
     * @return Controller Istanza attuale
     */
    public function addControllerJs($js) {
        if (is_array($js)) :
            foreach ($js as $j) :
                $this->_js[] = "$this->id/$j";
            endforeach;
        elseif (is_string($js)):
            $this->_js[] = "$this->id/$js";
        endif;
        return $this;
    }

    /**
     * Popola la variabile $_js con il nome (o i nomi se l'argomento è un array) dei files js da caricare.
     * I nomi devono contenere l'eventuale percorso all'interno della cartella /js.
     * Restituisce l'istanza attuale del Controller in modo da consentire il concatenamento.
     * 
     * @param mixed $js Nome (o nomi se si tratta di un array) dei files js da caricare.
     * @return Controller Istanza attuale
     */
    public function addJs($js) {
        if (is_array($js)):
            $this->_js = array_merge($this->_js, $js);
        else:
            $this->_js[] = $js;
        endif;
        return $this;
    }

    /**
     * Inserisce i file javascript comuni a tutte le pagine, specificati nei parametri di configurazione
     * dell'applicazione. L'array è composto da chiavi che indicano il nome del file (con eventuale
     * percorso all'interno della cartella /js e senza estensione .js) e da valori che indicano la posizione
     * in cui il tag <script> verrà inserito (normalmente CClientScript::POS_HEAD oppure CClientScript::END).
     * Quindi Inserisce alla fine del <body> gli script js assegnati alla proprietà $_js
     * per le singole pagine.
     * 
     */
    protected function js() {
        $cs = Yii::app()->getClientScript();
        // js principale (da parametri)
        if (isset(Yii::app()->params['js'])) :
            foreach (Yii::app()->params['js'] as $file => $pos) :
                $cs->registerScriptFile(substr($file, 0, 2) == '//' ? $file : "/js/$file.js", $pos);
            endforeach;
        endif;
        foreach ($this->_js as $js) :
            $cs->registerScriptFile(substr($file, 0, 2) == '//' || substr($file, 0, 8) == 'https://' ? $file : "/js/$js.js", CClientScript::POS_END);
        endforeach;
    }

    /**
     * Aggiunge alla variabile $_breadcrumbs un elemento con testo ed eventualmente url specificati.
     * 
     * @param string $text Testo del breadcrumb
     * @param string $url Url del breadcrumb
     */
    public function addBreadcrumb($text, $url = null) {
        $this->_breadcrumbs[$text] = $url;
    }

    /**
     * Getter della varibile privata $_breadcrumbs.
     * 
     * @return array Variabile $_breadcrumbs
     */
    public function getBreadcrumbs() {
        return $this->_breadcrumbs;
    }

}

/* End of file Controller.php */
