<?php

require_once './helpers/GoodsGroupDAO.php';
require_once './helpers/GoodsDAO.php';


//DBから商品グループを取得する
$goodsGroupDAO = new GoodsGroupDAO();
$goodsgroup_list = $goodsGroupDAO->get_goodsgroup();

$goodsDAO = new GoodsDAO();

//商品グループが選択された時
if (isset($_GET['groupcode'])) {

    //選択された商品グループの商品を取得する    
    $groupcode = $_GET['groupcode'];
    $goods_list = $goodsDAO->get_goods_by_groupcode($groupcode);
} else {

    //おすすめ商品を取得する
    $goods_list = $goodsDAO->get_recommend_goods();
}

//クエストパラメータkeywordがあったとき
if(isset($_GET['keyword'])){
    $keyword = $_GET['keyword'];
    $goods_list = $goodsDAO->get_goods_by_keyword($keyword);
}



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>JecShopping</title>
    <link href="css/IndexStyle.css" rel="stylesheet">
</head>

<body>
    <?php include "header.php"; ?>

    <?php if(!empty($keyword)):?>
        <p>検索結果：<?php echo htmlspecialchars($keyword,ENT_QUOTES,'UTF-8') ?></p>
        <?php endif;?>       

    <table id="goodsgroup">
        <?php foreach ($goodsgroup_list as $goodsgroup) : ?>
            <tr>
                <td>
                    <a href="index.php?groupcode=<?= $goodsgroup->groupcode ?>">
                        <?= $goodsgroup->groupname ?>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <div id="goodslist">
        <?php foreach ($goods_list as $goods) : ?>
            <table align="left">
                <tr>
                    <td>
                        <a href="goods.php?goodscode=<?= $goods->goodscode ?>">
                            <img src="images/goods/<?= $goods->goodsimage ?>">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="goods.php?goodscode=<?= $goods->goodscode ?>">
                            <?= $goods->goodsname ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        ¥<?= number_format($goods->price) ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?= $goods->recommend ? "おすすめ" : " " ?>
                    </td>
                </tr>
            </table>
        <?php endforeach ?>
    </div>
</body>

</html>