<?php
/**
 * Created by PhpStorm.
 * User: v-zhongsm02
 * Date: 2018/8/1
 * Time: 13:49
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
//use App\Admin\Auth\Database\Look;//引用模型
use Carbon\Carbon;
use Encore\Admin\Auth\Permission;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use App\User;
use App\Look;
use App\LookHouse;
use App\Admin\Extensions\ExcelExpoter;
use function GuzzleHttp\default_ca_bundle;
use Illuminate\Support\Facades\Request;

class LookController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('看房列表');
            //$content->description('Description...');
//            $content->row($this->form());
            $content->row($this->grid());
        });
    }


    public function create()
    {
        // 检查权限，有create-post权限的用户或者角色可以访问创建文章页面
        Permission::check('create-looks');
    }

    public function edit($id)
    {

    }
    protected function form()
    {
    }

    protected function grid()
    {
        //页面显示的表格

        return Admin::grid(Look::class, function (Grid $grid) {
            //grid显示表格内容，$grid->数据库中相应的字段（‘在页面上显示的名称’）->其他方法();或者$grid->column（‘数据库中相应的字段’，‘在页面上显示的名称’）->其他方法();
            $grid->paginate(20);
            $city_id = Admin::user()->city_id;
            if (!empty($city_id)) {
                $grid->model()->where('city_id', '=',$city_id);//权限控制
            }
            // 设置初始排序条件
            $grid->model()->orderBy('look_at', 'desc');
            $grid->exporter(new ExcelExpoter());//重写导出

            $grid->id('ID')->sortable();
            $grid->code('看单编号');
            $grid->city_name('城市');
            $grid->community_name('小区战图名称');
            $grid->column('status','状态')->display(function(){
                $user = new Look();
                $status = $user->status($this->look_at, $this->status);
                switch ($status) {
                    case '已过期':
                        return "<span class='label label-danger'>" . $status . "</span>";
                    case '已确认':
                        return "<span class='label label-success'>" . $status . "</span>";
                    case '已取消':
                        return "<span class='label label-warning'>" . $status . "</span>";
                    default:
                        return "<span class='label label-info'>" . $status . "</span>";
                }
            });
            $grid->look_houses('座栋-单元-房号')->display(function ($look_houses) {
                $look_houses = array_map(function ($look_house) {
                    return "<span class='label label-success'>{$look_house['building_name']}-{$look_house['unit']}-{$look_house['house_name']}</span>";
                }, $look_houses);
                return join('&nbsp;', $look_houses);
            });

            $grid->user()->mobile('手机');
            $grid->user()->real_name('联系人');
            $grid->user()->business_name('公司');
            $grid->look_at('约看时间');
            $grid->validate_at('确认时间');

            $grid->disableActions();
//             $grid->disableExport();//禁用导出数据按钮
//            $grid->disableRowSelector();//禁止复选框
            $grid->disableCreateButton();//禁用新增

            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });


            $grid->filter(function ($filter) {
                $filter->like('code', '看房编号');//用看房编号作为条件模糊查询
                $filter->like('user.business_name', '客户公司');//用客户公司作为条件模糊查询
                $filter->where(function ($query) {
                    if ($this->input == -1) {
                        //过期：时间不是今天且是过去，约看状态为 0
                        $query->where('status', '=', 0)
                            ->where('look_at', '<=', Carbon::today());
                    }elseif ($this->input == 0) {
                        //预约：时间大于今天0点，约看状态为 0
                        $query->where('status', '=', 0)
                            ->where('look_at', '>=', Carbon::today());
                    }elseif ($this->input == 1) {
                        //确认：状态为 1
                        $query->where('status', '=',1);
                    }elseif ($this->input == 2) {
                        //取消：状态为 2
                        $query->where('status', '=',2);
                    }
                }, '状态')->select(['0' => '预约中','1' => '已确认','2' => '已取消','-1' => '已过期']);
                $filter->like('community_name', '小区');//用小区作为条件模糊查询
                //1.时间段筛选   设置created_at字段的范围查询
                $filter->between('look_at', '约看时间')->datetime();
                $filter->between('validate_at', '确定时间')->datetime();
                $filter->disableIdFilter();//禁用id查询过滤器
                //$filter->equal('status')->select(['0' => '预约中','1' => '已确认','2' => '已取消','-1' => '预约中/已过期']);

            });
        });
    }

}