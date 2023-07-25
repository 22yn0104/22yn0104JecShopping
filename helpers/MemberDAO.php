<?php
require_once 'DAO.php';

class Member
{
    public int $memberid;
    public string $email;
    public string $membername;
    public string $zipcode;
    public string $address;
    public string $tel;
    public string $password;
}


class MemberDAO
{
    //DBからメールアドレスとパスワードが一致する会員データを取得する
    public function get_member(string $email, string $password)
    {
        $dbh = DAO::get_db_connect();

        //メールアドレスが一致する会員データを取得する
        $sql = "SELECT * FROM member WHERE email = :email";
        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->execute();

        //1件分のデータをMemberクラスのオブジェクトとして取得する
        $member = $stmt->fetchObject('Member');

        if ($member !== false) {
            //パスワードが一致するか検証
            if (password_verify($password, $member->password)) {
                //会員データを返す
                return $member;
            }
        }
        return false;
    }

    //会員データを登録する
    public function insert(Member $member)
    {
        $dbh = DAO::get_db_connect();

        $sql = "INSERT INTO Member (email,membername,zipcode,address,tel,password) Values (:email,:membername,:zipcode,:address,:tel,:password)";

        $stmt = $dbh->prepare($sql);

        //パスワードをハッシュ化
        $password = password_hash($member->password, PASSWORD_DEFAULT);

        $stmt->bindValue(':email', $member->email, PDO::PARAM_STR);
        $stmt->bindValue(':membername', $member->membername, PDO::PARAM_STR);
        $stmt->bindValue(':zipcode', $member->zipcode, PDO::PARAM_STR);
        $stmt->bindValue(':address', $member->address, PDO::PARAM_STR);
        $stmt->bindValue(':tel', $member->tel, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();
    }

    //指定したメールアドレスの会員データが存在していればtrueを返す
    public function email_exists(string $email)
    {
        //DBに接続する
        $dbh = DAO::get_db_connect();

        $sql = "SELECT * FROM Member WHERE email = :email";

        $stmt = $dbh->prepare($sql);

        //SQLに変数の値を当てはめる
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        //SQLを実行する
        $stmt->execute();

        if ($stmt->fetch() !== false) {
            return true;
        } else {
            return false;
        }
    }
}
