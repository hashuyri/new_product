<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ログイン画面</title>
</head>

<body>
  <form action="login_act.php" method="POST">
    <fieldset>
      <legend>ログイン画面</legend>
      <div>
        ユーザーid: <input type="text" name="user_id" required>
      </div>
      <div>
        password: <input type="text" name="password" required>
      </div>
      <div>
        <button>Login</button>
      </div>
      <a href="register.php">新規ユーザー登録</a>
    </fieldset>
  </form>

</body>

</html>