<?php

require_once 'player.php';

use MyProject\Player;

echo "プレイヤーの人数を入力してください（2～5）：";
$num_of_players = trim(fgets(STDIN));

$list_of_players = [];
for ($i = 1; $i <= $num_of_players; $i++) {
    echo "プレイヤー{$i}の名前を入力してください：";
    $name = trim(fgets(STDIN));
    $player = new Player($name);
    array_push($list_of_players, $player);
}

$all = [];//すべてのトランプ(52枚)
for ($i = 1; $i <= 52; $i++) {
    array_push($all, $i); //1~52の数字をトランプに対応させる
}

shuffle($all); //トランプをシャッフル

echo "戦争を開始します。\n";

//カードを配る
for ($i = 0; $i < 52; $i++) {
    $who_gets_card = $i % $num_of_players;
    array_push($list_of_players[$who_gets_card]->cards, $all[$i]);
}

echo "カードが配られました。\n";

$is_result_decided = false;//勝敗が決まったか判定

$field_cards = [];//場札

while (!($is_result_decided)) {//勝敗が決まればループ終了
    echo "戦争!\n";
    $raw_nums = [];//そのターンに出した、そのままの数字
    $card_nums = [];//そのターンに出した、カードの数字
    foreach ($list_of_players as $player) {
        $num_list = $player->putCard();
        array_push($raw_nums, $num_list[0]);
        array_push($card_nums, $num_list[1]);
    }

    $field_cards = array_merge($field_cards, $raw_nums);//プレイヤーが出したカードを場札に加える

    $num_of_field_cards = count($field_cards);//場札の枚数

    //$card_nums = [プレイヤー1のカードの数字、プレイヤー2のカードの数字、・・・、プレイヤーnのカードの数字]

    $sorted_card_nums = $card_nums;
    sort($sorted_card_nums);
        
    if ($sorted_card_nums[$num_of_players - 1] == $sorted_card_nums[$num_of_players - 2]) {
        /*
        カードの数字を大きい順に並べた配列で、一番大きい数字がふたつ以上ないかの確認
        あったらやり直し
        なかったら勝敗を判定
        */
        echo "引き分けです。\n";
    } else {
        $max_card_num = array_pop($sorted_card_nums);
        for ($i = 0; $i < $num_of_players; $i++) {
            if ($card_nums[$i] == $max_card_num) {
                $list_of_players[$i]->getCards($field_cards);
                $field_cards = [];
                echo "{$list_of_players[$i]->name}が勝ちました。{$list_of_players[$i]->name}はカードを{$num_of_field_cards}枚もらいました。\n";
                break;
            }
        }
    }

    for ($i = 0; $i < $num_of_players; $i++) {
        if ($list_of_players[$i]->cards == [] && $list_of_players[$i]->received_cards != []) {
            $list_of_players[$i]->addCards();
        }
        if ($list_of_players[$i]->cards == [] && $list_of_players[$i]->received_cards == []) {
            echo "{$list_of_players[$i]->name}の手札がなくなりました。\n";
            $is_result_decided = true;
        }
    }
}

for ($i = 0; $i < $num_of_players; $i++) {
    $list_of_players[$i]->final_cards = count($list_of_players[$i]->cards) + count($list_of_players[$i]->received_cards);
}

$result = [];//$result[$i]は$list_of_players[$i]->final_cards
for ($i = 0; $i < $num_of_players; $i++) {
    array_push($result, $list_of_players[$i]->final_cards);
}

//結果の表示
for ($i = 0; $i < $num_of_players; $i++) {
    echo "{$list_of_players[$i]->name}の手札の枚数は{$result[$i]}枚です。";
    if ($i == $num_of_players - 1) {
        echo "\n";
    }
}

//順位を決定
$result = array_unique($result);
sort($result);
$r = 1;//順位を表す変数

while ($result != []) {
    $cnt = 0;//同じ順位の人が何人いたかカウント
    $max_num_of_cards = array_pop($result);
    for ($i = 0; $i < $num_of_players; $i++) {
        if ($list_of_players[$i]->final_cards == $max_num_of_cards) {
            $list_of_players[$i]->ranking = $r;
            $cnt++;
        }
    }
    $r += $cnt;
}

//順位を表示
for ($i = 0; $i < $num_of_players; $i++) {
    if ($i != $num_of_players - 1) {
        echo "{$list_of_players[$i]->name}が{$list_of_players[$i]->ranking}位、";
    } else {
        echo "{$list_of_players[$i]->name}が{$list_of_players[$i]->ranking}位です。\n";
    }
}

echo "戦争を終了します。\n";
