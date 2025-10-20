<?php

namespace MyProject;

class Player
{
    public $name;//プレイヤー名
    public $cards = [];//手持ちのカード
    public $received_cards = [];//もらった場札
    public $final_cards;//勝負が終わった時のカードの枚数
    public $ranking;//順位

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function putCard()
    {
        //場に出したカードを出力するメソッド
        $top_card = array_pop($this->cards);

        /*
        13で割った商が0,1,2,3の時それぞれハート、ダイヤ、スペード、クローバーとする
        そして、余りをカードの数字に対応させる
        ただし余りが0の場合は商を1減らし、余りを13増やす
        例えば26は1余り13と考えることで、ダイヤの13に対応させる
        さらに、余りが1の場合は余りを13増やす
        こうすることで、A、K、Q、J、10、9、8、7、6、5、4、3、2の
        順番に調整する
        ややこしすぎ、絶対もっと良い方法ある
        */

        $card_mark = intdiv($top_card, 13);
        $card_number = $top_card % 13;

        //余りが0の場合は商を1減らし、余りを13増やすための処理
        if ($card_number == 0) {
            $card_number = 13;
            $card_mark--;
        }

        //余りが1の場合は余りを13増やすための処理
        if ($card_number == 1) {
            $card_number = 14;
        }

        //13で割った商が0,1,2,3の時それぞれハート、ダイヤ、スペード、クローバーとするための配列
        $card_mark_list = ["ハート", "ダイヤ", "スペード", "クローバー"];

        if ($card_number <= 10) {
            //10以下の場合はそのまま数字を出力
            echo "{$this->name}のカードは{$card_mark_list[$card_mark]}の{$card_number}です。\n";
        } else {
            //11以上の場合は対応するアルファベットを出力
            $card_alphabet_list = ["J", "Q", "K", "A"];
            $card_alphabet = $card_number % 11;
            echo "{$this->name}のカードは{$card_mark_list[$card_mark]}の{$card_alphabet_list[$card_alphabet]}です。\n";
        }

        //そのままの数字と、場に出したカードの数字、両方で返す
        return [$top_card, $card_number];
    }

    public function getCards($field_cards)
    {
        //勝ったときに、場札をもらうメソッド
        $this->received_cards = array_merge($this->received_cards, $field_cards);
    }

    public function addCards()
    {
        //手札が0枚の時にもらった場札シャッフルし、手札に加えるメソッド
        shuffle($this->received_cards);
        $this->cards = $this->received_cards;
        $this->received_cards = [];
    }
}
