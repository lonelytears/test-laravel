<?php

namespace App\Admin\Extensions;

use App\Look;
use Carbon\Carbon;
use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    public function export()
    {
        Excel::create('Filename', function($excel) {

            $excel->sheet('Sheetname', function($sheet) {
                $temp = [];
                $look = new Look();
                //var_dump(collect($this->getData())->toArray());die;
                $arr = collect($this->getData());
                foreach ($arr as $v) {
                    foreach ($v['look_houses'] as $item) {
                        $temps['code'] = $v['code'];
                        $temps['city_name'] = $v['city_name'];
                        $temps['community_name'] = $v['community_name'];
                        $temps['status'] = $look->status(Carbon::parse($v['look_at']),$v['status']);
                        $temps['building_name'] = $item['building_name'];
                        $temps['unit'] = $item['unit'];
                        $temps['house_name'] =$item['house_name'];
                        $temps['mobile'] = $v['user']['mobile'];
                        $temps['real_name'] = $v['user']['real_name'];
                        $temps['business_name'] = $v['user']['business_name'];
                        $temps['look_at'] = $v['look_at'];
                        $temps['validate_at'] = $v['validate_at'];
                        $temp[] = $temps;
                        $temps = [];
                    }
                }
//                dd($temp);
                $key = [
                    'code'=>'看房编号',
                    'city_name'=>'城市',
                    'community_name'=>'小区',
                    'status'=>'状态',
                    'building_name'=>'座栋',
                    'unit'=>'单元',
                    'house_name'=>'房号',
                    'mobile'=>'手机',
                    'real_name'=>'联系人',
                    'business_name'=>'公司',
                    'look_at'=>'约看时间',
                    'validate_at'=>'确认时间',
                ];
                array_unshift($temp,$key);
//                print_r($temp);die;
                $rows = array_map(function ($v){
                    return array_only($v, ['code','city_name', 'community_name','status', 'unit','building_name','house_name','real_name','mobile','business_name','look_at', 'validate_at']);
                },$temp);

                $sheet->rows($rows);

            });

        })->export('xls');
    }
}