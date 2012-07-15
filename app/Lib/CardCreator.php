<?php
/**
 * カード生成クラス
 */
class CardCreator{

    private $collaboraters;

    function CardCreator(){
        $this->collaborators = array();
    }

    /**
     * コラボレーターの情報を追加する
     */
    function put($conInfo){
        $this->collaborators[] = $colInfo;
    }

    /**
     * 背景画像を追加する
     */
    function setBackGround($name){
        $this->background = $name;
    }

    /**
     * 画像インスタンスを作成する
     */
    function createImage(){
        return @imagecreatefromjpeg($this->background);
    }

}

/**
 * レスポンスをJpegで出力する。
 * 本当はこうじゃないけどね…
 */
function responseJpeg($img){
    header('Content-Type: image/jpeg');
    imagejpeg($img);
    imagedestroy($img);
}


/**
//大体の使い方

include_once('CardCreator.php');

$creator = new CardCreator();
$creator->setBackground('test.jpg');
$img = $creator->createImage();
responseJpeg($img);
*/