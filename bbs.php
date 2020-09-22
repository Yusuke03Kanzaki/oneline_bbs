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
    print_r($_POST);
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
        // echo 'aaaaa';
        //保存するためのSQL文を作成
        echo $sql = "INSERT INTO post (name, comment, created_at) VALUES ('"
        . mysqli_real_escape_string($link, $name) . "','"
        . mysqli_real_escape_string($link, $comment) . "','"
        . date('Y-m-d H:i:s') . "')";

        //保存する
        mysqli_query($link, $sql);
        echo mysqli_error ($link);

        mysqli_close($link);

        header('Location: http://'. $_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);


    }

    
    // /* トランザクションをコミットします */  これ全く必要なかったphpmyadminでオートクリメントが設定されていなかった
    // if (!mysqli_commit($link)) {
    //     print("Transaction commit failed\n");
    //     exit();
    // }
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
      <?php if (count($errors)): ?>
      <ul class="error_list">
        <?php foreach ($errors as $error): ?>
        <li>
          <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    <form action="bbs.php" method="post">  <!--actionは送信先のURI-->
        名前: <input type="text" name='name'/><br>
        ひとこと: <input type="text" name="comment" size="60"/><br>  <!--sizeは文字数-->
        <input type="submit" name="submit" value="送信">  <!--submitで送信-->
    </form>

    <?php 
    //投稿された内容を取得するSQLを作成して結果を取得
    $sql = "SELECT * FROM `post` ORDER BY `created_at` DESC";
    // var_dump($sql);
    $result = mysqli_query($link, $sql);

    //取得した結果を$postsに格納
    $posts = [];
    if ($result !== false && mysqli_num_rows($result)) {
        while ($post = mysqli_fetch_assoc($result)) {
            $posts[] = $post;
        }
    }

    //取得結果を開放して接続を閉じる
    mysqli_free_result($result);
    mysqli_close($link);
    ?>

    <?php if ($result !== false && mysqli_num_rows($result)): ?>
    <ul>
        <?php while ($post = mysqli_fetch_assoc($result)): ?>
        <li>
            <?php echo htmlspecialchars($post['name'], ENT_QUOTES, 'UTF-8'); ?>:
            <?php echo htmlspecialchars($post['comment'], ENT_QUOTES, 'UTF-8'); ?>:
            - <?php echo htmlspecialchars($post['created_at'], ENT_QUOTES, 'UTF-8'); ?>:
        </li>
        <?php endwhile; ?>
    </ul>
    <?php endif; ?>

    <?php
    //結果を取得して開放して接続を閉じる
    mysqli_free_result($result);
    mysqli_close($link);
    ?>
</body>
</html>