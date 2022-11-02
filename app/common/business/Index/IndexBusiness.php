<?php

namespace app\common\business\Index;

use app\common\exception\BusinessException;
use app\common\model\mysql\Article;
use app\common\model\mysql\Story;
use app\common\model\mysql\Template;

class IndexBusiness
{

    public function storyCreate($param): bool
    {
        $Story = new Story();

        $id = $Story->add($param);

        $data = [];
        $data['content_source_url'] = 'https://m.transitorylink.com/#/?id=' . $id;

        $where = [];
        $where[] = ['id','=',$id];
        return $Story->upData($where,$data);
    }

    public function storyUpdate($param): bool
    {
        $id = $param['id'];

        $Story = new Story();

        $where   = [];
        $where[] = ['id', '=', $id];
        $where[] = ['del_status','=',0];

        $res = $Story->getInfo($where);

        if(empty($res)){
            throw new BusinessException('sys_is_empty');
        }
        unset($param['templates']);
        return $Story->upData($where, $param);
    }

    public function storyDetail($param): array
    {
        $Story = new Story();

//        $field = ['id','template_id','thumb_id','title','vice_title','author','digest','contents','comment_able','share_able'];
        $field = ['*'];
        $info = $Story->getInfo($param,$field);

        if (empty($info)) {
            throw new BusinessException('sys_is_empty');
        }

        if(!empty($info['contents'])){
            foreach ($info['contents'] as &$v){
                if(!empty($v['article_id'])){
                    $Article = new Article();

                    $where = [];
                    $where[] = ['id','=',$v['article_id']];

                    $re = $Article->getInfo($where);
                    $v['digest'] = $re['digest'];
                    $v['digest_img'] = $re['digest_img'];
                }
            }
        }

        $where = [];
        $where[] = ['id','in',$info['template_id']];
        $where[] = ['del_status','=',0];

        $field = ['id','name','img_top','img_footer'];
        $Template = new Template();
        $res = $Template->getInfo($where,$field);

        $info['templates'] = [
            'img_top' => $res['img_top'],
            'img_footer' => $res['img_footer'],
        ];

        return $info;
    }

    public function storyRemove($param): bool
    {
        $id = $param['id'];

        $where   = [];
        $where[] = ['id', '=', $id];
        $where[] = ['del_status', '=', 0];

        $Story = new Story();
        $res     = $Story->getInfo($where);
        if (empty($res)) {
            throw new BusinessException('sys_is_empty');
        }

        $data               = [];
        $data['deleted_at'] = date('Y-m-d H:i:s', time());
        $data['del_status']  = 1;

        return $Story->upData($where, $data);
    }

    public function storyList($param): array
    {

        $page      = $param['page'];
        $page_size = $param['page_size'];

        $where   = [];
        $where[] = ['del_status', '=', 0];

        if(!empty($param['search_name'])){
            $name = $param['search_name'];
            $where[] = ['title','like','%'.$name.'%'];
        }

//        $field = ['id','template_id','views','thumb_id','title','comment_able','share_able','content_source_url','created_at'];
        $field = ['*'];

        $Story = new Story();
        return $Story->pageList($where, $page, $page_size, $field);
    }

    public function storyViews($param): bool
    {
        $id = $param['id'];

        $where   = [];
        $where[] = ['id', '=', $id];
        $where[] = ['del_status', '=', 0];

        $Story = new Story();
        $info     = $Story->getInfo($where);
        if (empty($info)) {
            throw new BusinessException('sys_is_empty');
        }

        $data               = [];
        $data['views'] = $info['views']+=1;

        return $Story->upData($where, $data);
    }

}
