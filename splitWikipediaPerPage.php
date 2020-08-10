<?php
/**
 * WikipediaのXMLを、1ページ毎のXMLに分割するスクリプト。
 * (1000ファイル毎に1フォルダにまとめて出力する)
 *
 * @license   MIT License
 * @author    hoku
 */

// 出力ファイル単位
define('OUTPUT_UNIT', 1000);
// 出力先ディレクトリパス
define('DEFAULT_OUT_DIR', __DIR__ . '/out');

// 引数チェック
if (count($argv) < 2) {
    echo "ERROR: 引数が足りません。\n";
    echo "ERROR: php splitWikipediaPerPage.php srcFilePath [outDirPath = " . DEFAULT_OUT_DIR . "]\n";
    exit;
}
if (!file_exists($argv[1])) {
    echo "ERROR: 指定されたXMLファイルが存在しません。\n";
    echo "ERROR: srcFilePath => " . $argv[1] . "\n";
    exit;
}

// 分割実行
parseWikipedia($argv[1], $argv[2] ?? DEFAULT_OUT_DIR);


/**
 * WikipediaのXMLを、1ページ毎のXMLに分割する。
 * (1000ファイル毎に1フォルダにまとめて出力する)
 *
 * @param string $srcFilePath     WikipediaのXMLファイルパス
 * @param string $outDirRootPath  分割結果の出力先ディレクトリパス
 * @return void
 */
function parseWikipedia(string $srcFilePath, string $outDirRootPath) : void
{
    ini_set('memory_limit', '2048M');
    set_time_limit(60 * 60 * 6);

    @mkdir($outDirRootPath, 0744, true);

    $srcFile = fopen($srcFilePath, 'r');
    if (!$srcFile) {
        fclose($srcFile);
        return;
    }
    
    // 最初と最後の行を取得
    $firstLine = trim(fgets($srcFile));
    preg_match('/\<\s*([a-zA-Z0-9_-]+)\s/', $firstLine, $firstNodeNameMatch);
    $firstNodeName = $firstNodeNameMatch[1];
    $lastLine = '</' . $firstNodeName . '>';

    // 1ページずつ出力していく 
    $outputDirCount  = 0;
    $outputFileCount = 0;
    $pageTmp = [];
    while ($line = fgets($srcFile)) {
        $line = trim($line);
        if ($line === '<page>') {
            $pageTmp = [];
            $pageTmp[] = $line;
        } elseif ($line === '</page>') {
            if ($outputFileCount >= OUTPUT_UNIT) {
                $outputDirCount++;
                $outputFileCount = 0;
            }
            $outDirPath  = $outDirRootPath . '/' . $outputDirCount;
            $outFileName = (($outputDirCount * OUTPUT_UNIT) + $outputFileCount++) . '.xml';
            @mkdir($outDirPath, 0744, true);
            
            $pageTmp[] = $line;

            // 出力する
            $outputString = $firstLine . "\n" . implode("\n", $pageTmp) . "\n" . $lastLine;
            file_put_contents($outDirPath . '/' . $outFileName, $outputString);
        } else {
            $pageTmp[] = $line;
        }
    }
    fclose($srcFile);
}
