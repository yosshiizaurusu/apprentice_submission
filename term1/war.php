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
