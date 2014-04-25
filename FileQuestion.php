<?php
require_once('define.php');
require_once('common.php');

class FileQuestion {
    private $questions = null;
    private $filename = '';
    
    /**
     * 読み込む質問が書かれたファイル名をセットする
     * @param string $filename ファイル名
     */
    public function __construct($filename) {
        $this->filename = $filename;
    }
    /**
     * 質問の数を返す。
     * @return integer 質問数
     */
    public function size() {
        // すでに読みこんであったらその要素数を返す
        if ($this->questions !== null) {
            return count($this->questions);
        }
        
        // まだだったら1行目だけ読み込んで返す
        $fh = fopen($this->filename, 'r');
        if ($fh && flock($fh, LOCK_SH)) {
            // 1行目を数値化
            $line = new FileLine($fh);
            $size = +$line->getText();
            flock($fh, LOCK_UN);
            
            return $size;
        }
        else {
            // ファイルオープン・ロックに失敗した
            return false;
        }
    }
    /**
     * 指定した質問IDの質問を返す。
     * @param integer $id 1から始まる質問ID
     * @return object 質問オブジェクト
     */
    public function get($id) {
        if ($this->questions === null) {
            $this->readFile();
        }
        // 質問IDは1オリジン
        $num = $id - 1;
        if (array_key_exists($num, $this->questions)) {
            return $this->questions[$num];
        }
        else {
            return null;
        }
    }
    /**
     * 質問IDをランダムに生成して返す。
     * @return integer 質問ID
     */
    public function generateId() {
        return mt_rand(1, $this->size());
    }
    
    /**
     * static変数にファイルの内容を読み込む
     * @return boolean 成功したかどうか
     */
    private function readFile() {
        $fh = fopen($this->filename, 'r');
        if ($fh && flock($fh, LOCK_SH)) {
            $this->questions = $this->fgetqAll($fh);
            flock($fh, LOCK_UN);
            
            return true;
        }
        else {
            // ファイルオープン・ロックに失敗した
            return false;
        }
    }
    /**
     * ファイルハンドラから質問を読み込んで配列にして返す。
     * 1行目に書かれた数だけ質問を読み込む。
     * @param object $fh ファイルハンドラ
     * @return array 質問オブジェクトの配列
     */
    private function fgetqAll($fh) {
        $line = new FileLine($fh);
        $size = +$line->getText();
        $questions = array();
        
        // size回だけ質問を読み、配列に入れてく
        // 途中でEOFに達した場合、それ以降の質問のtextは空文字、optionsは空の配列になる
        for ($qid = 1; $qid <= $size; $qid++) {
            $line = fget_line_skip_empty($fh); // 空行じゃない最初の行が質問文
            $qtext = $line->getText();
            $options = array();
            
            // 空行じゃない間、選択肢と見なす。ループ1回==1選択肢
            $line = new FileLine($fh);
            while (!$line->isEmpty()) {
                $otext = $line->getText();  // 最初の行が選択肢の文
                $items = array();
                
                // 1行ずつ読んで、案件なら配列に入れていく
                $line = new FileLine($fh);
                while ($line->isItem()) {
                    $items[] = $line->toItem();
                    $line = new FileLine($fh);
                }
                
                $options[] = new Option(array(
                    'text' => $otext,
                    'items' => $items
                ));
            }
            
            $questions[] = new Question(array(
                'id' => $qid,
                'text' => $qtext,
                'options' => $options
            ));
        }
        
        return $questions;
    }
}
