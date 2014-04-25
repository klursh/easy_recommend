<?php
require_once('define.php');
require_once('common.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title><?php echo h(SITE_NAME); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script type="text/javascript" src="js/libs/jquery-2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#start').click(function() {
                // 質問IDを受信し、question.phpにGET
                $.ajax({
                    type: 'GET',
                    url: 'qid_generate.php',
                    dataType: 'text',
                    success: function(qid) {
                        location.href = 'question.php?qid=' + qid;
                    }
                });
            });
        });
    </script>
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
                <h3><?php echo h(SITE_NAME); ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <button id="start" class="btn btn-primary btn-lg col-xs-6 col-xs-offset-3">▶ スタート！</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

