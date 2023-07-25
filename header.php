<?php
require_once './helpers/MemberDAO.php';
require_once './helpers/CartDAO.php';

//セッション開始(セッションの2重起動を防ぐ)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//ログイン中の時
if (!empty($_SESSION['member'])) {

    //セッション変数の会員情報を取得する
    $member = $_SESSION['member'];

    //DBから会員のカートデータを取得する
    //$cartDAO = new CartDAO();
    //$sum = $cartDAO->get_cart_sum($num);
}



?>
<header>
    <link href="css/HeaderStyle.css" rel="stylesheet">

    <div id="logo">
        <div>
            <a href="./index.php">
                <img src="images/JecShoppingLogo.jpg" alt="JecShoppingロゴ">
            </a>
        </div>
    </div>

    <div id="link">
        <div>
            <form action="index.php" method="GET">
                <input type="text" name="keyword" value="<?php if (isset($keyword)) : ?> <?php echo htmlspecialchars($keyword,ENT_QUOTES,'UTF-8') ?> <?php endif; ?>">
                <input type="submit" value="検索">
            </form>
            <?php if (isset($member)) : ?>
                <?= $member->membername; ?>さん
                <a href="cart.php">カート</a>
                <a href="logout.php">ログアウト</a>
            <?php else : ?>
                <a href="login.php">ログイン</a>
            <?php endif; ?>
        </div>
    </div>

    <div id="clear">
        <hr>
    </div>
</header>