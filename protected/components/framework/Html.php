<?php

/**
 * Estende la classe CHtml con alcuni metodi di utilità.
 *
 * @author Maurizio Cingolani
 * @version 1.0.2
 */
class Html extends CHtml {

    /**
     * Questo metodo, che riceve come argomenti i messaggi nelle varie lingue, permette di sceglierne uno
     * in base alla lingua attualemente selezionata.
     * Il numero di argomenti deve rispecchiare il numero e l'ordine delle lingue, definite nell'array Yii::app()->params['languages'].
     * Quindi il metodo restituisce l'argomento corrispondente alla lingua attualmente selezionata.
     * Per velocizzare l'esecuzione ed evitare di cercare la lingua attuale (assegnata alla variabile Yii::app()->session['language'])
     * a ogni invocazione del metodo, viene utilizzata la variabile Yii::app()->session['languageIndex']; questa viene impostata
     * a ogni cambio lingua dal metodo {@link Controller::beforeAction}, e indica in che posizione dell'array delle
     * lingue si trova quella attualmente selezionata. In questo modo è immediato decidere quale argomento
     * deve essere restituito senza attraversare l'array delle lingue.
     * 
     * @return string Messaggio nella lingua selezionata
     * @throws CException Quando il numero di argomenti non coincide con il numero di linguaggi impostati in Yii::app()->params['languages']
     */
    public static function MultilanguageText() {
        if (!isset(Yii::app()->params['languages']) || func_num_args() !== count(Yii::app()->params['languages'])) :
            throw new CException(__METHOD__ . ' : the number of arguments doesn\'t match the number of languages.');
        endif;
        if (!isset(Yii::app()->session['languageIndex']))
            return func_get_arg(0);

        return func_get_arg(Yii::app()->session['languageIndex']);
    }

}

/* End of file Html.php */