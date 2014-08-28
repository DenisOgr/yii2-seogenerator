<?php
/**
 *
 */

namespace denisogr\seogenerator;
use yii\base\Exception;

/**
 * Class SeoGeneraor
 * Можно использовать следующие правила:
 *
 * @package denisogr\seogenerator
 * @author Denis Porplenko <denis.porplenko@gmail.com>
 */
class SeoGeneraor {


    const FORMAT_JSON = 'json';

    const FORMAT_XML  = 'xml';

    const FORMAT_TEXT = 'text';

    const BASE_BIG1 = 'big1';

    const BASE_SM1  = 'sm1';

    const BASE_SM2  = 'sm2';

    const TYPE_RANDOM  = 'random';

    const TYPE_FIRST  = 'first';


    /**
     * Текст и праивла
     *
     * @var string
     */
    private $_text;

    /**
     * Используемая база синонимов
     * @var string
     */
    private $_base   = 'sm1'; //big1, sm2, sm1

    /**Метод подстановки синонима(первый или случайный).
     * @var string
     */
    private $_type   = 'random'; //first, random

    /** Сколько делать синонимизированных текстов(актуален только при type=random), значения: от 1 до 10
     * @var int
     */
    private $_count  = 1;

    /**
     * @var string формат ответа шлюза, значения: xml, json, text
     */
    private $_format = 'json'; //xml, json, text

    /**
     * Удаленный сервис
     * @var string
     */
    private $_url = 'http://seogenerator.ru/api/synonym/';
    
    
    public function text($dat){
        $this->_text = $dat;
        return $this;
    }
    public function base($dat){
        $this->_base = $dat;
        return $this;
    }
    public function type($dat){
        $this->_type = $dat;
        return $this;
    }
    public function count($dat){
        $this->_count = $dat;
        return $this;
    }
    public function format($dat){
        $this->_format = $dat;
        return $this;
    }

    /**
     * @return mixed
     * @throws \yii\base\Exception
     */
    function get(){

        if (empty($this->_text)) {
            throw new Exception('Property text can not be empty!');
        }

        $dat = [
            'text'=>$this->_text,
            'base'=>$this->_base,
            'type'=>$this->_type,
            'count'=>$this->_count,
            'format'=>$this->_format
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->_url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dat);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $dat = curl_exec($ch);
        curl_close($ch);
        return $this->convert($dat);
    }

    protected function convert($dat)
    {
        $result = $dat;

        switch($this->_format) {
            case self::FORMAT_JSON:
                $result = json_decode($result);
                break;
        }

        return $result;
    }
} 