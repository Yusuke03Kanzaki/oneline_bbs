<?php

//データベースに接続
$link = mysqli_connect('127.0.0.1', 'root', 'root', 'oneline_bbs');
if (!$link) {
    die('データベースに接続できません：' . mysqli_error($link). PHP_EOL) ;
}

//データベースを選択する
mysqli_select_db($link, 'oneline_bbs');

$errors = array();

//POSTなら保存処理実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //名前が正しく入力されているかチェック
    $name = null;
    if (!isset($_POST['name']) || !strlen($_POST['name'])) {
        $errors['name'] = '名前を入力してください';
    } elseif (strlen($_POST['name']) > 40) {
        $errors['name'] = '名前は４０文字以内で入力してください';
    } else {
        $name = $_POST['name'];
    }

    //一言が正しく入力されているかチェック
    $comment = null;
    if (!isset($_POST['comment']) || !strlen($_POST['comment'])) {
        $errors['comment'] = 'ひとこと入力してください';
    } elseif (strlen($_POST['comment']) > 200) {
        $errors['comment'] = 'ひとことは２００文字以内で入力してください';
    } else {
        $comment = $_POST['comment'];
    }

    //エラーがなければ保存
    if (count($errors) === 0) {
        // $sql = "INSERT INTO `post` (`name`, `comment`, `created_at`) VALUES ('". mysqli_real_escape_string($name). "'," 
        $sql = "INSERT INTO `post` (`name`, `comment`, `created_at`) VALUES ('". mysqli_real_escape_string($name). "','". mysqli_real_escape_string($comment). "','"
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>ひとこと掲示板</h1>

    <form action="bbs.php" method="post">
        名前: <input type="text" name='name'/><br>
        ひとこと: <input type="text" name="comment" size="60"/><br>
        <input type="submit" name="submit" value="送信">
    </form>
</body>
</html>