<?php

namespace app\common\model\mysql;


use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\model\Pivot;

class BaseModel extends Pivot
{
    private static $query_obj = null;

//    public $table;
//
//    public function __construct($table = '')
//    {
////        $this->table = $table;

//    }

    public $append = [];
    public $hidden = [];
    public $with = null;
    public $fillable = null;

    # 获取单条数据
    final public function getInfo(array $where = [], $field = true, string $alias = 'a', $join = null, string $order = '', $data = null): ?array
    {
        $db_obj = $this;

        if (!empty($this->with)) {
            $db_obj = $this->with($this->with);
        }

        if (!empty($join)) {
            $db_obj = $this->alias($alias);
            $db_obj = $this->parseJoin($db_obj, $join);
        }

        $result = $db_obj->where($where)->order($order)->field($field)->find($data);

        return $result ? $this->objToArray($result) : null;
    }

    /**
     * 获取列表数据
     * @param array       $where
     * @param null        $limit
     * @param bool|string|array $field
     * @param string      $alias
     * @param null        $join
     * @param string      $order
     * @param string      $group
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    final public function getList(array $where = [], $limit = null, $field = true, string $alias = 'a', $join = null, string $order = 'id desc', string $group = ''): array
    {
        self::$query_obj = $this->where($where)->order($order);
        if (!empty($join)) {
            self::$query_obj = self::$query_obj->alias($alias);
            self::$query_obj = $this->parseJoin(self::$query_obj, $join);
        }

        if (!empty($group)) {
            self::$query_obj = self::$query_obj->group($group);
        }

        if (!empty($limit)) {
            self::$query_obj = self::$query_obj->limit($limit);
        }

        if (!empty($this->with)) {
            self::$query_obj = self::$query_obj->with($this->with);
        }

        $result = self::$query_obj->field($field)->select();

        return $this->objToArray($result);
    }

    /**
     * 获取分页列表数据
     * @param array       $where
     * @param int         $page
     * @param int         $limit
     * @param bool|string $field
     * @param string      $alias
     * @param null        $join
     * @param string      $order
     * @param string      $group
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    final public function pageList(array $where = [], int $page = 1, int $limit = 10, $field = true, string $alias = 'a', $join = null, string $order = 'id desc', string $group = ''): array
    {
        self::$query_obj = $this->where($where)->order($order);

        if (!empty($this->with)) {
            self::$query_obj = self::$query_obj->with($this->with);
        }

        if (!empty($join)) {
            self::$query_obj = self::$query_obj->alias($alias);
            self::$query_obj = $this->parseJoin(self::$query_obj, $join);
        }

        if (!empty($group)) {
            self::$query_obj = self::$query_obj->group($group);
        }

        $paginate = [
            'page'      => $page,
            'list_rows' => $limit,
            'var_page'  => 'page',
        ];

        $result = self::$query_obj->field($field)->paginate($paginate);

        return $this->objToArray($result);
    }

    /**
     * 获取表所有数据
     * @return array|\think\Collection|Db[]
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    final public function selectAll()
    {
        $result = $this->select();
        return $this->objToArray($result);
    }


    final private function objToArray($obj)
    {
        return $obj->append($this->append)->hidden($this->hidden)->toArray();
    }

    final public function getTotal(array $where = []): int
    {
        $result = $this->where($where)->count();
        return max($result, 0);
    }


    /**
     * join分析
     * @param       $db_obj
     * @param array $join
     * @return mixed
     */
    protected function parseJoin($db_obj, array $join)
    {
        foreach ($join as $item) {
            [$table, $on, $type] = $item;
            $type = strtolower($type);
            switch ($type) {
                case "left":
                    $db_obj = $db_obj->leftJoin($table, $on);
                    break;
                case "inner":
                    $db_obj = $db_obj->join($table, $on);
                    break;
                case "right":
                    $db_obj = $db_obj->rightjoin($table, $on);
                    break;
                case "full":
                    $db_obj = $db_obj->fulljoin($table, $on);
                    break;
                default:
                    break;
            }
        }
        return $db_obj;
    }

    /**
     * 添加数据
     * @param array $data
     * @return int|string
     */
    final public function add(array $data)
    {
        if (is_array($this->fillable) && !empty($this->fillable)) {
            $data = array_intersect_key($this->fillable, $data);
        }
        return self::save($data);
    }

    /**
     * 修改数据
     * @param array $where
     * @param array $data
     * @return bool
     */
    final public function upData(array $where = [], array $data = []): bool
    {
        if (is_array($this->fillable) && !empty($this->fillable)) {
            $data = array_intersect_key($this->fillable, $data);
        }
        $res = self::update($data,$where);
        return (bool)$res;
    }

    final public function upIncData(array $where = [], string $key = '', int $val = 1): bool
    {
        return $this->where($where)->inc($key, $val)->update();
    }

    /**
     * 删除数据
     * @param $where
     * @param $id
     * @param $destroy
     * @return int
     * @throws DbException
     */
    final public function delOne($where, $id = '', $destroy = ''): int
    {
        return self::destroy($where);
        return $this->where($where)->delete();
//        if (empty($id)) {
//           return Db::name($this->table)->where($where)->delete($id);
//         } else {
//             return Db::name($this->table)->where($where)->destroy($destroy);
//         }
    }

//    final public function delete(array $where = [], $data = null): bool
//    {
//        return $this->where($where)->delete($data);
//    }

    /**
     * 事物开启
     */
    final public function startTrans()
    {
        Db::startTrans();
    }

    /**
     * 事物提交
     */
    final public function commit()
    {
        Db::commit();
    }

    /**
     * 事物回滚
     */
    final public function rollback()
    {
        Db::rollback();
    }


}
