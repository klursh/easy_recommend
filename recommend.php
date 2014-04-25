<?php
require_once('define.php');
require_once('common.php');
require_once('FileQuestion.php');

if (!isset($_GET['qid'])) {
    // 質問IDがGETされてない
    redirect_to(SITE_URL);
}

$fq = new FileQuestion(QUESTION_FILE);
$question = $fq->get($_GET['qid']);

if (!$question) {
    // 質問IDが不正
    redirect_to(SITE_URL);
}
if (!isset($_GET['o']) || !array_key_exists($_GET['o'], $question->options)) {
    // 回答が不正。質問ページヘ
    redirect_to(SITE_URL.'question.php?qid='.$_GET['qid']);
}

$items = $question->options[$_GET['o']]->items;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>結果 | <?php echo h(SITE_NAME); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src="js/libs/jquery-2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <div id="main_container" class="container">
        <div id="title_area">
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>そんなあなたには…</h3>
            </div>
            <div class="panel-body">
                <h4>これだ！</h4>
                <ul>
                    <?php for ($i = 0, $count = count($items); $i < $count; $i++): ?>
                    <li>
                        <a target="_blank" href="http://item.example.jp/items/<?php echo h($items[$i]->id); ?>"><?php echo h($items[$i]->name); ?></a>
                    </li>
                    <?php endfor; ?>
                </ul>
                <div class="text-center">
                    <a target="_blank" href="http://item.example.jp" class="btn btn-warning btn-md">もっと探したい人はコチラ</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
