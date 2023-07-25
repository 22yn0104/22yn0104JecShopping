<?php
require_once 'DAO.php';

class Goods
{
    public string $goodscode;
    public string $goodsname;
    public int $price;
    public string $detail;
    public int $groupcode;
    public bool $recommend;
    public string $goodsimage;
}

class GoodsDAO
{
    //おすすめ商品を取得するメソッド
    public function get_recommend_goods()
    {

        //DBに接続
        $dbh = DAO::get_db_connect();

        //Goodsテーブルからおすすめ商品を取得する
        $sql = "SELECT * FROM Goods WHERE recommend = 1";
        $stmt = $dbh->prepare($sql);

        //SQLを実行
        $stmt->execute();

        //取得したデータを配列にする
        $data = [];
        while ($row = $stmt->fetchObject('Goods')) {
            $data[] = $row;
        }
        return $data;
    }

    //引数の商品グループの商品を取得する
    public function get_goods_by_groupcode(int $groupcode)
    {

        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM goods WHERE groupcode = :groupcode ORDER BY recommend DESC";

        $stmt = $dbh->prepare($sql);

        //SQLに変数を当てはめる
        $stmt->bindValue(':groupcode', $groupcode, PDO::PARAM_INT);

        //SQLを実行する
        $stmt->execute();

        //取得したデータをGoodsクラスの配列にする
        $data = [];
        while ($row = $stmt->fetchObject('Goods')) {
            $data[] = $row;
        }
        return $data;
    }

    //引数で指定した商品コードの商品を取得する    
    public function get_goods_by_goodscode(string $goodscode)
    {

        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM goods WHERE goodscode = :goodscode";

        $stmt = $dbh->prepare($sql);

        //SQLに変数を当てはめる
        $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        //1件分のデータをGoodsクラスのオブジェクトとして取得する
        $goods = $stmt->fetchObject('Goods');
        return $goods;
    }

    //指定した検索ワードの商品を探す
    public function get_goods_by_keyword(string $keyword)
    {

        //DBに接続
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM Goods WHERE goodsname LIKE :keyword OR detail LIKE :keyword1 ORDER BY recommend DESC";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
        $stmt->bindValue(':keyword1', '%' . $keyword . '%', PDO::PARAM_STR);

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetchObject('Goods')) {
            $data[] = $row;
        }
        return $data;
    }
}
