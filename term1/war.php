<?php

require_once 'player.php';

use MyProject\Player;

$player1 = new Player("プレイヤー1");
$player2 = new Player("プレイヤー2");


$all = [];
for ($i = 1; $i <= 52; $i++) {
    array_push($all, $i); //1~52の数字をトランプに対応させる
}

shuffle($all); //トランプをシャッフル

echo "戦争を開始します。\n";

$player1->cards = [];//プレイヤー1の手札
$player2->cards = [];//プレイヤー２の手札
$field_cards = [];//場札

//カードを配る
for ($i = 0; $i < 52; $i++) {
    if ($i % 2 == 0) {
        array_push($player1->cards, $all[$i]);
    } else {
        array_push($player2->cards, $all[$i]);
    }
}

echo "カードが配られました。\n";

$is_result_decided = false;//勝敗が決まったか判定

while (!($is_result_decided)) {//勝敗が決まればループ終了
    echo "戦争!\n";
    $num_list1 = $player1->putCard();
    $top_card1 = $num_list1[0];
    $card_number1 = $num_list1[1];

    $num_list2 = $player2->putCard();
    $top_card2 = $num_list2[0];
    $card_number2 = $num_list2[1];

    array_push($field_cards, $top_card1, $top_card2);//プレイヤーが出したカードを場札に加える

    $num_of_field_cards = count($field_cards);//場札の枚数

    if ($card_number1 > $card_number2) {
        $player1->getCards($field_cards);
        $field_cards = [];
        echo "{$player1->name}が勝ちました。{$player1->name}はカードを{$num_of_field_cards}枚もらいました。\n";
    } elseif ($card_number1 < $card_number2) {
        $player2->getCards($field_cards);
        $field_cards = [];
        echo "{$player2->name}が勝ちました。{$player2->name}はカードを{$num_of_field_cards}枚もらいました。\n";
    } else {
        echo "引き分けです。\n";
    }

    /*
    もらった場札が、0枚じゃないなら、もらった場札を手札に加える
    手札も、もらった場札も0枚なら終わり
    */
    if ($player1->cards == [] && $player1->received_cards != []) {
        $player1->addCards();
    }
    if ($player2->cards == [] && $player2->received_cards != []) {
        $player2->addCards();
    }

    if ($player1->cards == [] && $player1->received_cards == []) {
        echo "{$player1->name}の手札がなくなりました。\n";
        $is_result_decided = true;
    }
    if ($player2->cards == [] && $player2->received_cards == []) {
        echo "{$player2->name}の手札がなくなりました。\n";
        $is_result_decided = true;
    }
}

$player1->final_cards = count($player1->cards) + count($player1->received_cards);
$player2->final_cards = count($player2->cards) + count($player2->received_cards);

$result = [$player1->final_cards, $player2->final_cards];

//結果の表示
echo "{$player1->name}の手札の枚数は{$result[0]}です。";
echo "{$player2->name}の手札の枚数は{$result[1]}です。\n";

//順位を決定
sort($result);//枚数を昇順ににソート
for ($i = 0; $i < 2; $i++) {
    $max_num_of_cards = array_pop($result);
    if ($player1->final_cards == $max_num_of_cards) {
        $player1->ranking = $i + 1;
    } elseif ($player2->final_cards == $max_num_of_cards) {
        $player2->ranking = $i + 1;
    }
}

//順位を表示
echo "{$player1->name}が{$player1->ranking}位、{$player2->name}が{$player2->ranking}位です。\n";

echo "戦争を終了します。\n";
