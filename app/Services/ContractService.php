<?php

namespace App\Services;

use CURLFile;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * 电子签章系统服务
 */
class ContractService
{
    /**
     * 接口请求方法
     * @param  [type] $data [description]
     * @param  [type] $url  [description]
     * @return [type]       [description]
     */
    public function request($data, $url, $method='POST')
    {
        $client = new Client();

        $response = $client->request($method, $url, [
            'http_errors' => false,
//            'headers' => self::buildHeader($data),
            'form_params' => $data
        ]);

        Log::info('Contract API Request', [
            'url' => $url,
            'data' => $data,
            'method' => $method,
            'code' => $response->getStatusCode(),
            'body' => json_decode($response->getBody(), true)
        ]);

        return $response->getBody();
    }

    public function post_files($data, $url)
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直    接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        return $data;

    }

    /**
     * 生成合同
     * @param string $template
     * @param array $parameter_map
     */
    public function set_contract($template,$parameter_map=[])
    {
        $parameter_map = [
            'Text2'=>'sz20180000249',
            'Text3'=>'1',
            'Text4'=>date('Y_m-d',time()),
            'Text5'=>'RO003859765',
            'fill_3'=>'林贞3',
            'fill_4'=>'430103198308240029',
            'fill_5'=>'深圳市福田区梅林路63号',
            'fill_6'=>'13788855340',
            'fill_7'=>'',
            'undefined'=>'',
            'fill_10'=>'曾波',
            'fill_11'=>'513922198612183298',
            'fill_13'=>'深圳市福田区梅林路63号',
            'fill_14'=>'13788855340',
            'fill_17'=>'',
//    'undefined2'=>'',
            'fill_19'=>'深圳市福田区梅林路63号梅林万科中心第二层',
            'fill_20'=>'福田',
            'fill_21'=>'万科',
            'fill_22'=>'肖琳',
            'fill_23'=>'120',
            'fill_24'=>'2017 0035523',
            'fill_25'=>'2017',
            'fill_26'=>'7',
            'fill_27'=>'15',
            'fill_28'=>'2018',
            'fill_29'=>'7',
            'fill_30'=>'15',
            'fill_31'=>'7',
            'fill_1'=>'30',
            'fill_2'=>'10000',
            'fill_9'=>'28000',
            'fill_12'=>'28000',
            'fill_15'=>'壹仟',
            'fill_16'=>'1000',
            'fill_32'=>'壹萬',
            'fill_33'=>'10000',
            'fill_34'=>'伍仟',
            'fill_35'=>'5000',
            'fill_36'=>'伍仟',
            'fill_37'=>'5000',
            'fill_38'=>'中国工商银行',
            'fill_39'=>'陌**',
            'fill_40'=>'6212236987556423669',
            'fill_41'=>'2018',
            'fill_42'=>'7',
            'fill_43'=>'15',
            'fill_44'=>'伍仟',
            'fill_45'=>'5000',
            'fill_46'=>'维修基金',
            'fill_47'=>'水电',
            'fill_48'=>'2018',
            'fill_49'=>'7',
            'fill_50'=>'15',
            'fill_51'=>'3',
            'fill_53'=>'15000',
        ];
        $timpstamp = date ( "YmdHis" );
        //合同生成
        $contract_id = $this->get_unique_str('CO');//合同编号
        $msg_digest = base64_encode ( strtoupper ( sha1 ( config('services.contract.APPID') . strtoupper ( md5 ( $timpstamp ) ) . strtoupper ( sha1 ( config('services.contract.APPSECRET').$template.$contract_id) ) . json_encode($parameter_map)) ) ); // 消息摘要
        $url = config('services.contract.url').'generate_contract.action';

        //设置post数据
        $post_data = array (
            "app_id" => config('services.contract.APPID'),
            "timestamp" => $timpstamp,
            "v" => config('services.contract.v'),
            'parameter_map' =>json_encode($parameter_map),//
            'template_id' =>$template,//模板编号
            'contract_id' =>$contract_id,//合同编号
            //    'file' =>$files,//PDF 模板   文件流
            "msg_digest" => $msg_digest
        );

        //请求资源结果
        $res = $this->request($post_data, $url);

        $data = [
            'contract_id'=>$contract_id,
            'res'=>$res
        ];
        return $data;
    }


    /**
     * 手动签署合同
     * @param $customer_id
     * @param $contract_id
     * @param $doc_title
     * @param $client_role
     * @param $sign_keyword
     * @param $return_url
     * @param $notify_url
     * @param int $keyword_strategy
     * @param string $doc_type
     * @return string
     */
    public function extsign_contract($customer_id,$contract_id,$doc_title,$client_role,$sign_keyword,$return_url,$notify_url,$keyword_strategy=2,$doc_type='.pdf')
    {
//        $customer_id = '00CD90B9CA34BFCFD7C38BE76851CD3F';// 客户编号
//        $contract_id = "CO201808071758507700"; // 合同编号
//        $doc_title = "万科 投资合同"; // 合同标题
//        $client_role = "1"; // 签署角色
//        $doc_type = ".pdf"; // 文档类型
//        $sign_keyword = '出租方：';//能被ctrl+f查找功能检索到关键词，用于签名定位
//        $keyword_strategy = 2;//最后一个关键字签章； 1第一个关键词签  0所有关键词签

        //手动签署
        $api_url = config('services.contract.url')."extsign.action";
        $doc_url = "https://testapi.fadada.com:8443/api//viewContract.action?app_id=401407&v=2.0&timestamp=20180807175851&contract_id=CO201808071758507700&msg_digest=NDYyMzZBMzEzRTY1MTREREYzQzdGNUZGODg1RkRERjdFRUVCMEM0OA==";// 文档地址
        $timpstamp =  date ( "YmdHis" );
        $transaction_id = $this->get_unique_str('TR'); // 交易号
        $sha1 = strtoupper ( sha1 ( config('services.contract.APPSECRET') . $customer_id . $doc_url ) );
        $md5 = strtoupper ( md5 ( $transaction_id . $timpstamp ) );
        $sha2 = strtoupper ( sha1 ( config('services.contract.APPID') . $md5 . $sha1 ) );
        $base64 = base64_encode ( $sha2 );
        $return_url = $return_url."?transaction_id=".$transaction_id.'&contract_id='.$contract_id.'&customer_id='.$customer_id;//回调地址
        $notify_url = $notify_url."?transaction_id=".$transaction_id.'&contract_id='.$contract_id.'&customer_id='.$customer_id;

        $url = $api_url . "?timestamp=" . $timpstamp . "&transaction_id=" . $transaction_id ."&sign_keyword=" . $sign_keyword ."&keyword_strategy=" . $keyword_strategy . "&contract_id=" . $contract_id . "&doc_type=" . $doc_type . "" . "&return_url=" . urlencode ( $return_url ) . "&client_role=" . $client_role . "&customer_id=" . $customer_id . "" . "&doc_title=" . urlencode ( $doc_title ) . "&doc_url=" . urlencode ( $doc_url ) . "" . "&app_id=" . config('services.contract.APPID') . "&msg_digest=" . $base64 . "&notify_url=" . urlencode ( $notify_url ) . "&v=".config('services.contract.v');

        return [
            'url'=>$url,
            'transaction_id'=>$transaction_id
        ];
    }

    /**
     * 自动签署
     * @param $contract_id
     * @param $customer_id
     * @param $doc_title
     * @param int $client_role
     * @param $sign_keyword
     * @param int $keyword_strategy
     * @param $notify_url
     * @param $return_url
     * @return array
     */
    public function extsign_auto_contract($contract_id,$customer_id,$doc_title,$client_role=1,$sign_keyword,$keyword_strategy=2,$notify_url,$return_url)
    {
        $timpstamp = date ( "YmdHis" );

        $transaction_id = $this->get_unique_str('TR');//交易号
        $notify_url = $notify_url."?transaction_id=".$transaction_id.'&contract_id='.$contract_id.'&customer_id='.$customer_id;
        $return_url = $return_url."?transaction_id=".$transaction_id.'&contract_id='.$contract_id.'&customer_id='.$customer_id;

        $msg_digest = base64_encode ( strtoupper ( sha1 ( config('services.contract.APPID') . strtoupper ( md5 ( $transaction_id.$timpstamp ) ) . strtoupper ( sha1 ( config('services.contract.APPSECRET').$customer_id) ) ) ) ); // 消息摘要
        $url = config('services.contract.url');

        //设置post数据
        $post_data = array (
            "app_id" => config('services.contract.APPID'),
            "timestamp" => $timpstamp,
            "v" => config('services.contract.v'),
            'transaction_id' =>$transaction_id,//交易号 只允许长度<=32 的英文或数字字符
            'contract_id' =>$contract_id,
            "customer_id" => $customer_id,
            "client_role" => $client_role,//客户角色 1-接入平台；2-仅适用互金行业担保公司或担保人；3-接入平台客户（互金行业指投资人）；4-仅适用互金行业借款企业或者借款人
            "doc_title" => $doc_title,//合同标题
            "return_url" => $return_url,//页面跳转URL（签署结果同步通知）
            'position_type' => 0, //定位类型
            'sign_keyword' => $sign_keyword,
            'keyword_strategy' => $keyword_strategy,
            'notify_url' => $notify_url,
            "msg_digest" => $msg_digest
        );

        //请求资源结果
        $data = $this->request($post_data, $url);

        return [
            'transaction_id' => $transaction_id,
            'res' => $data
        ];
    }

    /**
     * 合同归档
     * @param $contract_id
     * @return \Psr\Http\Message\StreamInterface
     */
    public function filing($contract_id)
    {
        $timpstamp = date ( "YmdHis" );
//        $contract_id = "LG0346469422";//合同编号
        $msg_digest = base64_encode ( strtoupper ( sha1 ( config('services.contract.APPID') . strtoupper ( md5 ( $timpstamp ) ) . strtoupper ( sha1 ( config('services.contract.APPSECRET').$contract_id) ) ) ) ); // 消息摘要
        $url = config('services.contract.url').'contractFiling.action';

        //设置post数据
        $post_data = array (
            "app_id" => config('services.contract.APPID'),
            "timestamp" => $timpstamp,
            "v" => config('services.contract.v'),
            'contract_id' =>$contract_id,//合同编号 只允许长度<=32 的英文或数字字符
            "msg_digest" => $msg_digest
        );

        //请求资源结果
        return $this->request($post_data, $url);
    }

    /**
     * CA注册申请
     * @param $idCard
     * @param $mobile
     * @param $customerName
     * @return \Psr\Http\Message\StreamInterface
     */
    public function sync_person_auto($idCard,$mobile,$customerName)
    {
        $timpstamp = date ( "YmdHis" );

        $id_mobile = Crypt3Des::encrypt ( $idCard . "|" . $mobile, config('services.contract.APPSECRET')); // 对身份证、手机号进行3des加密

        $msg_digest = base64_encode ( strtoupper ( sha1 ( config('services.contract.APPID') . strtoupper ( md5 ( $timpstamp ) ) . strtoupper ( sha1 ( config('services.contract.APPSECRET') ) ) ) ) ); // 消息摘要

        $url = config('services.contract.url').'syncPerson_auto.action';

        //设置post数据
        $post_data = array (
            "app_id" => config('services.contract.APPID'),
            "timestamp" => $timpstamp,
            "v" => config('services.contract.v'),
            "customer_name" => $customerName,
            //"email" => $email,
            "id_mobile" => $id_mobile,
            "msg_digest" => $msg_digest
        );
        //请求资源结果
        return $this->request($post_data, $url);
    }

    /**
     * 合同模板上传
     * @param $file
     * @return array
     */
    public function upload_contract_template($file)
    {
        $timpstamp = date ( "YmdHis" );
        $template = $this->get_unique_str('TM');

        $msg_digest = base64_encode ( strtoupper ( sha1 ( config('services.contract.APPID') . strtoupper ( md5 ( $timpstamp ) ) . strtoupper ( sha1 ( config('services.contract.APPSECRET').$template) ) ) ) ); // 消息摘要
        $url = config('services.contract.url').'uploadtemplate.action';

        //设置post数据
        $post_data = array (
            "app_id" => config('services.contract.APPID'),
            "timestamp" => $timpstamp,
            "v" => config('services.contract.v'),
            'template_id' =>$template,//模板编号 只允许长度<=32 的英文或数字字符
            'file' =>new CURLFile(realpath($file)),//PDF 模板   文件流
            "msg_digest" => $msg_digest
        );

        //请求资源结果
        $data = $this->post_files($post_data, $url);

        return [
            'template_id'=>$template,
            'res'=>$data
        ];
    }


    /**
     * 生成合同、模板、交易编号。。。
     *合同：CO  模板：TM   交易号：TR
     * @return string
     */

    public function get_unique_str ($type = 'CO')
    {
        return $type.date('Y').date('m').date('d').date('H').date('i').date('s').rand(1000,9999);
    }

    public function idcard_pic_ocr($ocr_type,$pic_base64,$pic_type)
    {
        $timpstamp = date ( "YmdHis" );
//        $contract_id = "LG0346469422";//合同编号
        $msg_digest = base64_encode ( strtoupper ( sha1 ( config('services.contract.APPID') . strtoupper ( md5 ( $timpstamp ) ) . strtoupper ( sha1 ( config('services.contract.APPSECRET').$ocr_type.$pic_base64.$pic_type) ) ) ) ); // 消息摘要
        $url = config('services.contract.url').'idcard_pic_ocr.api';

        //设置post数据
        $post_data = array (
            "app_id" => config('services.contract.APPID'),
            "timestamp" => $timpstamp,
            "v" => config('services.contract.v'),
            'ocr_type' =>$ocr_type,//图片类型1：一代 2：二代正面 3：二代反面 4：临时身份证
            'pic_base64' =>$pic_base64,//base64 图片
            'pic_type' =>$pic_type,//图片格式 png、jpg、bmp
            "msg_digest" => $msg_digest
        );

        //请求资源结果
        return $this->request($post_data, $url);
    }


}