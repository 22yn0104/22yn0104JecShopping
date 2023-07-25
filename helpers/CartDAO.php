<?php
require_once 'DAO.php';

class Cart
{
    public int $memberid;
    public string $goodscode;
    public string $goodsname;
    public int $price;
    public string $detail;
    public string $goodsimage;
    public int $num;
}

class CartDAO
{
    //会員のカートデータを取得する  
    public function get_cart_by_memberid(int $memberid)
    {

        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT memberid,Cart.goodscode,Goods.goodsname,Goods.price,Goods.detail,Goods.goodsimage,Cart.num FROM Cart INNER JOIN Goods ON Cart.goodscode = Goods.goodscode WHERE memberid = :memberid";

        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
        $stmt->execute();

        //取得したデータをCartクラスの配列にする
        $data = [];
        while ($row = $stmt->fetchObject('Cart')) {
            $data[] = $row;
        }
        return $data;
    }

    //カートの合計個数
    //public function get_cart_sum(int $num)
    //{
        //$dbh = DAO::get_db_connect();

       // $sql = "SELECT SUM(num) FROM Cart";

       // $stmt = $dbh->prepare($sql);

       // $stmt->execute();

   // }

    //指定した商品がカートテーブルに存在するか確認する
    public function cart_exists(int $memberid, string $goodscode)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM Cart WHERE memberid = :memberid AND goodscode = :goodscode";

        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
        $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        if ($stmt->fetch() !== false) {
            return true; //カートに商品が存在する
        } else {
            return false; //カートに商品が存在しない
        }
    }

    //カートテーブルに商品を追加する
    public function insert(int $memberid, string $goodscode, int $num)
    {
        $dbh = DAO::get_db_connect();

        //カートに同じ商品がないとき
        if (!$this->cart_exists($memberid, $goodscode)) {
            //カートテーブルに商品を登録する
            $sql = "INSERT INTO Cart (memberid,goodscode,num) VALUES (:memberid,:goodscode,:num)";

            $stmt = $dbh->prepare($sql);

            $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
            $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);
            $stmt->bindValue(':num', $num, PDO::PARAM_INT);

            $stmt->execute();
        }
        //カートテーブルに同じ商品があるとき
        else {
            //カートテーブルに商品個数を加算する
            $sql = "UPDATE Cart SET num = num + :num WHERE memberid = :memberid AND goodscode = :goodscode";

            $stmt = $dbh->prepare($sql);

            $stmt->bindValue(':num', $num, PDO::PARAM_INT);
            $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
            $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);

            $stmt->execute();
        }
    }

    //カートテーブルの商品個数を変更する
    public function update(int $memberid, string $goodscode, int $num)
    {
        $dbh = DAO::get_db_connect();

        $sql = "UPDATE Cart SET num = :num WHERE memberid = :memberid AND goodscode = :goodscode";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':num', $num, PDO::PARAM_INT);
        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
        $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);

        $stmt->execute();
    }

    //カートテーブルから商品を削除する
    public function delete(int $memberid, string $goodscode)
    {
        $dbh = DAO::get_db_connect();

        $sql = "DELETE FROM Cart WHERE memberid = :memberid AND goodscode = :goodscode";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);
        $stmt->bindValue(':goodscode', $goodscode, PDO::PARAM_STR);
        $stmt->execute();
    }

    //会員のカート情報を全て削除する
    public function delete_by_memberid(int $memberid)
    {
        $dbh = DAO::get_db_connect();

        $sql = "DELETE FROM Cart WHERE memberid = :memberid";

        $stmt = $dbh->prepare($sql);

        $stmt->bindValue(':memberid', $memberid, PDO::PARAM_INT);

        $stmt->execute();
    }
}
