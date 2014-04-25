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

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>質問 | <?php echo h(SITE_NAME); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src="js/libs/jquery-2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function() {
            // 最初の選択肢をチェックする
            $('#o_0').attr('checked', true);
        });
    </script>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<body>
    <div id="main_container" class="container">
        <div id="title_area">
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3>質問</h3>
            </div>
            <div class="panel-body">
                <form method="get" action="recommend.php">
                    <input type="hidden" name="qid" value="<?php echo h($_GET['qid']); ?>">
                    <p><?php echo h("質問 {$question->id} : {$question->text}"); ?></p>
                    <?php for ($i = 0, $count = count($question->options); $i < $count; $i++): ?>
                    <div class="radio">
                        <label>
                            <input type="radio" name="o" value="<?php echo $i; ?>" id="o_<?php echo $i; ?>">
                            <?php echo h($question->options[$i]->text); ?>
                        </label>
                    </div>
                    <?php endfor; ?>
                    <div class="row">
                        <input type="submit" value="次へ" class="btn btn-primary btn-lg col-xs-6 col-xs-offset-3">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>