<?php

/**
 * 
 *
 * @author Maurizio Cingolani
 * @version 1.0.9
 */
class Bootstrap extends CApplicationComponent {

    /**
     * Crea un  tag <a> con le opportune classi Bootstrap (btn, btn-lg, ...).
     * 
     * @param string $text Testo del pulsante
     * @param array $htmlOptions Opzioni
     * @param array $classes Lista delle classi da applicare (lg, md, primary, ...) senza prefisso 'btn-'
     * @return string Codice html del pulsante
     */
    public static function AnchorButton($text, $url, array $htmlOptions = null, array $classes = null) {
        if (is_array($classes)) :
            $classes = array_map(function($e) {
                return "btn-$e";
            }, $classes);
        else :
            $classes = array();
        endif;
        $htmlOptions = self::AddClasses(array_merge(array('btn'), $classes), $htmlOptions);
        $htmlOptions['role'] = 'button';
        return Html::link($text, $url, $htmlOptions);
    }

    /**
     * Crea un  pulsante <button> con le opportune classi Bootstrap (btn, btn-lg, ...).
     * 
     * @param string $type Tipo di pulsante (button, submit,)
     * @param string $text Testo del pulsante
     * @param array $htmlOptions Opzioni
     * @param array $classes Lista delle classi da applicare (lg, md, primary, ...) senza prefisso 'btn-'
     * @return string Codice html del pulsante
     */
    public static function Button($type, $text, array $htmlOptions = null, array $classes = null) {
        if (is_array($classes)) :
            $classes = array_map(function($e) {
                return "btn-$e";
            }, $classes);
        else :
            $classes = array();
        endif;
        $htmlOptions = self::AddClasses(array_merge(array('btn'), $classes), $htmlOptions);
        $htmlOptions['type'] = $type;
        return Html::tag('button', $htmlOptions, $text, true);
    }

    public static function CheckBox(CModel $model, $attribute, array $htmlOptions = null) {
        $htmlOptions = self::AddClasses();
        $field = self::InputField('checkbox', $model, $attribute);
        return $field;
    }

    /**
     * Crea il tag <input> con le opportune classi Bootstrap e con icona 'freccia giù' sulla destra.
     *  Per il resto è un semplice wrapper per il metodo {@link Html::activeDropDownList}.
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param type $data Dati con cui popolare il campo
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function ComboSelectField(CModel $model, $attribute, array $data = null, array $htmlOptions = null) {
        $htmlOptions = self::AddClasses();
        self::CreateDataProps($model->getValidators($attribute), $htmlOptions);
        $htmlOptions['data-combo-select'] = 'true';
        $field = self::TextField($model, $attribute, $htmlOptions);
        $handle = Html::link(
                        '<span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>', '#'
                        , array('id' => get_class($model) . "_{$attribute}_button", 'onclick' => 'return false;'));
        return $field . $handle;
    }

    /**
     * Crea un input di tipo text con le opportune classi Bootstrap e con gli attributi data-... per la validazione.
     * Wrapper per il metodo {@link Bootstrap::InputField}.
     * @param CModel $model Modello della form
     * @param string $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag Html
     */
    public static function CurrencyField(CModel $model, $attribute, array $htmlOptions = null) {
        $htmlOptions = self::AddClasses(array('currency'));
        self::CreateDataProps($model->getValidators($attribute), $htmlOptions);
        $htmlOptions['data-currency'] = 'true';
        $field = self::TextField($model, $attribute, $htmlOptions);
        return $field;
    }

    /**
     * Crea un input di tipo text con le opportune classi Bootstrap e con gli attributi data-... per la validazione.
     * Wrapper per il metodo {@link Bootstrap::InputField}.
     * 
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function DateField(CModel $model, $attribute, array $htmlOptions = null) {
        return self::InputField('text', $model, $attribute, $htmlOptions);
    }

    public static function Editor(CModel $model, $attribute) {
        $tag = Html::openTag('div', array(
                    'id' => get_class($model) . ($attribute ? '_' . $attribute : '' ),
                    'class' => 'form-control-static summernote',
                    'data-sn-editor' => 'true',
        ));
        if ($model->{$attribute})
            $tag.=Html::decode($model->{$attribute});
        $tag.=Html::closeTag('div');
        return Html::decode($tag);
    }

    /** Crea un input di tipo text con le opportune classi Bootstrap e con gli attributi data-... per la validazione.
     * Wrapper per il metodo {@link Bootstrap::InputField}.
     * 
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function EmailField(CModel $model, $attribute, array $htmlOptions = null) {
        return self::InputField('text', $model, $attribute, $htmlOptions);
    }

    /**
     * Crea un tag <div> (id: "{nome classe CModel}_{nome attributo}_error") con le opportune classi Bootstrap 
     * che definiscono un alert di errore e la classe "form-error" per lo stile aggiuntivo.
     * Consente di visualizzare gli errori di validazione per i campi della form grazie allo 
     * <span> interno (id: "{nome classe CModel}_errormessage").
     * 
     * @param CModel $model Modello della form
     * @param string $attribute Attributo del modello
     * @return string Tag html
     */
    public static function ErrorDiv(CModel $model, $attribute = null) {
        $tag = Html::openTag('div', array(
                    'id' => get_class($model) . ($attribute ? '_' . $attribute : '' ) . '_error',
                    'class' => 'form-error alert alert-danger',
        ));
        $tag.=Html::tag('span', array('id' => get_class($model) . ($attribute ? '_' . $attribute : '' ) . '_errormessage',));
        $tag.=Html::closeTag('div');
        return Html::decode($tag);
    }

    /**
     * Crea un tag <div> (id: "{nome classe CModel}_message") con le opportune classi Bootstrap
     * che definiscono un alert di errore e la classe "form-message" per lo stile aggiuntivo.
     * Usato di solito per il messaggio di errore o successo al submit della form.
     * 
     * @param CModel $model Modello della form
     * @return string Tag html
     */
    public static function FormMessage(CModel $model) {
        return Html::decode(Html::tag('div', array(
                            'id' => get_class($model) . '_message',
                            'class' => 'form-message alert',
                                ), '', true));
    }

    /**
     * Crea un tag <label> collegato al campo della form con classe
     * Bootstrap 'control-label'.
     * 
     * @param CModel $model Modello della form
     * @param string $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function Label(CModel $model, $attribute, $htmlOptions = null) {
        $htmlOptions = self::AddClasses(array('control-label'), $htmlOptions);
        return Html::activeLabel($model, $attribute, $htmlOptions);
    }

    /**
     * Crea un input di tipo password con le opportune classi Bootstrap e con gli attributi data-... per la validazione.
     * Wrapper per il metodo {@link Bootstrap::InputField}.
     * 
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function PasswordField(CModel $model, $attribute, array $htmlOptions = null) {
        return self::InputField('password', $model, $attribute, $htmlOptions);
    }

    public static function PictureDiv(CModel $model, $attribute, array $htmlOptions = null, array $options = array()) {
        $tag = Html::openTag('div', array(
                    'id' => get_class($model) . ($attribute ? '_' . $attribute : '' ),
                    'class' => 'picture_dnd_handler form-control-static',
                    'data-picure-dnd' => 'true',
        ));
        $tag.=Html::tag('span', array(
                    'id' => get_class($model) . ($attribute ? '_' . $attribute : '' ) . '_span',
                    'style' => 'display: ' . (isset($model->{$attribute}) ? 'none' : 'block') . ';',
                        ), isset($options['dropMessage']) ? $options['dropMessage'] : 'Trascina qui');
        $tag.=Html::tag('img', array(
                    'id' => get_class($model) . ($attribute ? '_' . $attribute : '' ) . '_img',
                    'class' => 'img-responsive img-thumbnail',
                    'alt' => '',
                    'style' => 'display: ' . (isset($model->{$attribute}) ? 'block' : 'none') . ';',
                    'src' => $model->{$attribute},
        ));
        $tag.=Html::openTag('p');
        $tag.=Html::tag('span', array('class'=>'btn btn-xs btn-primary btn-file'), 'Scegli... <input type="file">');
        $tag.=self::Button('button', 'Pulisci', array(
                    'id' => get_class($model) . ($attribute ? '_' . $attribute : '' ) . '_clear',
                    'data-toggle' => 'tooltip',
                    'title' => 'Clicca per rimuovere la foto attuale',
                    'style' => 'display: ' . (isset($model->{$attribute}) ? 'block' : 'none') . ';'
                        ), array('danger', 'xs'));
        $tag.=Html::closeTag('p');
        $tag.=Html::closeTag('div');
        return Html::decode($tag);
    }

    /**
     * Crea il tag <select> con le opportune classi Bootstrap. Per il resto è un semplice
     * wrapper per il metodo {@link Html::activeDropDownList}.
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param type $data Dati con cui popolare il <select/>
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function SelectField(CModel $model, $attribute, $data, array $htmlOptions = null) {
        $htmlOptions = self::AddClasses(array('form-control'), $htmlOptions);
        self::CreateDataProps($model->getValidators($attribute), $htmlOptions);
        return Html::activeDropDownList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Crea un campo <textarea> con le opportune classi Bootstrap.
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function TextareaField(CModel $model, $attribute, array $htmlOptions = null) {
        $htmlOptions = self::AddClasses(array('form-control'), $htmlOptions);
        self::CreateDataProps($model->getValidators($attribute), $htmlOptions);
        return Html::activeTextArea($model, $attribute, $htmlOptions);
    }

    /** Crea un input di tipo testo con le opportune classi Bootstrap e con gli attributi data-... per la validazione.
     * Wrapper per il metodo {@link Bootstrap::InputField}.
     * 
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function TextField(CModel $model, $attribute, array $htmlOptions = null) {
        return self::InputField('text', $model, $attribute, $htmlOptions);
    }

    /** Crea un input di tipo text con le opportune classi Bootstrap e con gli attributi data-... per la validazione.
     * Wrapper per il metodo {@link Bootstrap::InputField}.
     * 
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    public static function TimeField(CModel $model, $attribute, array $htmlOptions = null) {
        return self::InputField('text', $model, $attribute, $htmlOptions);
    }

    /**
     * Scorre l'elenco delle regole di validazione e appende all'array $htmlOptions (passato per riferimento)
     * gli opportuni attributi che verranno inseriti nel tag per la validazione via Javascript.
     * Regole siupportate e relativi attributi:
     * 
     * <b>CRequiredValidator</b>
     * <ul>
     * <li>data-required="true"</li>
     * <li>data-missing-message="{$validator->message}"</li>
     * </ul>
     * 
     * <b>CRegularExpressionValidator</b>
     * <ul>
     * <li>data-regexp="{$validator->pattern}"</li>
     * <li>data-invalid-message="{$validator->message}"</li>
     * </ul>
     * 
     * <b>CEmailValidator</b>
     * <ul>
     * <li>data-email="true"</li>
     * <li>data-regexp="{$validator->pattern}"</li>
     * <li>data-invalid-message="{$validator->message}"</li>
     * </ul>
     * 
     * <b>CDateValidator</b>
     * <ul>
     * <li>data-date="true"</li>
     * <li>data-invalid-message="{$validator->message}"</li>
     * </ul>
     * 
     * <b>TimeValidator</b>
     * <ul>
     * <li>data-date="{$validator->pattern}"</li>
     * <li>data-invalid-message="{$validator->message}"</li>
     * </ul>
     * 
     * <b>CNumberValidator</b>
     * <ul>
     * <li>data-number="int" oppure "float" in base al valore di {$validation->integerOnly}</li>
     * <li>data-regexp="{$validator->integerPattern}" oppure {$validator->numberPattern} in base al valore di {$validator->integerOnly}</li>
     * <li>data-invalid-message="{$validator->message}"</li>
     * <li>data-min="{$validator->min}"</li>
     * <li>data-min-message="{$validator->tooSmall}"</li>
     * <li>data-max="{$validator->max}"</li>
     * <li>data-max-message="{$validator->tooBig}"</li>
     * <li>
     * </ul>      
     * 
     * <b>CCompareValidator</b>
     * <ul>
     * <li>data-compare="{$validator->compareAttribute}"</li>
     * <li>data-compare-operator="{$validator->operator}"</li>
     * <li>data-compare-message="{$validator->message}"</li>
     * </ul>
     * 
     * <b>UpperCaseValidator</b>
     * <ul>
     * <li>data-uppercase="true"</li>
     * </ul>
     * 
     * <b>LowerCaseValidator</b>
     * <ul>
     * <li>data-lowercase="true"</li>
     * </ul>
     * 
     * <b>ProperCaseValidator</b>
     * <ul>
     * <li>data-propercase="true"</li>
     * </ul>
     * 
     * <b>TrimValidator</b>
     * <ul>
     * <li>data-trim="true"</li>
     * </ul>
     * 
     * @param array $validators Regole di validazione per l'attributo del modello della form
     * @param array $htmlOptions Opzioni (array passato per riferimento)
     */
    private static function CreateDataProps(array $validators, array &$htmlOptions) {
        if (!is_array($validators) || count($validators) <= 0)
            return;
        foreach ($validators as $validator) :
            if ($validator instanceof CRequiredValidator) :
                $htmlOptions['data-required'] = 'true';
                if ($validator->message) :
                    $htmlOptions['data-missing-message'] = Html::decode($validator->message);
                endif;
            elseif ($validator instanceof CRegularExpressionValidator) :
                $htmlOptions['data-regexp'] = $validator->pattern;
                if ($validator->message) :
                    $htmlOptions['data-invalid-message'] = Html::decode($validator->message);
                endif;
            elseif ($validator instanceof CEmailValidator) :
                $htmlOptions['data-email'] = 'true';
                $htmlOptions['data-regexp'] = substr(substr($validator->pattern, 0, strlen($validator->pattern) - 1), 1);
                if ($validator->message) :
                    $htmlOptions['data-invalid-message'] = Html::decode($validator->message);
                endif;
            elseif ($validator instanceof CDateValidator) :
                $htmlOptions['data-date'] = 'true';
                if ($validator->message) :
                    $htmlOptions['data-invalid-message'] = Html::decode($validator->message);
                endif;
            elseif ($validator instanceof TimeValidator) :
                $htmlOptions['data-time'] = $validator->pattern;
                if ($validator->message) :
                    $htmlOptions['data-invalid-message'] = Html::decode($validator->message);
                endif;
            elseif ($validator instanceof NumberValidator) :
                $htmlOptions['data-number'] = $validator->integerOnly ? 'int' : 'float';
                $pattern = $validator->integerOnly === true ? $validator->integerPattern : $validator->getNumberPattern();
                $htmlOptions['data-regexp'] = substr(substr($pattern, 0, strlen($pattern) - 1), 1);
                if ($validator->message) :
                    $htmlOptions['data-invalid-message'] = Html::decode($validator->message);
                endif;
                if ($validator->min !== null) :
                    $htmlOptions['data-min'] = $validator->min;
                    if ($validator->tooSmall !== null)
                        $htmlOptions['data-min-message'] = Html::decode($validator->tooSmall);
                endif;
                if ($validator->max !== null) :
                    $htmlOptions['data-max'] = $validator->max;
                    if ($validator->tooBig !== null)
                        $htmlOptions['data-max-message'] = Html::decode($validator->tooBig);
                endif;
            elseif ($validator instanceof CCompareValidator) :
                $htmlOptions['data-compare'] = $validator->compareAttribute;
                $htmlOptions['data-compare-operator'] = $validator->operator;
                $htmlOptions['data-compare-message'] = Html::decode($validator->message);
            elseif ($validator instanceof UpperCaseValidator) :
                $htmlOptions['data-uppercase'] = 'true';
            elseif ($validator instanceof LowerCaseValidator) :
                $htmlOptions['data-lowercase'] = 'true';
            elseif ($validator instanceof ProperCaseValidator) :
                $htmlOptions['data-propercase'] = 'true';
            elseif ($validator instanceof TrimValidator) :
                $htmlOptions['data-trim'] = 'true';
            else :
                throw new CException(__METHOD__ . ': validator ' . get_class($validator) . ' not supported.');
            endif;
        endforeach;
    }

    /**
     * Crea il tag <input> del tipo specificato con le opportune classi Bootstrap.
     * In base alle regole di validazione definite nel modello della form per l'attributo associato
     * vengono aggiunti gli attributi data-... necessari alla validazione via Javascript (si veda metodo
     * @link Html::CreateDataProps).
     * 
     * @param string $type Tipo di campo (text, password)
     * @param CModel $model Modello della form
     * @param type $attribute Attributo del modello della form
     * @param array $htmlOptions Opzioni
     * @return string Tag html
     */
    private static function InputField($type, CModel $model, $attribute, array $htmlOptions = null) {
        if ($type == 'checkbox') :
            $htmlOptions = self::AddClasses(array('checkbox'), $htmlOptions);
        else :
            $htmlOptions = self::AddClasses(array('form-control'), $htmlOptions);
        endif;
        self::CreateDataProps($model->getValidators($attribute), $htmlOptions);
        /* Selezione del tipo di input */
        if ($type === 'text') :
            return Html::activeTextField($model, $attribute, $htmlOptions);
        elseif ($type === 'password') :
            return Html::activePasswordField($model, $attribute, $htmlOptions);
        elseif ($type === 'checkbox') :
            return Html::activeCheckBox($model, $attribute, $htmlOptions);
        endif;
    }

    /**
     * Aggiunge all'elemento 'class' della lista degli attributi html le classi specificate.
     * Se necessatio inizializza sia la lista che l'elemento 'class'.
     * 
     * @param array $classes Lista delle classi da attribuire
     * @param array $htmlOptions Lista degli attributi html 
     * @return array Lista degli attributi con classi aggiunte all'elemento 'class' 
     */
    private static function AddClasses(array $classes = null, array $htmlOptions = null) {
        if ($htmlOptions === null)
            $htmlOptions = array();
        if (!isset($htmlOptions['class']))
            $htmlOptions['class'] = '';
        if ($classes === null)
            return $htmlOptions;
        foreach ($classes as $c) :
            $htmlOptions['class'].= (strlen($htmlOptions['class']) > 0 ? ' ' : '') . $c;
        endforeach;
        return $htmlOptions;
    }

}

/* End of file Bootstrap.php */
