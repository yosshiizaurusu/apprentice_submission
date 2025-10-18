<?php
class Player 
{
    public $name;
    public $cards;
    public $getcards;

    public function __construct($name) {
        $this->name = $name;
    }

    public function putCard() {
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
        if ($card_number == 1) $card_number = 14;

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

        return $card_number;
    }
}

$player1 = new Player("プレイヤー1");
$player2 = new Player("プレイヤー2");


$all = [];
for ($i = 1; $i <= 52; $i++) {
    array_push($all, $i); //1~52の数字をトランプに対応させる
}

shuffle($all); //トランプをシャッフル

echo "戦争を開始します。\n";

$player1->cards = [];
$player2->cards = [];
//カードを配る
for ($i = 0; $i < 52; $i++) {
    if ($i % 2 == 0) {
        array_push($player1->cards, $all[$i]);
    } else {
        array_push($player2->cards, $all[$i]);
    }
}

echo "カードが配られました。\n";

$draw = true;
while ($draw) {
    echo "戦争!\n";
    $top_card1 = $player1->putCard();
    $top_card2 = $player2->putCard();

    echo $top_card1 . "\n";//確認用
    echo $top_card2 . "\n";//確認用

    //注意：カードを捨てる動作はまだ実装していない
    //プレイヤーの出したトランプのマークと数字(１１以上はアルファベット)を出力するところまで完了

    if ($top_card1 > $top_card2) {
        echo "プレイヤー1が勝ちました。\n戦争を終了します。\n";
        $draw = false;//ループ終了
    } elseif ($top_card1 < $top_card2) {
        echo "プレイヤー2が勝ちました。\n戦争を終了します。\n";
        $draw = false;//ループ終了
    } else {
        echo "引き分けです\n";
    }
}

