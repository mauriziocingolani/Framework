<?php
/**
 * @property CDbException $error Eccezione
 * @property string $message Errore che descrive quale operazione è fallita (impossibile ottenere/creare/eliminare...)
 */
$message = array_search($this->error->errorInfo[1], array('Inserimento valore duplicato.' => 1062, 'Violazione di chiave secondaria.' => 1451));
?>

<p class="server_error">
    ERRORE SQL (<?php echo $this->error->errorInfo[1]; ?>). <?php echo Html::encode($this->message); ?>

    <?php if ((defined('YII_DEBUG') && YII_DEBUG === true) || $message !== false) : ?>
        Il server riporta:</p>
    <blockquote class="server_error">

        <?php echo Html::encode(YII_DEBUG === true ? $this->error->getMessage() : $message); ?>

    </blockquote>

<?php else : ?>

    </p>

<?php endif; ?>