<?php 
/********* 递归是程序调用自身的编程技巧*********/

//使用静态变量存储递归
function deeploop()
{
    static $i = 1;
    echo $i;
    $i++;
    if ($i <= 10) {
        deeploop();
    }
}
##deeploop();
//使用 值引用 的形式
function deeploop2(&$i = 1)
{
    echo $i;
    $i++;
    if ($i <= 10) {
        deeploop2($i);
    }
}
##deeploop2();
//使用 global 全局变量的形式
$i = 1;
function deeploop3()
{
    global $i;
    echo $i;
    $i++;
    if ($i <= 10) {
        deeploop3();
    }
}
##deeploop3();

/********* 递归无限极分类原理*********/
/*
 * 每一个分类都需要记录它的父级id，当为顶级分类时，父级id为0。这样无论哪个分类
 * 都可以通过父级id一层层的去查明它所有的父级
 * 以便清楚知道它所属何种分类，层级深度为多少
 */

/**
 * 无限极分类树 getTree($categories)
 * @param array $data
 * @param int $parent_id
 * @param int $level
 * @return array
 */
function getTree($data = [], $parent_id = 0, $level = 0)
{
    $tree = [];
    if ($data && is_array($data)) {
        foreach ($data as $v) {
            if ($v['parent_id'] == $parent_id) {
                $tree[] = [
                    'id' => $v['id'],
                    'level' => $level,
                    'cat_name' => $v['cat_name'],
                    'parent_id' => $v['parent_id'],
                    'children' => getTree($data, $v['id'], $level + 1),
                ];
            }
        }
    }
    return $tree;
}
/**
 * 循环获取子孙树 getSubTree($categories)
 *
 * @param array $data
 * @param int $id
 * @param int $level
 * @return array
 */
function getSubTree($data = [], $id = 0, $level = 0)
{
    static $tree = [];

    foreach ($data as $key => $value) {
        if ($value['parent_id'] == $id) {
            $value['laravel'] = $level;
            $tree[] = $value;
            getSubTree($data, $value['id'], $level + 1);
        }
    }
    return $tree;
}
/**
 * 通过pid获取所有上级分类 常用于面包屑导航 getParentsByParentId2($categories, 9)
 *
 *
 * @param array $data
 * @param $parent_id
 * @return array
 */
function getParentsByParentId($data = [], $parent_id)
{
    static $categories = [];
    
    if ($data && is_array($data)) {
        foreach ($data as $item) {
            if ($item['id'] == $parent_id) {
                $categories[] = $item;
                getParentsByParentId($data, $item['parent_id']);
            }
        }
    }
    return $categories;
}

function getParentsByParentId2($data = [], $parent_id)
{
    $categories = [];
    
    if ($data && is_array($data)) {
        foreach ($data as $item) {
            if ($item['id'] == $parent_id) {
                $categories[] = $item;
                $categories = array_merge($categories, getParentsByParentId2($data, $item['parent_id']));
            }
        }
    }
    return $categories;
}