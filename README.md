splitWikipediaPerPage
================

**splitWikipediaPerPage** は、WikipediaのXMLファイルを「1ページ1XMLファイル」に分割するためのスクリプトです。

「Wikipediaを解析して何かに使いたいけど、サイズがデカくて扱いづらい」なんて時に便利です。


使い方
-----

``` shell
# 最新のWikipediaデータをダウンロードする
curl https://dumps.wikimedia.org/jawiki/latest/jawiki-latest-pages-articles.xml.bz2 -o jawiki-latest-pages-articles.xml.bz2

# 解凍する
bunzip2 jawiki-latest-pages-articles.xml.bz2

# splitWikipediaPerPageのソースをクローンしてくる
git clone https://github.com/hoku/splitWikipediaPerPage.git

# WikipediaのXMLを「1ページ1XMLファイル」に分割する
php splitWikipediaPerPage/splitWikipediaPerPage.php jawiki-latest-pages-articles.xml

# 待っていると、outディレクトリに分割されたファイルが吐き出される
cd out
```


実行パラメータ
-----------

* 第1引数 : WikipediaのXMLファイルパス
* 第2引数 : 分割結果の出力先ディレクトリパス
  * 指定しない場合は、splitWikipediaPerPage.php が存在するディレクトリにoutディレクトリが生成され、その中に分割結果のファイルが出力されます。


分割結果
-------

「1ページ1XMLファイル」で出力されます。

* outディレクトリ内に、1000ファイル毎に1ディレクトリで出力されます。
* ディレクトリ名は0から連番となります。
  * out/0/
  * out/1/
  * …
* ファイルは全て全て通して連番になります。
  * out/0/0.xml
  * out/0/….xml
  * out/0/999.xml
  * out/1/1000.xml
  * out/1/….xml
  * out/1/1999.xml
  * …
* 目安として、2019年モデルの Macbook Pro (i9) だと7分ほどで分割が完了します。


ライセンス
-------

MIT License.
