<?php

namespace App\Http\Controllers;

use App\Auth;
use App\ContractSign;
use App\ContractTemplate;
use App\ContractUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Http\Request;
use App\Rules\Mobile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Encryption\DecryptException;
use App\User;
use App\Look;
use App\Contract;
use App\LookHouse;
use App\Jobs\PushWork;
use Carbon\Carbon;
use App\Services\ContractService;

class SignatureController extends Controller
{
    /**
     *签约列表
     */
    public function user_sign_list(Contract $contract,ContractUser $user)
    {
//        print_r(1111);die;
        /*$parameter_map = Input::all();
        print_r($parameter_map);die;*/
        /*$party_a_user = json_decode($user->where('mobile','=','137888553401')->get()->first());
        print_r($party_a_user);die;*/
        //todo 获取签署列表  完成
        $creator_id = 1;//测试数据
        $user_contract = json_decode($contract->where('creator_id','=',$creator_id)->get());

        foreach ($user_contract as &$item) {
            $item->party_a = $user->findById($item->party_a_id);
            $item->party_b = $user->findById($item->party_b_id);
            //如果有代理
            if (!empty($item->agent_a_id)) {
                $item->agent_a = $user->findById($item->agent_a_id);
            }
            if (!empty($item->agent_b_id)) {
                $item->agent_b = $user->findById($item->agent_b_id);
            }
            $item->status_name = $contract->get_status_name($item->status);
        }
//        $user_contract = $contract->findByCreatorId(1);
        if (empty($user_contract)) {
            return response()->json([
                'code' => 0,
                'msg'=>'暂无数据',
                'error' => '签署列表获取失败'
            ]);
        }else{
            return response([
                'msg'=>'签署列表获取成功！',
                'code'=>'200',
                'data'=>$user_contract,
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }

    }

    /**
     * 合同模板上传接口
     * post
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function upload_template(Request $request)
    {
        $file = $_FILES;
        $contract = new ContractService();

        //上传模板
        $data = $contract->upload_contract_template($file['file']['tmp_name']);
        $res = json_decode($data['res']);

        if ($res->result == "success") {

            ContractTemplate::create([
                'template_id'=>$data['template_id'],
                'template_name'=>$file['file']['name'],
            ]);

            return response([
                'msg'=>$res->msg,
                'code'=>$res->code,
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }else {
            return response()->json([
                'code' => $res->code,
                'error' => '模板上传失败',
                'msg'=>$res->msg,
            ]);
        }
    }

    /**
     * 新增签约
     */
    public function generate_contract (Request $request,ContractUser $user)
    {
        $parameter_map = $request->all();
        //校验甲方是否已经存在ca表，没有则新增
        $party_a_id = $this->check_user('mobile',request()->party_a_email,[
            'email' => request()->party_a_email,
            'customer_name' => request()->party_a_customer_name,
            'idCard' => request()->party_a_idCard,
            'mobile' => request()->party_a_mobile,
        ]);
        //校验乙方是否已经存在ca表，没有则新增
        $party_b_id = $this->check_user('mobile',request()->party_b_email,[
            'email' => request()->party_b_email,
            'customer_name' => request()->party_b_customer_name,
            'idCard' => request()->party_b_idCard,
            'mobile' => request()->party_b_mobile,
        ]);
        //是否存在甲方代理
        if (request()->agent_a_id) {
            $agent_a_id = $this->check_user('mobile',request()->agent_a_mobile,[
                'email' => request()->agent_a_email,
                'customer_name' => request()->agent_a_customer_name,
                'idCard' => request()->agent_a_idCard,
                'mobile' => request()->agent_a_mobile,
            ]);
        }

        //是否存在乙方代理
        if (request()->agent_a_id) {
            $agent_b_id = $this->check_user('mobile',request()->agent_b_mobile,[
                'email' => request()->agent_b_email,
                'customer_name' => request()->agent_b_customer_name,
                'idCard' => request()->agent_b_idCard,
                'mobile' => request()->agent_b_mobile,
            ]);
        }

        $contract = new ContractService();

        $template = request()->template;

        //生成合同
        $data = $contract->set_contract($template,$parameter_map);

        //显示获得的数据
        $res = json_decode($data['res']);

        if ($res->code == 1000) {
            //todo  合同入库
            $viewpdf_url = str_replace('×tamp', '&template', $res->viewpdf_url);
            $download_url = str_replace('×tamp', '&template', $res->download_url);
            $param = array_merge([
                'template_id' => $template,//模板编号
                'contract_id' => $data['contract_id'],//合同编号
                'viewpdf_url' => $viewpdf_url,//合同查看地址
                'download_url' => $download_url,//合同下载地址
                'creator_id' => '',//创建人
                'party_a_id' => $party_a_id,//甲方id
                'party_b_id' => $party_b_id,//乙方id
                'agent_a_id' => $agent_a_id ?? 0,//甲方代理id
                'agent_b_id' => $agent_b_id ?? 0,//乙方代理id
            ], request([
                //todo 合同的房源信息 租金信息
                /*'community_name',
                'home_name',
                'property_sn',*/
            ]));
            Contract::create($param);
            //合同房源信息插入
            $user_id = 1; //todo  测试数据写死1后期直接获取企业微信用户信息id
            ContractHouse::create(array_merge([
                'contract_id' => $data['contract_id'],//合同编号
                'creator_id' => $user_id,//创建人id
            ],request([
                'community_name',
                'home_name',
                'house_code',
                'property_sn',
                'property_user',
                'contract_id',
            ])));
            return redirect()->route('home')->with('messages', '创建合同成功');
        } else {
            return back()->withInput()->withErrors('【'.$res->code.'】'.$res->msg);
        }

    }

    /**
     * 检查用户是否已经存在，不存在则新增
     * @param string $type
     * @param $data
     * @param array $param
     * @return mixed 用户信息
     */
    protected function check_user($type='mobile',$data,$param=[])
    {
        //校验乙方是否已经存在ca表，没有则新增
        $user = new ContractUser();
        switch ($type) {
            case 'mobile':
                $user_info = $user->findByMobile($data);
                break;
            case 'id':
                $user_info = $user->findById($data);
                break;
        }
        if (empty($user_info)) {
            //不存在，新增
            $user_id = ContractUser::insertGetId($param);
        }else{
            $user_id = $user_info->id;
        }

        return $user_id;

    }

    /**
     * 签约详情
     */
    public function contract_info(Request $request,Contract $contract,ContractUser $user)
    {
        //todo  签约详情  完成
        $contract_id = $request->contract_id;
        $contract_info = $contract->findById($contract_id);

        $contract_info->party_a = $user->findById($contract_info->party_a_id);
        $contract_info->party_b = $user->findById($contract_info->party_b_id);
        //如果有代理
        if (!empty($contract_info->agent_a_id)) {
            $contract_info->agent_a = $user->findById($contract_info->agent_a_id);
        }
        if (!empty($contract_info->agent_b_id)) {
            $contract_info->agent_b = $user->findById($contract_info->agent_b_id);
        }
        $contract_info->status_name = $contract->get_status_name($contract_info->status);

        if (empty($contract_info)) {
            return response()->json([
                'code' => 0,
                'msg'=>'暂无数据',
                'error' => '签约详情获取失败'
            ]);
        }else{
            return response([
                'msg'=>'签约详情获取成功！',
                'code'=>'200',
                'data'=>$contract_info,
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }

    }

    /**
     * 获取合同模板
     */
    public function get_contract_template(ContractTemplate $template)
    {
        //todo 获取合同模板  完成
        $template_list = $template->get();
//        print_r(json_decode($template_list));
        if (empty(json_decode($template_list))) {
            return response()->json([
                'code' => 0,
                'msg'=>'暂无数据',
                'error' => '合同模板列表获取失败'
            ]);
        }else{
            return response([
                'msg'=>'合同模板列表获取成功！',
                'code'=>'200',
                'data'=>$template_list,
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }

    }

    /**
     * 获取房源
     */
    public function get_houses()
    {
        //todo 获取房源
    }

    /**
     * 重新发送短信
     * 优先级中
     */
    public function send_msg_code()
    {
        //todo  重新发送短信
    }

    /**
         * 发送支付提醒
     * 优先级中
     */
    public function send_pay_remind()
    {
        //todo  发送支付提醒
    }

    /**
     * 身份验证
     * 优先级高
     */
    public function check_user1()
    {
        //todo  身份验证
    }

    /**
     * 客户信息验证  验证客户是否已实名认证过(是否CA申请)
     * 优先级高
     */
    public function check_customer(ContractUser $user)
    {
        //todo  客户信息验证  验证客户是否已实名认证过(是否CA申请)  差用户信息从redis中获取
        $user_mobile = 15361557652;

        $user_info = $user->findByMobile($user_mobile);

        if (!empty($user_info->customerId)) {
            return response([
                'msg'=>'身份验证通过！',
                'code'=>'200',
                'data'=>$user_info,
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }else{
            return response()->json([
                'code' => 0,
                'msg'=>'身份验证不通过',
                'error' => '没有注册CA信息'
            ]);
        }
    }


    /**
     * 客户签章（手动签署合同）
     *
     */
    public function extsign_contract(Request $request,Contract $contract,ContractUser $user)
    {
        //todo  在签署回调中修改合同状态（回调方法中处理这部分逻辑） 签署交易表需要新增记录（回调测试还没有测过）
        $customer_id = $request->customer_id;
        $contract_id = $request->contract_id;
        $doc_title = $request->doc_title;
        $return_url = 'http://'.$request->server('HTTP_HOST').'/signature/notify';
        $notify_url = 'http://'.$request->server('HTTP_HOST').'/signature/notify';

        $user_info = $user->findByCustomerID($customer_id);
        $user_id = $user_info->id;
        //查询是甲方或者是乙方。决定签章关键词
        $contract_info = $contract->findByContractId($contract_id);

        if ($contract_info->party_a_id = $user_id) {
            $sign_keyword = '承租方：';
        }
        if ($contract_info->party_b_id = $user_id) {
            $sign_keyword = '承租方：';
        }
        if ($contract_info->agent_a_id = $user_id) {
            $sign_keyword = '承租方：';
        }
        if ($contract_info->agent_b_id = $user_id) {
            $sign_keyword = '承租方：';
        }

        $contractService = new ContractService();
        //测试签署
        $data = $contractService->extsign_contract('00CD90B9CA34BFCFD7C38BE76851CD3F','CO201808071758507700','万科 投资合同',1,'出租方',$return_url,$notify_url,$keyword_strategy=2,$doc_type='.pdf');
        $data = $contractService->extsign_contract($customer_id,$contract_id,$doc_title,1,$sign_keyword ?? '出租方：',$return_url,$notify_url,$keyword_strategy=2,$doc_type='.pdf');
        return response(['sign_url'=>$data['url'],'transaction_id'=>$data['transaction_id']], 200, [
            'Content-Type' => 'application/json'
        ]);
    }

    /**
     * 自动签署接口
     * @param Request $request
     * @param ContractUser $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function extsign_auto(Request $request,ContractUser $user)
    {
        //todo 校验是否公司管理员 否return

        $customer_id = $request->customer_id;
        $contract_id = $request->contract_id;
        $doc_title = $request->doc_title;
        $return_url = 'http://'.$request->server('HTTP_HOST').'/signature/notify';
        $notify_url = 'http://'.$request->server('HTTP_HOST').'/signature/notify';

        $sign_keyword = '出租方：';

        $user_info = $user->findByCustomerID($customer_id);
        $contract = new ContractService();

        $data = $contract->extsign_auto_contract($contract_id,$customer_id,$doc_title,$client_role=1,$sign_keyword,$keyword_strategy=2,$notify_url,$return_url);

        $res = json_decode($data['res']);

        if ($res->code == 1000) {
            //将本次签署信息更新到数据表
            ContractSign::create([
                'transaction_id' => $data['transaction_id'],
                'contract_id' => $contract_id,
                'customer_id' => $user_info->id,
            ]);

            //todo  自动签署 只有丙方会调用自动签署  因此签署完毕需要调用短信发送接口

            return response([
                'msg'=>'合同自动签署成功！',
                'code'=>'200',
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }else{
            return response([
                'code' => $res->code,
                'msg'=>$res->msg,
                'error' => '合同自动签署失败'
            ]);
        }
    }

    /**
     * 合同归档接口  自动发起合同成交  生成成交报告
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function filing_contract()
    {
        $contract_id = '';

        $contract = new ContractService();

        $res = json_decode($contract->filing($contract_id));

        if ($res->result == "success") {
            //todo  在contract表中将合同修改为归档状态

            //todo 自动发起合同成交  生成成交报告

            return response([
                'msg'=>'合同归档成功！',
                'code'=>'200',
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }else {
            return response()->json([
                'code' => $res->code,
                'msg'=>$res->msg,
                'error' => '合同归档失败'
            ]);
        }
    }

    /**
     * CA注册接口  实名认证
     * @param Request $request
     * @param ContractUser $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request,ContractUser $user)
    {
        $validator = Validator::make($request->all(), [
            'real_name' => 'required',
            'id_card' => 'required',
            'mobile' => ['required', new Mobile]
        ]);

        // 判断校验是否通过
        if ($validator->fails()) {

            // 捕获所有的错误
            $errors = $validator->errors();

            Log::error('Contract CA Register Errors', [
                'request' => $request->all(),
                'errors' => $errors
            ]);

            // 返回第一条错误信息
            return response()->json([
                'code' => 0,
                'error' => $errors->first()
            ]);
        } else {
            //通过验证
            $contract = new ContractService();

            //注册CA
            $res = json_decode($contract->sync_person_auto(request()->id_card,request()->mobile,request()->real_name));

            if ($res->code == 1000) {
                //申请成功 将用户CA证书号更新至数据库
                $user_info = $user->findByMobile(request()->mobile);
                if (!empty($user_info)) {
                    //更新信息
                    $user->update([
                        'customerId'=>$res->customer_id,
                    ]);
                }else{
                    //新增
                    $user->create([
                        'customer_name'=>request()->real_name,
                        'idCard'=>request()->id_card,
                        'mobile'=>request()->mobile,
                    ]);
                }

                return response([
                    'msg'=>'注册CA成功',
                    'customer_id'=>$res->customer_id,
                ], 200, [
                    'Content-Type' => 'application/json'
                ]);
            }else{
                return response()->json([
                    'code' => 0,
                    'error' => 'CA注册失败',
                    'msg'=>$res->msg
                ]);
            }
        }
    }

    /**
     * 终止签约
     */
    public function stop_contract(Request $request,Contract $contract,ContractUser $user)
    {
        $contract_id = $request->contract_id;
        $contract_info = $contract->findById($contract_id);
        //校验合同处于待丙方签章的状态
        if ($contract_info->status != 2) {
            return response()->json([
                'code' => 0,
                'msg'=>'合同不符合终止签约条件，不能终止签约',
                'error' => '合同终止签约失败'
            ]);
        }
        //todo 终止签约   校验当前用户是否合同发起人


    }

    /**
     * OCR识别身份证功能
     */
    public function ocr(Request $request)
    {
        $photo = $_FILES;
        //todo OCR识别身份证功能  完成
        $ocr_type = $request->ocr_type ? $request->ocr_type : 2;
        //todo  正式打开
        //$pic = $request->pic;
        $pic = base64_encode(file_get_contents($photo['pic']['tmp_name']));//测试写法正式前端直接传base64过来
        $pic_type = $request->pic_type ? $request->pic_type : 'jpg';

        //通过验证
        $contractService = new ContractService();

        $res = json_decode($contractService->idcard_pic_ocr($ocr_type,$pic,$pic_type));

        if ($res->code == 1 && $res->msg == 'success') {
            return response([
                'msg'=>'OCR识别身份证成功',
                'data'=>$res->data,
            ], 200, [
                'Content-Type' => 'application/json'
            ]);
        }else{
            return response()->json([
                'code' => 0,
                'error' => 'OCR识别身份证失败',
                'msg'=>$res->msg.'【'.$res->code.'】'
            ]);
        }
    }

}
