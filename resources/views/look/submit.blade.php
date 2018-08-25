@extends('layout.main')

@section('title', '提交约看')

@section('contents')

    <!-- 社区community摸态框内容 -->
    <div id="about" class="weui-popup__container">
        <div class="weui-popup__overlay"></div>
        <div class="weui-popup__modal">
            <div class="weui-search-bar weui-search-bar_focusing" id="communitySearchBar">
                <form class="weui-search-bar__form">
                    <div class="weui-search-bar__box">
                        <i class="weui-icon-search"></i>
                        <input type="search" class="weui-search-bar__input" id="communityInput" placeholder="搜索楼盘名 如：金色领域" required="" autocomplete="off" autofocus="autofocus" />
                        <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
                    </div>
                    <label class="weui-search-bar__label" id="searchText">
                        <i class="weui-icon-search"></i>
                        <span>搜索楼盘名 如：金色领域</span>
                    </label>
                </form>
                <a href="javascript:" class="weui-search-bar__cancel-btn close-popup">返回</a>
            </div>
            <!-- 遍历搜索结果列表展示 -->
            <ul id="cityNameList">
                <!-- <li>
                    <div class="weui-cells weui-cells_radio">
                        <label class="weui-cell weui-check__label" for="x11">
                            <div class="weui-cell__bd">
                                <p></p>
                            </div>
                            <div class="weui-cell__ft">
                                <input type="radio" class="weui-check" name="radio1" id="x11">
                                <span class="weui-icon-checked"></span>
                            </div>
                        </label>
                    </div>
                </li> -->
            </ul>
        </div>
    </div>
    <!-- 单元unit+房间号room摸态框内容 -->
    <div id="unit" class="weui-popup__container">
        <div class="weui-popup__overlay"></div>
        <div class="weui-popup__modal">
            <div class="weui-search-bar weui-search-bar_focusing" id="searchBar">
                <form class="weui-search-bar__form">
                    <div class="weui-search-bar__box">
                        <i class="weui-icon-search"></i>
                        <input type="search" class="weui-search-bar__input" id="houseInput" placeholder="搜索房间名 如：101" required="" autocomplete="off" />
                        <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
                    </div>
                    <label class="weui-search-bar__label" id="searchText">
                        <i class="weui-icon-search"></i>
                        <span>搜索房间名 如：101</span>
                    </label>
                </form>
                <a href="javascript:" class="weui-search-bar__cancel-btn close-popup">返回</a>
            </div>
            <!-- 遍历搜索结果列表展示 -->
            <ul id="houseNameList">
                <!-- <li>
                    <div class="weui-cells weui-cells_radio">
                        <label class="weui-cell weui-check__label" for="x11">
                            <div class="weui-cell__bd">
                                <p></p>
                            </div>
                            <div class="weui-cell__ft">
                                <input type="radio" class="weui-check" name="radio1" id="x11">
                                <span class="weui-icon-checked"></span>
                            </div>
                        </label>
                    </div>
                </li> -->
            </ul>
        </div>
    </div>
    <!-- 原页面表单内容	 -->
    <form action="{{url('/look/submit')}}" id="mainForm" method="post">
        {{csrf_field()}}
        <div class="weui_cells" id="InputsWrapper">
            <input type="hidden" value="0" id="item_box_number">
            <div class="weui-cell weui_cell_select weui_select_after">
                <div class="weui-cell__hd">
                    <label class="weui-label">城市名</label>
                </div>
                <input name="city_id" type="hidden">
                <div class="weui-cell__bd weui_cell_primary">
                    <input class="weui-input" id="submit_order_city" name="city_name" type="text" placeholder="请选择城市名"/>
                </div>
            </div>

            <div class="weui-cell weui_cell_select weui_select_after">
                <div class="weui-cell__hd">
                    <label class="weui-label">约看时间</label>
                </div>
                <div class="weui-cell__bd weui_cell_primary">
                    <input class="weui-input" name='look_at' id="datetime-picker" type="text" placeholder="仅限当天约看" readonly="readonly" value="{{date('Y-m-d H:i', strtotime('+10 minutes'))}}" />
                </div>
            </div>


            <div class="weui-cell weui_cell_select weui_select_after">
                <div class="weui-cell__hd">
                    <label class="weui-label">楼盘名</label>
                </div>
                <input name="community_code" type="hidden">
                <div class="weui-cell__bd weui_cell_primary">

                    <a href="javascript:" class="open-popup" data-target="#about">
                        <input class="weui-input" id="submit_order_building" name="community_name" type="text" placeholder="请选楼盘名"/>
                    </a>
                </div>
            </div>

            <div id="InputsWrapperBoxList">
                <div id="InputsWrapperBox">
                    <div class="weui-cell weui_cell_select weui_select_after card_title">
                        <p>约看房间1</p>
                        <img class=" card_del" src="{{url('/image/close-circle.png')}}" alt="">
                    </div>
                    <div class="weui-cell weui_cell_select weui_select_after bg_color_building">
                        <div class="weui-cell__hd">
                            <label class="weui-label">期数座栋</label>
                        </div>
                        <input name="building_code" type="hidden">
                        <div class="weui-cell__bd weui_cell_primary">
                            <input class="weui-input" id="submit_order_term"  name="building_name" type="text" placeholder="请选择期数座栋"/>
                        </div>
                    </div>

                    <div class="weui-cell weui_cell_select weui_select_after bg_color_unit">
                        <div class="weui-cell__hd">
                            <label class="weui-label">单元房号</label>
                        </div>
                        <input name="unit" type="hidden">
                        <input name="house_code" type="hidden">
                        <input name="house_name" type="hidden">
                        <div class="weui-cell__bd weui_cell_primary">
                            <a href="javascript:" class="open-popup" data-target="#unit">
                                <input class="weui-input submit_order_room" id="submit_order_room" type="text" placeholder="请选择单元房号" data-item_num="0" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="AddButtonBox">
            <div id="AddButton">
                <img src="{{url('/image/plus-circle.png')}}" alt="">
                <p>添加更多约看房间</p>
            </div>
        </div>
        <div class="my_info_btn">
            <input type="hidden" name="house_list" value="" />
            <a href="" class="weui-btn weui-btn-mini weui-btn_primary" id="submitFormData">确认提交</a>
            <a href="{{url('/')}}" class="weui-btn weui-btn-mini weui-btn_default">返回</a>
        </div>
    </form>
@endsection

@section('extend')
    <script>
        (function(){
            var currentSelectedCityId = null;
            var currentSelectedBuildingCode = null;
            var currentSelectedCommunityCode = null;
            var	FieldCount = 1;
            //这部分可以公用    只需要结果就是异步  then
            function getNameData(type, url , data){
                return $.ajax({
                    type: type,
                    url: url,
                    data: data,
                    beforeSend:function(data){
                        if(data.readyState == 0){
                            $.showLoading("数据加载中");
                        }
                    },
                    complete: function (res){
                        // console.log(res)
                        //可以拿到请求响应结构  包含错误码  error success的集合
                        $.hideLoading();
                    }
                })
            };
            function renderCitySelect(selector,title,datalist, selectedCb){
                $("#"+selector).select({
                    title: title,
                    multi: false,
                    items: datalist,
                    onChange: selectedCb
                });
            };
            //城市请求接口............................................................开始
            getNameData("POST", "{{url('/api/getCity')}}").then(cityDataFormat);
            function cityDataFormat(data){
                // console.log(data.data);
                if (data && data.data) {
                    for (var i = 0; i < data.data.length; i++) {
                        $.extend(data.data[i], {
                            title: data.data[i]["city_name"],
                            value: data.data[i]["city_id"]
                        })
                    }
                    // console.log(data.data);
                    //渲染城市下拉框
                    renderCitySelect("submit_order_city","选择城市", data.data, getSelectedCity);
                }
            };
            function getSelectedCity(selectedCityId){
                currentSelectedCityId = selectedCityId.values;
                $("input[name ='city_id']").val(currentSelectedCityId);
                //监听城市名变化,当要改变时情况下面输入框的值
                $("#submit_order_city").on("change",function(){
                    if($(this).val()!==""){
                        $("#submit_order_building,#submit_order_term,#submit_order_room ").val("");
                        $("input[name ='building_code']").val('');
                        $("input[name ='house_code']").val('');
					    getCommunityNumber();
                        delIpt();
                    }
                })
            };
            //城市请求接口............................................................结束
            //楼盘请求接口............................................................开始
            // 搜索页input自动获取焦点
            function getCommunityNumber(selectedCityId){
                // console.log(selectedCityId)
                // console.log(currentSelectedCityId);
                $('body').height($('body')[0].clientHeight);
                $('input').focus(function(){
                    $('body').height($('body')[0].clientHeight);
                })
                // 键盘事件 取keyword值
                $("#communityInput").bind('input propertychange', function(){
                    var keywords = $(this).val();
                    // console.log(keywords);
                    keywords.replace(/ /g, "");
                    if(!keywords){return}
                    // return;
                    // console.log(currentSelectedCityId);
                    var j = {
                        page : 1,
                        city_id : currentSelectedCityId,
                        community_name : keywords
                    };
                    $.ajax({
                        type:'POST',
                        data:j,
                        url:'{{url('/api/getCommunity')}}',
                        beforeSend:function(data){
                            if(data.readyState == 0){
                                $.showLoading("数据加载中");
                            }
                        },
                        success:function(data){
                            // console.log(data);
                            if (data && data.data && data.data.list && data.data.list.length) {
                                var cityList = '';
                                for (var i = 0; i < data.data.list.length; i++) {
                                    $.extend(data.data.list[i], {
                                        value: data.data.list[i]["community_code"],
                                        title: data.data.list[i]["community_name"],		 /// value 和title都是 name
                                    })
                                    var k = [];
                                    var community = [];
                                    k.push(data.data.list[i]["community_name"]);
                                    community.push(data.data.list[i]["community_code"])
                                    // y.push(data.data.list[i]["community_code"]);
                                    // // console.log(k[0]);
                                    // // console.log(y[0]);
                                    var cityNameList=k[0];
                                    var communityCode = community[0];
                                    // console.log(communityCode);
                                    // // console.log(currentSelectedCommunityCode);
                                    cityList +=
                                        '<li>'+
                                        '<div class="weui-cells weui-cells_radio">'+
                                        '<label class="weui-cell weui-check__label" for="'+ i +'">'+
                                        '<div class="weui-cell__bd">'+
                                        '<p data-communitycode="'+communityCode+'" id="cityName'+i+'">'+cityNameList+'</p>'+
                                        '</div>'+
                                        '<div class="weui-cell__ft">'+
                                        '<input type="radio" class="weui-check" name="radio2" id="'+ i +'">'+
                                        '<span class="weui-icon-checked">'+'</span>'+
                                        '</div>'+
                                        '</label>'+
                                        '</div>'+
                                        '</li>';
                                }
                                $("#cityNameList").html(cityList);
                                // 遍历结束
                                // renderBuildingSelect("communityInput", data.data.list, getSelectedBuilding);
                            }
                        },
                        complete: function (res){
                            // console.log(res)
                            //可以拿到请求响应结构  包含错误码  error success的集合
                            $.hideLoading();
                        },
                        error:function(e){
                            alert("发送失败");
                        }
                    })
                });
            }
            //初始化只是绑定keyup方法
            getCommunityNumber();
            // 将搜索结果列表中选中的值  传入楼盘名输入框中
            function pushValue(selectedCityId,selectedBuildingCode){
                $("#cityNameList").on("click","li",function(e) {

                    var loupan_name = $(this).find("p").text();
                    $("#submit_order_building").val(loupan_name);
                    $("#communityInput").val('');
                    $("#cityNameList").html('');

                    currentSelectedCommunityCode = $(this).find("p").data("communitycode");
                    $("input[name ='community_code']").val(currentSelectedCommunityCode);
                    $("input[name ='building_code']").val('');
                    $("input[name ='house_code']").val('');
                    delIpt();
                    // console.log(currentSelectedCommunityCode);            //  能够拿到当前选择的社区code


                    $("#submit_order_building").on("change",function(){
                        var community = $("#submit_order_building").data("values");
                        $("input[name ='community_code']").val(community);
                    })
                    //当要改变楼盘名时下面的输入框清空
                    $("#submit_order_building").on("click",function(){
                        if($(this).val()!==""){
                            $("#submit_order_term,#submit_order_room").val("");
                            $("#submit_order_term"+FieldCount,"#submit_order_room"+FieldCount).val("");
                            $("#communityInput").focus();

                        }
                    })

                    $.closePopup();
                    getBuildingData();
                    return false;
                });
            }
            pushValue();
            //楼盘请求接口............................................................结束
            //座栋请求接口.................................................................开始
            function getBuildingData(selectedCityId,selectedCommunityCode,selectedBuildingCode,count){
                // console.log(FieldCount);
                // console.log(currentSelectedCityId);
                // console.log(currentSelectedCommunityCode);
                $.ajax({
                    url: '{{url('/api/getBuilding')}}',
                    data: { city_id: currentSelectedCityId, community_code: currentSelectedCommunityCode,page:1 },
                    datatype: "json",
                    type:"post",
                    // async: false,
                    beforeSend:function(data){
                        if(data.readyState == 0){
                            $.showLoading("数据加载中");
                        }
                    },
                    success: function (data) {
                        // console.log(data.data.list);
                        var a = [];
                        var json = data.data.list;
                        if (data && data.data && data.data.list && data.data.list.length) {
                            for (i = 0; i < json.length; i++){
                                a.push({ title: json[i].building_name, value: json[i].building_code});
                            }
                            // console.log(a);
                            $("#submit_order_term").select("update",{
                                title: "选择期数座栋",
                                multi: false,
                                items: a,
                                onChange:function(a,selectedBuildingCode){
                                    // console.log(a.values)
                                    currentSelectedBuildingCode = a.values;
                                    // console.log(currentSelectedBuildingCode);
                                    $("#submit_order_term").on("change", function () {
                                        var building = $("#submit_order_term").data("values");
                                        $("input[name ='building_code']").val(building);
                                        $("input[name ='house_code']").val('');
                                        $("#item_box_number").val(0);
                                    })
                                    //当要改变楼盘名时下面的输入框清空
                                    $("#submit_order_term").on("click",function(){
                                        if($(this).val()!==""){
                                            $("#submit_order_room").val("");

                                        }
                                    })
                                }
                            });

                            //......count...
                            $("#submit_order_term"+FieldCount).select("update",{
                                title: "选择期数座栋",
                                multi: false,
                                items: a,
                                onChange:function(a,selectedBuildingCode){
                                    // console.log(a.values)
                                    currentSelectedBuildingCode = a.values;
                                    // console.log(currentSelectedBuildingCode);
                                    $("#submit_order_term"+FieldCount).on("change", function () {
                                        var building = $("#submit_order_term"+FieldCount).data("values");
                                        $("input[name ='building_code"+FieldCount+"']").val(building);
                                    $("input[name ='house_code"+FieldCount+"']").val('');
                                    $("#item_box_number").val(FieldCount);
									                                    })
                                    //当要改变楼盘名时下面的输入框清空
                                    $("#submit_order_term"+FieldCount).on("click",function(){
                                        if($(this).val()!==""){
                                            $("#submit_order_room"+FieldCount).val("");

                                        }
                                    })
                                }
                            });
                        }
                    },
                    complete: function (res){
                        // console.log(res)
                        //可以拿到请求响应结构  包含错误码  error success的集合
                        $.hideLoading();
                    },
                    error: function (err) {
                        alert(err)
                    }
                })
                // })
            }
            // getBuildingData();
            //座栋请求接口............................................................结束
            //单元房号请求接口............................................................开始
            function getHouseNumber(selectedCityId,selectedCommunityCode,selectedBuildingCode){
                // // console.log(currentSelectedCityId);
                //    // console.log(currentSelectedCommunityCode);
                //    // console.log(currentSelectedBuildingCode);
                // $('input').focus(function(){
                // 	$('body').height($('body')[0].clientHeight);
                // })
                // 键盘事件 取keyword值
                $("#houseInput").bind('input propertychange', function(){
                    $('body').height($('body')[0].clientHeight);
                    var keywords = $(this).val();
                    // console.log(keywords);
                    keywords.replace(/ /g, "");
                    if(!keywords){return}
                    var j = {
                        page: 1,
                        city_id: currentSelectedCityId,
                        community_code: currentSelectedCommunityCode,           //44190010
                        building_code: currentSelectedBuildingCode,                  //441900004201002000001,
                        house_name : keywords
                    };
                    $.ajax({
                        type:'POST',
                        data:j,
                        url:'{{url('/api/getHouse')}}',
                        beforeSend:function(data){
                            if(data.readyState == 0){
                                $.showLoading("数据加载中");
                            }
                        },
                        success:function(data){
                            // console.log(data);
                            if (data && data.data && data.data.list && data.data.list.length) {
                                var cityList = '';
                                for (var i = 0; i < data.data.list.length; i++) {
                                    $.extend(data.data.list[i], {
                                        value: data.data.list[i]["unit"],
                                        title: data.data.list[i]["house_name"],		 /// value 和title都是 name
                                    })
                                    var k = [];
                                    var houseName = [];
                                    var houseCode = [];
                                    k.push(data.data.list[i]["house_name"]);
                                    houseName.push(data.data.list[i]["unit"]);
                                    houseCode.push(data.data.list[i]["house_code"])
                                    // // console.log(k[0]);
                                    var cityNameList=k[0];
                                    var unitList = houseName[0];
                                    var houseCodeList = houseCode[0];
                                    // console.log(cityNameList)
                                    cityList +=
                                        '<li>'+
                                        '<div class="weui-cells weui-cells_radio">'+
                                        '<label class="weui-cell weui-check__label" for="'+ i +'">'+
                                        '<div class="weui-cell__bd">'+
                                        '<p data-housename="'+cityNameList+'" data-housecode="'+houseCodeList+'" data-unit="'+unitList+'" id="cityName'+i+'">'+unitList+"单元"+cityNameList+"房间"+'</p>'+
                                        '</div>'+
                                        '<div class="weui-cell__ft">'+
                                        '<input type="radio" class="weui-check" name="radio2" id="'+ i +'">'+
                                        '<span class="weui-icon-checked">'+'</span>'+
                                        '</div>'+
                                        '</label>'+
                                        '</div>'+
                                        '</li>';
                                }
                                $("#houseNameList").html(cityList);
                                // 遍历结束
                                // renderBuildingSelect("houseInput", data.data.list, getSelectedHouse);
                            }
                        },
                        complete: function (res){
                            // console.log(res)
                            //可以拿到请求响应结构  包含错误码  error success的集合
                            $.hideLoading();
                        },
                        error:function(e){
                            alert("发送失败");
                        }
                    })

                });
            }
            //初始化只是绑定keyup方法
            getHouseNumber();
            // 将搜索结果列表中选中的值  传入楼盘名输入框中
           		function pushHouse(){
			$("#houseNameList").on("click","li",function(e) {
				var house_item_num = $("#item_box_number").val();
                var loupan_name = $(this).find("p").text();

				if(house_item_num != 0){
					$("#submit_order_room"+house_item_num).val(loupan_name);
                    var houseName = $(this).find("p").data("housename");
                    $("input[name ='house_name"+house_item_num+"']").val(houseName);
                    var houseCode = $(this).find("p").data("housecode");
                    $("input[name ='house_code"+house_item_num+"']").val(houseCode);
                    var unit = $(this).find("p").data("unit");
                    $("input[name ='unit"+house_item_num+"']").val(unit);
                }else{
					$("#submit_order_room").val(loupan_name);
                    var houseName = $(this).find("p").data("housename");
                    $("input[name ='house_name']").val(houseName);
                    var houseCode = $(this).find("p").data("housecode");
                    $("input[name ='house_code']").val(houseCode);
                    var unit = $(this).find("p").data("unit");
                    $("input[name ='unit']").val(unit);
				}

                $.closePopup();
                $("#houseNameList").html('');
                $("#houseInput").val('');

                return false;
            });
		}
            pushHouse();
            //单元房号请求接口............................................................结束
            // 约看时间 组件..........................................................开始
            function getDate(){
                $("#datetime-picker").datetimePicker();
            }
            //getDate();
            // 约看时间 组件..........................................................结束
            //
            // 增减输入框功能............................开始
            function addIpt(count){
                // $(document).ready(function() {
                var MaxInputs       = 20; //maximum input boxes allowed
                var InputsWrapper   = $("#InputsWrapper"); //Input boxes wrapper ID
                var AddButton       = $("#AddMoreFileBox"); //Add button ID
                var x = InputsWrapper.length; //initlal text box count
                FieldCount=1; //to keep track of text box added
                $("#AddButton").click(function(e){ //on add input button click
                    if(x <= MaxInputs){ //max input box allowed
                        FieldCount++; //text box added increment     // add input box
                        var addIpt = '';
                        addIpt +=
                            '<div id="InputsWrapperBox" class="linshi-item" >'+
                            '<input id="item_number" type="hidden" value="'+FieldCount+'">'+
                            '<div class="weui-cell weui_cell_select weui_select_after bg_color_building card_title">'+
                            '<p>约看房间'+FieldCount+'</p>'+
                            '<img class="card_del" src="{{url('/image/close-circle.png')}}" alt="">'+
                            '</div>'+
                            '<div class="weui-cell weui_cell_select weui_select_after bg_color_building">'+
                            '<div class="weui-cell__hd">'+
                            '<label class="weui-label">期数座栋</label>'+
                            '</div>'+
                            '<input name="building_code'+FieldCount+'" type="hidden">'+
                            '<div class="weui-cell__bd weui_cell_primary">'+
                            '<input class="weui-input" id="submit_order_term'+FieldCount+'"  name="building_name'+FieldCount+'" type="text" placeholder="请选择期数座栋"/>'+
                            '</div>'+

                            '</div>'+
                            '<div class="weui-cell weui_cell_select weui_select_after bg_color_unit">'+
                            '<div class="weui-cell__hd">'+
                            '<label class="weui-label">单元房号</label>'+
                            '</div>'+
                            '<input name="unit'+FieldCount+'" type="hidden">'+
                            '<input name="house_code'+FieldCount+'" type="hidden">'+
                            '<input name="house_name'+FieldCount+'" type="hidden">'+
                            '<div class="weui-cell__bd weui_cell_primary">'+
                            '<a href="javascript:" class="open-popup" data-target="#unit">'+
                            '<input class="weui-input submit_order_room" id="submit_order_room'+FieldCount+'" type="text" placeholder="请选择单元房号"  data-item_num="'+FieldCount+'" />'+
                            '</a>'+
                            '</div>'+
                            '</div>'+
                            '</div>';
                        $("#InputsWrapper").append(addIpt);
                        x++; //text box increment
                        // console.log(FieldCount)
                        // console.log(x)
                        getBuildingData();
                        pushValue();
                        refreshTitle();
                    }
                    return false;
                });
                $("#InputsWrapper").on("click",".card_del", function(e){ //user click on remove text
                    if(x>1){
                        //remove text box
                        $(this).parents("#InputsWrapperBox").remove();
                        refreshTitle();
                        x--; //decrement textbox
                        // console.log(x)
                    }
                    return false;
                });
				$("#InputsWrapper").on("click", ".submit_order_room", function(e){
					$("#item_box_number").val($(this).data("item_num"));
				});
                // });
            }
            addIpt();

			function delIpt() {
                $('.linshi-item').each(function () {
                    $(this).remove();
                });
            }

            //提交表单前拼接house_list
            $("#submitFormData").on("click", function(){
                var arr = [];
                $("input[id^=submit_order_term]").each(function(i){
                    var obj = new Object();
                    var building_div = $(this).parent().parent();
                    var room_div = $(building_div).next();

                    obj.building_code = $(building_div).find("input[name^=building_code]").val();
                    obj.building_name = $(building_div).find("input[name^=building_name]").val();
                    obj.unit = $(room_div).find("input[name^=unit]").val();
                    obj.house_code = $(room_div).find("input[name^=house_code]").val();
                    obj.house_name = $(room_div).find("input[name^=house_name]").val();

                    if (obj.building_code && obj.building_name && obj.unit && obj.house_code && obj.house_name){
                        arr[i] = obj;
                    }
                });
                if (arr.length > 0){
                    var house_info = JSON.stringify(arr);
                    $("input[name=house_list]").val(urlencode(house_info));
                    $("form#mainForm").submit();
                    return false;
                }
            });

            function urlencode (str) {
                str = (str + '').toString();
                return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
            }

            // 刷新房间title
            function refreshTitle()
            {
                $('.card_title').each(function(i, item){
                    $(item).find('p').text('约看房间' + ++i);
                });
            }

            $('#submit_order_building').click(function () {
                $('body').scrollTop(0);
                $('.weui-input').select("close");
            });

            $('.submit_order_room').click(function () {
                $('body').scrollTop(0);
                $('.weui-input').select("close");
            });

            $("#datetime-picker").datetimePicker({
                min: "{{date('Y-m-d')}}",
                max: "{{date('Y-m-d', strtotime('+1 month'))}}"
            });

        })();
    </script>
@endsection