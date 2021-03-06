<?php
class panels {

    public static $table_name = "panels";

    // 全件
    public static function findAll($id){
        $searchResult = ORM::for_table(self::$table_name)
            ->select('*')
            ->where('del_flg', 0)
            ->where('boards_id', $id)
            ->order_by_asc('order_key')
            ->find_many();
        return $searchResult;

    }

    // 最初の1件を取得
    public static function findFirst(){
        $searchResult = ORM::for_table(self::$table_name)
            ->select('*')
            ->where('del_flg', 0)
            ->order_by_asc('id')
            ->limit(1)
            ->find_one();
        return $searchResult;
    }


    // ラベル絞込み全件
    public static function findLabel($labelId){
        $searchResult = ORM::for_table(self::$table_name)
            ->select('*')
            ->where('labelid', $labelId)
            ->where('del_flg', 0)
            ->order_by_asc('title')
            ->find_many();
        return $searchResult;
    }

    // リスト（ページング用)
    public static function lists($page, $keyword=''){
        // ページの開始位置を計算
        $offset = 0;
        if($page[1] !== 1){
            $offset = ( ( $page[0] -1 ) * $page[1] ); // 指定されたページ * 1ページあたりの件数
        }
        $searchResult = ORM::for_table(self::$table_name)
            ->select('*')
            ->where_like('username', '%'.$keyword.'%')
            ->where('del_flg', 0)
            ->limit($page[1]) // 1ページあたりの件数
            ->offset($offset) // ページの開始位置
            ->order_by_desc('id')
            ->find_many();
        return $searchResult;
    }

    // 1件
    public static function find($id){
        $searchResult = ORM::for_table(self::$table_name)
            ->select('*')
            ->where('del_flg', 0)
            ->where('id', $id)
            ->find_one();
        return $searchResult;
    }

    // 論理削除
    public static function del($id){
        $delete = ORM::for_table(self::$table_name)->find_one($id);
        $delete->del_flg = 1;
        return $delete->save();
    }

    // 追加
    public static function create($array) {

        $lastOneResult = ORM::for_table(self::$table_name)
                ->select('*')
                ->where('del_flg', 0)
                ->order_by_desc('id')
                ->find_one();
        $newOrderNum = $lastOneResult['id'] + 1;

        $create = ORM::for_table(self::$table_name)->create();
        foreach ($array as $key => $value) {
            $create->{$key} = $value;
        }
        $create->order_key = $newOrderNum; // 並び順は最後のIDに+1
        $create->save();
        return $create->id();
    }

    // 更新
    public static function edit($id, $array){
        $edit = ORM::for_table(self::$table_name)->find_one($id);
        foreach ($array as $key => $value) {
            $edit->{$key} = $value;
        }
        return $edit->save();
    }

}
