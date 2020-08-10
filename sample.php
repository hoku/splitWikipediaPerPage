<?php
/**
 * splitWikipediaPerPage.php を使って分割したファイルの使用例。
 *
 * @license   MIT License
 * @author    hoku
 */

// 分割データが出力されているディレクトリパス
define('OUTPUT_DIR', './out');

// 分割データがあるディレクトリ内は、更に0から連番のディレクトリが入っているので順番に処理していく
foreach (glob(OUTPUT_DIR . '/*') as $outDir) {
    if (!is_dir($outDir)) { continue; }

    // 連番のディレクトリ内にある、1ページ毎のXMLファイルを順に処理していく 
    foreach (glob($outDir . '/*.xml') as $pageXml) {
        if (!is_file($pageXml)) { continue; }

        // XMLに対して好きな処理を実施する
        sample($pageXml);
    }
}


// サンプル処理
function sample($pageXml) {
    // XMLを読み込む
    $xml = simplexml_load_file($pageXml);

    // ページのタイトルを出力する
    echo $xml->page->title . "\n";
    // ページ本文の先頭30文字を出力する
    echo str_replace("\n", "", mb_substr($xml->page->revision->text, 0, 50)) . "…\n\n";
}
