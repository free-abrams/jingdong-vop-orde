<?php
declare (strict_types=1);

namespace app\common\lib;

/**
 * 数据处理
 * Class TreeUtil
 * @package app\util
 * @author  2066362155@qq.com
 */
class TreeUtil
{

    /**
     * [getParents 获取分类id所有父级分类]
     * @param  [type] $list [2维数组]
     * @param  [type] $id   [指定id]
     * @param string $pk [主键标识]
     * @param string $pid [父级标识]
     */
    public static function getParents($list, $id, string $pk = 'id', string $pid = 'parentId'): array
    {

        $tree = [];
        foreach ($list as $v) {
            if ($v[$pk] == $id) {
                $tree[] = $v;
                $tree   = array_merge(self::getParents($list, $v[$pid]), $tree);
            }
        }

        return $tree;
    }

    /**
     * [getParentsId 获取分类id所有父级分类id]
     * @param  [type] $list [2维数组]
     * @param  [type] $id   [指定id]
     * @param string $pk [主键标识]
     * @param string $pid [父级标识]
     */
    public static function getParentsId($list, $id, string $pk = 'id', string $pid = 'parentId'): array
    {

        $tree = [];
        foreach ($list as $v) {
            if ($v[$pk] == $id) {
                $tree[] = $v[$pk];
                $tree   = array_merge(self::getParentsId($list, $v[$pid], $pk, $pid), $tree);
            }
        }

        return $tree;
    }

    /**
     * [listToTreeOne 格式化分类，生成一维数组 ,根据path 属性]
     * @param  [type]  $list   [数组]
     * @param integer $root [指定根节点]
     * @param string  $prefix [前缀标识]
     * @param string  $pk [主键标识]
     * @param string  $pid [父级标识]
     * @param string  $html [前缀字符]
     */
    public static function listToTreeOne($list, int $root = 0, string $prefix = '', string $pk = 'id', string $pid = 'parentId', string $html = 'html')
    {

        $tree = [];
        foreach ($list as $v) {
            if ($v[$pid] == $root) {
                if ($v[$pid] == 0) {
                    $level = 0;
                } else {
                    $level = count(self::getParentsId($list, $v[$pk], $pk, $pid));
                }

                $v[$html] = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $level);
                $v[$html] = $v[$html] ? ($v[$html] . $prefix) : $v[$html];
                $tree[]   = $v;
                $level++;
                $tree = array_merge($tree, self::listToTreeOne($list, $v[$pk], $prefix, $pk, $pid, $html));
            }
        }

        return $tree;
    }


    /**
     * [listToTreeMulti 格式化分类--生成多维数组 ，子数组放在child 属性中]
     * @param  [type]  $list  [数组]
     * @param integer $root [指定根节点]
     * @param string  $pk [主键标识]
     * @param string  $pid [父级标识]
     * @param string  $child [子级标识]
     */
    public static function listToTreeMulti($list, int $root = 0, string $pk = 'id', string $pid = 'parentId', string $child = 'child'): array
    {

        $tree = [];
        foreach ($list as $v) {
            if ($v[$pid] == $root) {
                $v[$child] = self::listToTreeMulti($list, $v[$pk], $pk, $pid, $child);
                $tree[]    = $v;
            }
        }

        return $tree;
    }


    /**
     * [list_to_tree 格式化分类，生成多维数组的树]
     * @param  [type]  $list  [数组]
     * @param integer $root [指定根节点]
     * @param string  $pk [主键标识]
     * @param string  $pid [父级标识]
     * @param string  $child [子级标识]
     */
    public static function list_to_tree($list, int $root = 0, string $pk = 'id', string $pid = 'parentId', string $child = 'child'): array
    {
        // 创建Tree
        $tree = [];
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = [];
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }

            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent           =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }

        return $tree;
    }


}
