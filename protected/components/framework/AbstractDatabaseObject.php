<?php

/**
 * Superclass per tutti i modelli delle tabelle del database. Fornisce una prima implementazione
 * (tuttavia ancora astratta) dell'interfaccia DatabaseObject.
 *
 * @author Maurizio Cingolani
 * @version 1.0.4
 */
abstract class AbstractDatabaseObject extends CActiveRecord implements DatabaseObject {

    /** Data e ora di creazione del record */
    public $Created;
    public $_Created;

    /** Utente che ha creato il record */
    public $CreatedBy;

    /** Data e ora di modifica del record */
    public $Updated;
    public $_Updated;

    /** Ultimo utente che ha modificato il record */
    public $UpdatedBy;

    protected function beforeSave() {
        if ($this->isNewRecord) :
            $this->Created = date('Y-m-d H:i:s ');
            $this->CreatedBy = Yii::app()->user->id;
        else :
            $this->Created = date('Y-m-d H:i:s ');
            $this->UpdatedBy = Yii::app()->user->id;
        endif;
        return true;
    }

    /**
     * Questo metodo fa da vocabolario per gli errori SQL generati dalle azioni fatte dagli utenti (inserimenti,
     * elimiinazioni, aggiornamenti) che violano chiavi univoche o secondarie. Ogni modello dovrebbe fare
     * override di questo metodo, definendo la propria lista di errori personalizzata.
     * Un buon metodo, se non si vuole riscrivere tutta la lista, è quello di usare l'operatore '+' per fare il merge
     * dei due array (questo comune e quello del singolo modello), avendo l'accortezza di mettere sempre
     * prima quello del modello; in questo modo ogni modello può sovrascrivere solo i messaggi dei codici
     * di errore che gli interessano, lasciando per tutti gli altri quello di default definito in questa classe.
     * @return array Lista degli errori
     */
    public static function errors() {
        return array(
            1062 => 'Duplicate entry.',
            1451 => 'Cannot delete or update a parent row: a foreign key constraint fails',
        );
    }

    /**
     * Restituisce la lista dei valori di un campo ENUM di una tabella.
     * 
     * @param string $table Tabella
     * @param string $field Campo della tabella
     * @return CList Lista dei valori 
     */
    protected static function GetEnumValues($table, $field) {
        try {
            $data = new CList;
            $connection = Yii::app()->db;
            $row = $connection->createCommand("SHOW COLUMNS FROM {$table} WHERE Field = '{$field}' ")->query()->read();
            $type = $row['Type'];
            preg_match('/^enum\((.*)\)$/', $type, $matches);
            foreach (explode(',', $matches[1]) as $value) {
                $data->add(trim($value, "'"));
            }
            return $data;
        } catch (CDbException $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Elimina il record identificato dall'ID $pk con una semplice invocazione
     * del metodo deleteByPk.
     * @param CActiveRecord $model Modello del record da eliminare
     * @param int $pk ID del record da eliminare
     * @return boolean True se l'eliminazione ha avuto successo
     */
    protected static function SimpleDeleteRecord(CActiveRecord $model, $pk) {
        try {
            return $model->deleteByPk($pk);
        } catch (CDbException $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Restituisce il record identificato dall'ID $pk con una semplice invocazione
     * del metodo findByPk.
     * @param CActiveRecord $model Modello del record da recuperare
     * @param type $pk ID del record
     * @return CActiveRecord Record identificato dall'ID $pk
     */
    protected static function SimpleReadRecord(CActiveRecord $model, $pk) {
        try {
            return $model->findByPk($pk);
        } catch (CDbException $ex) {
            return $ex->getMessage();
        }
    }

}

/* End of file AbstractDatabaseObject.php */
