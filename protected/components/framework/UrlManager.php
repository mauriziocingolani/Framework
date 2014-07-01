<?php

/**
 * Estende {@link CUrlManager} aggiungendo la possibilità di specificare
 * le regole di routing in più lingue. Nel file di configurazione vanno assegnate
 * le regole nel modo seguente:
 * 
 *    'rules' => array(
 *       '{controller}/{action}' => 'testo comune a tutte le lingue',
 *       '{controller}/{action}' => array(
 *           'lingua1' => 'testo in lingua 1',
 *           'lingua2' => 'testo in lingua 2',
 *           ...
 *       ),
 *    ),...
 * 
 * Nel metodo {@link UrlManager::init()} le regole vengono risistemate nel formato
 * corretto per il routing, mentre quelle originali vengono salvate nella proprietà {@link _rawRules}.
 * Quindi la classe {@link Controller} nel metodo {@link Controller::beforeAction} invoca la funzione
 * {@link UrlManager::checkRouteAgainstLanguages}, che utilizza {@link _rawRules} per capire se
 * l'url della route corrisponde alla lingua attualemente selezionata, e in caso contrario effettua
 * automaticamente il redirect.
 *
 * @author Maurizio Cingolani <mauriziocingolani74@gmail.com>
 * @version 1.0
 */
class UrlManager extends CUrlManager {

    private $_rawRules;

    public function init() {
        $rules = array();
        $this->_rawRules = $this->rules;
        foreach ($this->rules as $action => $name) :
            if (is_array($name)) :
                foreach ($name as $n) :
                    $rules[$n] = $action;
                endforeach;
            else :
                $rules[$name] = $action;
            endif;
        endforeach;
        $this->rules = $rules;
        parent::init();
    }

    /**
     * Restituisce l'url corretto in base alle lingue impostate e alle regole definite nel file di configurazione.
     * Se non è impostato il multilingua viene restituito l'url passato come parametro; stessa cosa se le regole
     * non prevedono nomi diversi per le varie lingue, ovvero se l'elemento di {@link _rawRules} corrispondente 
     * alla route è uno scalare.
     * Se invece le regole prevedono nomi diversi (ovvero se l'elemento corrispondente di {@link _rawRules} è
     * un array), allora viene restituito l'url giusto, ovvero l'elemento dell'array di nomi che corrisponde all'indice
     * della lingua (Yii::app()->session['languageIndex]).
     * 
     * @param string $route Route attuale (controller/action)
     * @param string $url Url Attuale (senza / iniziale)
     * @return string Url corretto
     */
    public function checkRouteAgainstLanguages($route, $url) {
        if (!isset(Yii::app()->params['languages']) || !is_array(Yii::app()->params['languages']))
            return $url;
        if (isset($this->_rawRules[$route])) :
            $r = $this->_rawRules[$route];
            if (is_array($r)) :
                if ($r[Yii::app()->session['languageIndex']] != $url) :
                    return $r[Yii::app()->session['languageIndex']];
                endif;
            endif;
        endif;
        return $url;
    }

}
