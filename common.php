<?php
require_once('define.php');

/**
 * HTMLエスケープ
 * @param string $s エスケープ対象文字列
 * @return string 結果
 */
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES);
}

/**
 * 文字列の最後から改行を取り除く
 * 失敗した場合は空文字を返す
 * @param string $s 対象文字列
 * @return string 処理結果
 */
function chomp($s) {
    return is_string($s) ? rtrim($s, "\r\n") : '';
}

/**
 * HTTPリダイレクトし、スクリプトを終了する。
 * この関数を実行するまでにHTTPボディに何らかの出力を行った場合、リダイレクトは失敗する
 * @param string $url URL
 */
function redirect_to($url) {
    header('Location: '.$url);
    exit();
}

/** 
 * array_key_existsの配列版
 * 指定したキーがすべて配列に存在しているかどうかを判定する
 * @param array $keys キーの配列
 * @param array $arr 調査対象の配列
 * @return boolean すべて存在したらtrue
 */
function array_keys_exists($keys, $arr) {
    for ($i = 0, $count = count($keys); $i < $count; $i++) {
        if (!array_key_exists($keys[$i], $arr)) return false;
    }
    return true;
}

/**
 * 次の、空行じゃない行のFileLineオブジェクトを返す
 * @param object $fh ファイルハンドラ
 * @return object FileLineオブジェクト
 */
function fget_line_skip_empty($fh) {
    do {
        $line = new FileLine($fh);
    } while (!$line->isEOF() && $line->isEmpty());
    
    return $line;
}

/**
 * TODO: write description
 * FileLineクラス
 */
class FileLine {
    private $text;
    
    /**
     * ファイルハンドラから1行読み、改行を除いた文字列をインスタンス変数に入れる。
     * EOFまたは1行読めなかった場合はfalseを入れる。
     * @param object $fh ファイルハンドラ
     */
    public function __construct($fh) {
        $s = fgets($fh);
        if ($s !== false) {
            // 最後の改行を取り除いた文字列
            $this->text = chomp($s);
        }
        else {
            // EOFの場合
            $this->text = false;
        }
    }
    /**
     * EOFかどうか
     * @return boolean
     */
    public function isEOF() {
        return $this->text === false;
    }
    /**
     * 行がEOFまたは空文字かどうか
     * @return boolean
     */
    public function isEmpty() {
        return $this->isEOF() || $this->text === '';
    }
    /**
     * 案件を表す行かどうか判定する（"数,"で始まってるかどうかを判定する）
     * @return boolean 判定結果
     */
    public function isItem() {
        return !$this->isEOF() && !!preg_match('/^[0-9]+,/', $this->text);
    }
    /**
     * 案件オブジェクトを取得する。失敗した場合falseを返す
     * @return object 案件オブジェクト
     */
    public function toItem() {
        // 案件の行じゃない場合return
        if (!$this->isItem()) return false;
        // 最初のカンマの前が案件ID、後ろが案件名
        $params = explode(',', $this->text, 2);
        
        $item = new Item(array(
            'id' => $params[0],
            'name' => $params[1]
        ));
        return $item;
    }
    /**
     * 行の文字列を取得する。最後の改行は含まれない
     * @return string 行
     */
    public function getText() {
        return $this->text;
    }
}
/**
 * 質問クラス
 */
class Question {
    public $id = null;
    public $text = null;
    public $options = null;
    public function __construct($params) {
        if (!array_keys_exists(array('id', 'text', 'options'), $params)) {
            throw new Exception('引数が正しくありません');
        }
        $this->id = $params['id'];
        $this->text = $params['text'];
        $this->options = $params['options'];
    }
}
/**
 * 選択肢クラス
 */
class Option {
    public $text = null;
    public $items = null;
    public function __construct($params) {
        if (!array_keys_exists(array('text', 'items'), $params)) {
            throw new Exception('引数が正しくありません');
        }
        $this->text = $params['text'];
        $this->items = $params['items'];
    }
}
/**
 * 案件クラス
 */
class Item {
    public $id = null;
    public $name = null;
    public function __construct($params) {
        if (!array_keys_exists(array('id', 'name'), $params)) {
            throw new Exception('引数が正しくありません');
        }
        $this->id = $params['id'];
        $this->name = $params['name'];
    }
}


