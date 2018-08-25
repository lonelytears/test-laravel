<?php
return [
    'unique'               => ':attribute 已存在',
    'accepted'             => ':attribute 是被接受的',
    'active_url'           => ':attribute 必须是一个合法的 URL',
    'after'                => ':attribute 必须是 :date 之后的一个日期',
    'after_or_equal'       => ':attribute 必须是 :date 之后或相同的一个日期',
    'alpha'                => ':attribute 必须全部由字母字符构成。',
    'alpha_dash'           => ':attribute 必须全部由字母、数字、中划线或下划线字符构成',
    'alpha_num'            => ':attribute 必须全部由字母和数字构成',
    'array'                => ':attribute 必须是个数组',
    'before'               => ':attribute 必须是 :date 之前的一个日期',
    'before_or_equal'      => ':attribute 必须是 :date 之前或相同的一个日期',
    'between'              => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间',
        'file'    => ':attribute 必须在 :min 到 :max KB 之间',
        'string'  => ':attribute 必须在 :min 到 :max 个字符之间',
        'array'   => ':attribute 必须在 :min 到 :max 项之间',
    ],
    'boolean'              => ':attribute 字符必须是 true 或 false',
    'confirmed'            => ':attribute 二次确认不匹配',
    'date'                 => ':attribute 必须是一个合法的日期',
    'date_format'          => ':attribute 与给定的格式 :format 不符合',
    'different'            => ':attribute 必须不同于: other',
    'digits'               => ':attribute 必须是 :digits 位',
    'digits_between'       => ':attribute 必须在 :min and :max 位之间',
    'email'                => ':attribute 必须是一个合法的电子邮件地址。',
    'filled'               => ':attribute 的字段是必填的',
    'exists'               => '选定的 :attribute 是无效的',
    'image'                => ':attribute 必须是一个图片 (jpeg, png, bmp 或者 gif)',
    'in'                   => '选定的 :attribute 是无效的',
    'integer'              => ':attribute 必须是个整数',
    'ip'                   => ':attribute 必须是一个合法的 IP 地址。',
    'max'                  => [
        'numeric' => ':attribute 的最大长度为 :max 位',
        'file'    => ':attribute 的最大为 :max',
        'string'  => ':attribute 的最大长度为 :max 字符',
        'array'   => ':attribute 的最大个数为 :max 个',
    ],
    'mimes'                => ':attribute 的文件类型必须是: values',
    'min'                  => [
        'numeric' => ':attribute 的最小长度为 :min 位',
        'string'  => ':attribute 的最小长度为 :min 字符',
        'file'    => ':attribute 大小至少为: min KB',
        'array'   => ':attribute 至少有 :min 项',
    ],
    'not_in'               => '选定的 :attribute 是无效的',
    'numeric'              => ':attribute 必须是数字',
    'regex'                => ':attribute 格式是无效的',
    'required'             => ':attribute 字段必须填写',
    'required_if'          => ':attribute 字段是必须的当 :other 是 :value',
    'required_with'        => ':attribute 字段是必须的当 :values 是存在的',
    'required_with_all'    => ':attribute 字段是必须的当 :values 是存在的',
    'required_without'     => ':attribute 字段是必须的当 :values 是不存在的',
    'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的',
    'same'                 => ':attribute 和 :other 必须匹配',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size 位',
        'file'    => ':attribute 必须是 :size KB',
        'string'  => ':attribute 必须是 :size 个字符',
        'array'   => ':attribute 必须包括 :size 项',
    ],
    'url'                  => ':attribute 无效的格式',
    'timezone'             => ':attribute 必须个有效的时区',
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */
    'attributes'           => [
        // 用户信息
        'username' => '用户名',
        'mobile'   => '手机号',
        'real_name' => '真实名称',
        'sex' => '性别',
        'id_card' => '身份证号码',
        'user_business_code' => '中介从业资格编号',
        'city_id' => '城市标识',
        'city_name' => '城市名称',
        'business_name' => '所属中介公司',
        'id_card_photo_front' => '身份证正面',
        'id_card_photo_reverse' => '身份证背面',

        // 看单信息
        'community_code' => '小区标识',
        'community_name' => '小区名称',
        'building_code' => '座栋编码',
        'building_name' => '座栋名称',
        'unit' => '单元',
        'housefloor' => '楼层数',
        'house_code' => '房屋编码',
        'house_name' => '房屋名称',
        'orientation' => '朝向',
        'layout' => '户型',
        'build_area' => '建筑面积',
        'inner_area' => '使用面积',
        'look_at' => '约看时间',
        'validate_at' => '验证时间',
        'validate_user_id' => '验证用户标识',
        'validate_user_mobile' => '验证用户手机号',
        'status' => '状态',
        'validate_code' => '验证码',

        // 招聘
        'job_name' => '姓名',
        'birthday' => '生日',
        'education' => '学历'
    ],

    'dates' => [
        'now' => '当前',
        'tomorrow' => '明天',
        'yesterday' => '昨天',
    ]
];