<?php
require_once('define.php');
require_once('common.php');
require_once('FileQuestion.php');

$fq = new FileQuestion(QUESTION_FILE);
echo $fq->generateId();
