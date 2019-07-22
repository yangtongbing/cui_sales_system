<?php

namespace App\Admin\Controllers;

use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Log;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $str1 = '[
        {"each_price":["10"],"is_working":"1","start_time":"00:00","end_time":"18:00","price":"10","unit_time":"120","free_time":"120","top_price":"1","license_plate":6},
        {"each_price":["10","11","12","13"],"is_working":"1","start_time":"00:00","end_time":"14:00","price":"10,11,12,13","unit_time":"20","free_time":"20","top_price":"1","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"03:00","end_time":"07:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"04:00","end_time":"09:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"04:00","end_time":"09:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"05:00","end_time":"06:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"10:00","end_time":"24:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":0},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"05:00","end_time":"05:30","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"06:00","end_time":"13:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["2","2","2","2","3"],"is_working":"1","start_time":"07:00","end_time":"19:00","price":"2,2,2,2,3","unit_time":"15","free_time":"15","top_price":"1","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"11:00","end_time":"16:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"11:00","end_time":"16:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"11:00","end_time":"16:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"11:00","end_time":"16:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6},
        {"each_price":["1","2","3","4","5"],"is_working":"1","start_time":"13:00","end_time":"20:00","price":"1,2,3,4,5","unit_time":"20","free_time":"20","top_price":"","license_plate":6}]';
        $arr = json_decode($str1, true);
//        var_dump($arr);
//        exit();

        $license = [];
        $default = [];
        foreach ($arr as $value) {
            if ($value['license_plate'] == 6) {
                $license[] = $value;
            } else {
                $default[] = $value;
            }
        }

//        $licenseKeys = array_column($license, 'start_time');
//        array_multisort($licenseKeys, SORT_ASC, $license);

//        $defaultKeys = array_column($default, 'start_time');
//        array_multisort($defaultKeys, SORT_ASC, $default);
//        var_dump($default);
//        exit;
        $licenseTmp = [];
        $defaultTmp = [];

        $i = 0;
        foreach ($license as $value) {
            $licenseTmp = $this->dataOperation($licenseTmp, $value);
            Log::info('debug|' . $i . ';res:' . json_encode($licenseTmp));
            $i++;
        }

        $j = 0;
        foreach ($default as $value) {
            $defaultTmp = $this->dataOperation($defaultTmp, $value);
            Log::info('debug|' . $j . ';res:' . json_encode($defaultTmp));
            $j++;
        }

        $this->mergeData($licenseTmp, $defaultTmp);
//        var_dump($licenseTmp);
//        var_dump($defaultTmp);
//        print_r('license' . $licenseTmp);
//        print_r('default' . $defaultTmp);
        exit;
//        $grid = new Grid(new User);
//        $grid->column('id', __('Id'));
//        $grid->column('name', __('Name'));
//        $grid->column('email', __('Email'));
//        $grid->column('password', __('Password'));
//        $grid->column('remember_token', __('Remember token'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

//        return $grid;
    }

    private function mergeData($plateTypeArr, $defaultArr)
    {
        //断层数据
        $fault = [];
        for ($i=0; $i<count($plateTypeArr); $i++) {
            if ($i == 0 && $plateTypeArr[$i]['start_time'] != '00:00') {
                $fault[] = [
                    'start_time' => '00:00',
                    'end_time' => $plateTypeArr[$i]['start_time'],
                ];
            } elseif (isset($plateTypeArr[$i+1]) &&
                ($plateTypeArr[$i]['end_time'] != $plateTypeArr[$i+1]['start_time'])
            ) {
                $fault[] = [
                    'start_time' => $plateTypeArr[$i]['end_time'],
                    'end_time' => $plateTypeArr[$i+1]['start_time'],
                ];
            }
        }

        if ($plateTypeArr[count($plateTypeArr) - 1]['end_time'] != '24:00') {
            $fault[] = [
                'start_time' => $plateTypeArr[count($plateTypeArr) - 1]['end_time'],
                'end_time' => '24:00',
            ];
        }

        //无断层，直接返回
        if (empty($fault)) {
            return $plateTypeArr;
        }

        foreach ($defaultArr as $value) {
            foreach ($fault as $v) {
                if ($value['start_time'] <= $v['start_time'] &&
                    $value['end_time'] >= $v['end_time']
                ) {
                    $value['start_time'] = $v['start_time'];
                    $value['end_time'] = $v['end_time'];
                    $plateTypeArr[] = $value;
                }
            }
        }

        return $plateTypeArr;
    }

    private function dataOperation($originData=[], $insertData=[])
    {
        if (empty($insertData) || empty($originData)) {
            $originData[] = $insertData;
            return $originData;
        }

        $temp = [];
        foreach ($originData as $key => $value) {
            //对比时间 大于，小于，等于
            //类型相等，不等 类型区别
            //全类型 指定类型
            if ($value['start_time'] > $insertData['start_time']) {
                //交叉值 比如i=>0-9 v=>7-12
                if ($value['end_time'] > $insertData['end_time']) {
                    if ($insertData['end_time'] < $value['start_time']) {
                        continue;
                    }
                    $value['start_time'] = $insertData['end_time'];
                    $temp[] = json_encode($value);
                } else if ($value['end_time'] == $insertData['end_time']) {
                    //前交叉i=>0-9  v=>7-9
                    $temp[] = json_encode($value);
                } else {
                    //包含关系 i=>0-9 v=>7-8
                    $insertData['start_time'] = $value['end_time'];
                    $temp[] = json_encode($insertData);
                    $temp[] = json_encode($value);
                }
            } else if ($value['start_time'] == $insertData['start_time']) {
                //i=>0-9 v=>0-12
                if ($value['end_time'] > $insertData['end_time']) {
                    $temp[] = json_encode($value);
                } else if ($value['end_time'] == $insertData['end_time']) {
                    // i=>0-9 v=>0-9
                    $temp[] = json_encode($value);
                } else {
                    //i=>0-9 v=>0-7
                    $insertData['start_time'] = $value['end_time'];
                    $temp[] = json_encode($insertData);
                    $temp[] = json_encode($value);
                }
            } else {
                //i=>7-9 v=>0-12
                if ($value['end_time'] > $insertData['end_time']) {
                    $temp[] = json_encode($value);
                } else if ($value['end_time'] == $insertData['end_time']) {
                    //i=>7-9 v=>0-9
                    $temp[] = json_encode($value);
                } else {
                    //i=>7-9 v=>0-8  7-14 11-24
                    if ($value['end_time'] < $insertData['start_time']) {
                        $temp[] = json_encode($value);
                        $temp[] = json_encode($insertData);
                    } else {
                        $insertData['start_time'] = $value['end_time'];
                        $temp[] = json_encode($insertData);
                        $temp[] = json_encode($value);
                    }
                }
            }
        }

        $temp = array_unique($temp);
        foreach ($temp as &$value) {
            $value = json_decode($value, true);
        }

        $keys = array_column($temp, 'start_time');
        array_multisort($keys, SORT_ASC, $temp);

        return $temp;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('password', __('Password'));
        $show->field('remember_token', __('Remember token'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new User);

        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->password('password', __('Password'));
        $form->text('remember_token', __('Remember token'));

        return $form;
    }
}
