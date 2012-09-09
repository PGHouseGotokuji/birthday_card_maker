<?php
/*
 * Util : 共通メソッド
 */
class Util 
{
    /**
     * タイムスタンプを付与したURLを返す
     *
     * @param  string $docFilePath ドキュメントルートからのファイルパス
     * @return string
     */
    public static function getAssetTimestampUrl($docFilePath)
    {
        $filePath = WWW_ROOT . $docFilePath;
        if (file_exists($filePath)) {
            return $docFilePath . '?' . filemtime($filePath);
        }
        return $docFilePath;
    }
}
?>
