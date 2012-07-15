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
    function put($colInfo){
        $this->collaborators[] = $colInfo;
    }

    /**
     * 背景画像を追加する
     */
    function setBackGround($name){
        $this->background = $name;
    }

    /**
     * カードをレイアウトする
     */
    function doLayout($img){

        for($i = 0; $i < count($this->collaborators); ++$i){

            //$url = '/Users/ms2/dev/tmp/sample.jpg';
            //$url = 'https://graph.facebook.com/100000514317787/picture';
            //print_r($this->collaborators[$i]);

            $info = $this->collaborators[$i];
            $url = $info['imageUrl'];
            $colImg = @imagecreatefromjpeg($url);
            if(!$colImg) die();

            $x = $i * 50;
            $y = $i * 50;

            ImageCopy($img, $colImg,
                $x, $y,
                0, 0,
                ImageSx($colImg), ImageSy($colImg));
        }
    }

    /**
     * 画像インスタンスを作成する
     */
    function createImage(){
        $img = @imagecreatefromjpeg($this->background);
        $this->doLayout($img);
        return $img;
    }

}

/**
 * レスポンスをJpegで出力する。
 * 本当はここに置く処理じゃないけどね…
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
$creator->put(array(
    'imageUrl' => 'https://graph.facebook.com/100000514317787/picture',
    'name' => 'Shin.Kinjo'
));
$creator->put(array(
    'imageUrl' => 'https://graph.facebook.com/100000514317787/picture',
    'name' => 'Shin.Kinjo'
));
$creator->put(array(
    'imageUrl' => 'https://graph.facebook.com/100000316117821/picture',
    'name' => 'Shin.Kinjo'
));

$img = $creator->createImage();
responseJpeg($img);

*/