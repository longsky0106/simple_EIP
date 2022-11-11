var bUseTempSql;
var check_pct_sql_temp;

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}
function removeSelect(){
	if (window.getSelection) {
		if (window.getSelection().empty) {  // Chrome
			window.getSelection().empty();
		} else if (window.getSelection().removeAllRanges) {  // Firefox
			window.getSelection().removeAllRanges();
		}
	} else if (document.selection) {  // IE?
		document.selection.empty();
	}
}
var prod_data_first_text = '';
var prod_data_categories = '';
var prod_data_select_value = '';
var bGetProductType = false;
bUseTempSql = false;
check_pct_sql_temp = false;

$(document).ready(function(){
	// 路徑檢查
	root_path = window.location.pathname;
	root_path = root_path.indexOf("php");
	//alert(root_path);
	if(root_path < 0){
		root_path = window.location.pathname + "/system/";
	}else{
		root_path = "";
	}

	// ----↓銷售頁面-按鈕複製功能↓----
	var clipboard = new ClipboardJS('.btn');
	clipboard.on('success', function(e) {
		console.info('Action:', e.action);
		console.info('Text:', e.text);
		console.info('Trigger:', e.trigger);
		e.clearSelection();
		$('#copy_statu').css("display","flex");
		$("#copy_statu").html("複製成功！")
		setTimeout(function() {
		 $('#copy_statu').fadeOut();
		 $('#copy_statu').val('')
		}, 500 );
	});
	
	clipboard.on('error', function(e) {
		console.error('Action:', e.action);
		console.error('Trigger:', e.trigger);
		$('#copy_statu').css("display","flex");
		$("#copy_statu").html("複製失敗:(")
		setTimeout(function() {
		 $('#copy_statu').fadeOut();
		 $('#copy_statu').val('')
		}, 500 );
	});
	// ----↑銷售頁面-按鈕複製功能↑----

	mainWidth0 = $('#main_content').width();
		
	// 鍵盤Enter鍵事件
	$(function() {
		$("form input[id=SK_search]").keypress(function (e) {
			if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
				$("form input[id=SK_search_btn]").click();
				//submit1();
				return false;
			} else {
				return true;
			}
		});
	});

	$(window).resize(function() {
		bom_width();
		//mainWidth = $('#main_content').width();
		//$('#main_content').css("max-width",mainWidth);
		if ($(window).width() > 704 ) {
			if($('[id^=show_SPEC_S]').is(':visible') ) {
			}
			$('[id^=show_SPEC_S]').css("display","none");
		}
		else {
			if($('[id^=SK_SPEC]').is(':visible') ) {
			}
			$('[id^=SK_SPEC]').css("display","none");
		}
		
	});
	
	
	/* 使用臨時資料庫 */
	$('#check_pct_sql_temp').change(function() {
		if(this.checked) {
			//bUseTempSql = true;
			//alert('check_pct_sql_temp_change = true');
		}else{
			//check_pct_sql_temp = false;
			//bUseTempSql = false;
			//alert('check_pct_sql_temp_change = false');
		}
		
	});

	/* 產品分類一 下拉選單異動 */
	$(document).on('change', '#categories', function(event){
		if(event.isDefaultPrevented()) return; // 防止重複關聯事件
		event.preventDefault(); // 防止重複關聯事件
		shop_menu1_id = $('#categories :selected').val();
		if(shop_menu1_id != 0){
			$.post(root_path + "../system/get_shop_menu_level2.php", {
				shop_menu1_id: shop_menu1_id
			}, function(result){
				$("#ProdType").html(result);
				if(bGetProductType == true){
					prod_data_select_value = $('#prod_data option:contains('+ prod_data_categories[1] +')').val();
					$('#ProdType').val(prod_data_select_value).change();
					bGetProductType = false;
				}
			});
		}else if(shop_menu1_id == 0){
			$('#ProdType').val(0).change();
		}
	});

	/* 產品分類二 下拉選單異動 */
	$(document).on('change', '#ProdType', function(event){
		if(event.isDefaultPrevented()) return; // 防止重複關聯事件
		event.preventDefault(); // 防止重複關聯事件
		
		// 取得規格索引值
		shop_menu2_id = $('#ProdType :selected').val();
		//var SK_NO = $("#SK_search").val();
	   
		// 取得目前上方查詢結果的料號
		var SK_NO = $("#sk_no1").text();

		if(shop_menu2_id != 0){
			// 送出AJAX資料到後端來取得規格項目
			$.post(root_path + "../system/get_spec_item_from_menu.php", {
				shop_menu2_id: shop_menu2_id,
					SK_NO: SK_NO
					, check_pct_sql_temp: bUseTempSql
			}, function(result){
				$("#spec_edit").html(result);
				$("#spec_content_title").html("<span><b>規格<b style=\"color:blue;\"> ( " + SK_NO + " )</b></b></span>");
				
				if(typeof window_width_check === "function"){
					window_width_check();
				}
				
			});
		}
	});
	if($("#SK_create").length){
		$("#SK_create").focusout(function(){
			var Model = $("#SK_create").val();
			search_base_data(Model);
		});
	}
});


/* 查詢前檢查是否輸入 */
function submit1(){
		// if(lys.SK_NO.value == "") 
		if(lys.Model.value == "") 
		{
			var statu_check_text = ($("#statu_check").text()!='')?$("#statu_check").html():'&emsp;';
			$("#statu_check").html( 
				"<span style=\"color:red;\"><b>客官...沒有填資料...小的不知道您要找什麼</b></span>" 
			).delay(1500).fadeOut(1000, function() { //1.5秒後淡出訊息(隱藏)
				$(this).html(statu_check_text).show(); //將訊息替換成原先訊息
			});
			return false;
		}
		else {
			lys.Model.value = lys.Model.value.trim();
			submit_data();
			$("form input[id=SK_search]").blur(); 
			$("form input[id=SK_search_btn]").prop('disabled', true);
		}
}

/* 送出查詢 */
function submit_data(Model, user_action){

	// 路徑檢查
	root_path = window.location.pathname;
	root_path = root_path.indexOf("php");
	
	if(root_path < 0){
		root_path = window.location.pathname + "/system/";
	}else{
		root_path = "";
	}
	
	if (typeof(user_action) === 'undefined') {
		var user_action = "";
	}
	
	if(user_action=="create"){
		Model = "";
	}else{
		if (typeof(Model) === 'undefined') {
			var Model = $("#SK_search").val();
		}
	}
	
	$("#statu_check").html( "<span style=\"color:blue;\">查詢中...請稍後</span>" );
	
	$.post(root_path + "../system/show_data.php", {
		Model: Model
		, action: user_action
	}, function(result){
		$("#show_data").html(result);
		$("#statu_check").html( "<span style=\"color:blue;\">※ "+ Model + " 查詢結果如下※</span>" );
		
		if(typeof window_width_check === "function"){
			window_width_check();
		}
		
		if(user_action=="create"){
			// 載入產品分類選項
			$("#prod_data").load(root_path + "../system/prod_edit.php #categories, #ProdType");
			
			// 載入規格輸入欄位
			$("#spec_edit").load(root_path + "../include/spec_edit.php");
			
			// 載入產品描述&特色
			//$("#description_features_edit").load(root_path + "../system/get_description_feature.php");
			$.post(root_path + "../system/get_description_feature.php", {
				SK_NO: ''
				, Model: ''
				, check_pct_sql_temp: ''
				, action: user_action
			}, function(result){
				$("#description_features_edit").html(result);
			});	
			
		}else{
			// ------------如果有銷售/料號資料------------
			if ($("#sk_no1").text()){
				if ($("#sk_no1").text().indexOf("_temp") < 0){
					//alert("0");
					bUseTempSql = false;
				}else{
					//alert("1");
					bUseTempSql = true;
				}
				
				
				// ------------產品分類------------
				$("#prod_data").html( "<span style=\"color:blue;\">查詢中...請稍後</span>" );
				var SK_NO = $("#sk_no1").text();
				$.post(root_path + "../system/prod_edit.php", {
					SK_NO: SK_NO
					, check_pct_sql_temp: bUseTempSql
				}, function(resultP){
					$("#prod_data").html(resultP);
					if (resultP){
						prod_data_first_text = $("#prod_data").text().slice(0, $("#prod_data").text().indexOf("\n"));
						prod_data_categories = prod_data_first_text.split(" > ");
						if ($('#prod_data option:contains('+ prod_data_categories[0].replace('目前產品分類: ','') +')').length) 
						{
						   prod_data_select_value = $('#prod_data option:contains('+ prod_data_categories[0].replace('目前產品分類: ','') +')').val();
						   bGetProductType = true;
						   $('#categories').val(prod_data_select_value).change();
						}
						
						// ------------規格------------
						$("#spec_edit").load(root_path + "../include/spec_edit.php");
						$("#spec_content_title").html("<h4>規格(請先設定產品分類才能顯示完整規格列表)</h4>");
						
						// ------------產品描述&特色------------
						$("#description_features_edit").html( "<span style=\"color:blue;\">查詢中...請稍後</span>" );
						var SK_NO = $("#sk_no1").text();
						//var Model = $("#SK_search").val();
						$.post(root_path + "../system/get_description_feature.php", {
							SK_NO: SK_NO
							, Model: Model
							, check_pct_sql_temp: bUseTempSql
						}, function(result){
							$("#description_features_edit").html(result);
						});	
					}else{
						//alert(0);					
					}
				});
			}else{
				// 所有料號皆未填寫！
				$("#prod_data").html("沒有料號可進行查詢！");
				// $('#categories').val(0).change();
				$("#spec_edit").html("沒有料號可進行查詢！");
				$("#spec_content_title").html("<span><b>規格</b></span><br>");
				
				$('textarea').val('');			
				$("#update_PCT").prop('disabled', true);			
				$("#update_PCT_description").text("連同更新官網產品資料");
			}			
		}

		setTimeout(function(){
			$("form input[id=SK_search_btn]").prop('disabled', false);
		}, 500);
	});
};


// 顯示/隱藏規格
function show_SPEC(obj,n){
	$('#show_SPEC_S'+n).slideToggle(100,
		function() {
			if($('#show_SPEC_S'+n).is(':visible')) {
				$(obj).text("關閉");
			}
			else {
				$(obj).text("顯示");
			}
		}
	);

};

// (未使用)內崁官網資料
function pct_maker(data_id,n){
	/*$(function(){
		var contentURI= 'http://www.pct-max.com.tw/products-detail.php?index_id=72';
		$("#pro_maker_content"+n).load('grabber.php?url='+ contentURI);
		//alert('include/grabber.php?url='+ contentURI);
	});*/
	$("#pro_maker_content"+n).html("<iframe src=\"http://www.pct-max.com.tw\" />");
	$('#pro_maker'+n).slideToggle(300,"linear",
		function() {
			if($("#pro_maker"+n).is(':visible')) {
				$(obj).text("全球KVM 關閉");
			}
			else {
				$(obj).text("全球KVM");
			}
		}
	);
};

// 產生網頁介紹
function pro_maker(obj,n,templateNo){
	/*
		template1.php - 全球KVM中心
		template2.php - PChome24H
		template3.php - 純文字
		template4.php - 部落格
	*/
	
	var Model = $("#info_base_model").text(); // 標題右側文字
	var Model = Model.replace('(',''); // 移除左側括號
	var Model = Model.replace(')',''); // 移除右側括號
	var Model = $.trim(Model); // 移除所有空格
	
	
	//if(!$("#pro_maker_content"+n).text()){
		$("#pro_maker_content"+n).html("查詢中...請稍後");
		var SK_NO = $("#sk_no"+n).text();
		var template = 'template'+ templateNo + '.php';
		$.post("include/PCT_product.php", {
			Model: Model,
			SK_NO: SK_NO,
			template: template
			, check_pct_sql_temp: bUseTempSql
			}, function(result){
			$("#pro_maker_content"+n).html(result);
		});
	//}
	text_website = "";
	switch(templateNo) {
		case 1:
			text_website = "全球KVM";
			text_website2 = "標準全球KVM頁面";
			$("#btn_pro_maker_content").show();
			break;
		case 2:
			text_website = "PChome24H";
			text_website2 = "建議在PChome24H後台增加多個區塊再個別複製內容(PChome24H每個區塊只能在上方插入一個圖片)";
			$("#btn_pro_maker_content").hide();
			break;
		case 3:
			text_website = "純文字內容";
			text_website2 = "部分電商平台(如蝦皮)使用純文字格式";
			$("#btn_pro_maker_content").show();
			break;
		case 4:
			text_website = "部落格通用";
			text_website2 = "用來增加官網主要網站流量及推廣用";
			$("#btn_pro_maker_content").show();
			break;
		default:
			text_website = "全球KVM";
			text_website2 = "標準全球KVM頁面";
			$("#btn_pro_maker_content").show();
	}
	$("#pro_maker_des").text(text_website2);
	$('#pro_maker'+n).slideToggle(300,"linear",
		function() {
			if($("#pro_maker"+n).is(':visible')) {
				$(obj).text(text_website+"\n\r[×]");
				
			}
			else {
				$(obj).text(text_website);
			}
		}
	);
};




// 按下帶入範例按鈕
function spec_example_add_input(spec_item,n,lang){
	spec_item_name = spec_item.name;
	// 送出AJAX資料到後端來取得規格數值範例
	$.post(root_path + "../system/get_spec_example.php", {
		spec_item_name: spec_item_name
			, spec_item_no: n
			, spec_item_lang: lang
	}, function(result){
		if($.trim(result)){
			if(lang == 'both'){
				spec_example_result = $.trim(result).split('|');
				spec_example_result_tw = spec_example_result[0];
				spec_example_result_en = spec_example_result[1];
				
				if(spec_example_result_tw){
					$("input[name='"+ spec_item_name +"']").val(spec_example_result_tw);
				}
				
				if(spec_example_result_en){
					$("input[name='"+ spec_item_name +"_en']").val(spec_example_result_en);
				}
				
			}else{
				if(lang == 'tw'){
					$("input[name='"+ spec_item_name +"']").val($.trim(result));
				}else{
					$("input[name='"+ spec_item_name +"_en']").val($.trim(result));
				}
			}
			
		}
	});
}

// 顯示規格項目新增功能
function spec_item_add(){
	if($("#spec_item_add_content").is(':empty')){
		$("#spec_item_add_content").load("system/spec_item_add.php");
	}
	
}

// 檢查該型號的基本資料(對照)是否已存在 PCT.dbo.Data_Prod_Reference
function search_base_data(Model){
	$.post(root_path + "../system/prod_base_search.php", {
		Model: Model
	}, function(result){
		if(result.indexOf("無法重複建立！") >= 0){
			alert("此型號已經存在，無法重複建立！");
			return false;
		}
	});
}

// 新增基本資料(對照) PCT.dbo.Data_Prod_Reference
function insert_data(user_action){
	if(typeof(user_action) === "undefined"){
		user_action = "";
	}
	if($("#info_base_model").length){
		var Model = $("#info_base_model").text(); // 標題右側文字
		var Model = Model.replace('(',''); // 移除左側括號
		var Model = Model.replace(')',''); // 移除右側括號
	}else if($("#SK_create").length){
		var Model = $("#SK_create").val();
	}	
	
	var Model = $.trim(Model); // 移除所有空格
	if(!Model){
		alert("未輸入型號!");
		return false;
	}
	   
	return $.post(root_path + "../system/prod_insert.php", {
		Model: Model
	}).done(function(result){
		if(result.indexOf("新增成功！") >= 0){
			// alert("insert_data true");
			$("#statu_insert_check").html(result);
		/*	$("#statu_insert_check").html(result).delay(1200).fadeOut(1000, function() { //1.2秒後淡出返回訊息(隱藏)
				$(this).html("&emsp;").show(); //將返回訊息替換成全型空白並顯示
			});*/
			if(user_action=="create"){
				update_base_submit(user_action);
			}
		}else if(result.indexOf("無法重複建立！") >= 0){
			alert("無法重複建立！");
			$("#update_preview").empty();
		}
	}).fail(function() {
		$("#statu_insert_check").html("後端連線失敗");
		alert("後端連線失敗");
	});
	// console.log(bool);
}

// 新增臨時料號
function insert_temp_no_data(user_action){
	if(typeof(user_action) === "undefined"){
		user_action = "";
	}
	if($("#info_base_model").length){
		var Model = $("#info_base_model").text(); // 標題右側文字
		var Model = Model.replace('(',''); // 移除左側括號
		var Model = Model.replace(')',''); // 移除右側括號
		
	}else if($("#SK_create").length){
		var Model = $("#SK_create").val();
	}
	var Model = $.trim(Model); // 移除所有空格
	if(!Model){
		return false;
	}
	var SK_NO4 = $("input[name=SK_NO4]").val();
	if(user_action=="create"){
		$("input[name=SK_NO4]").val(Model + "_temp");
	}else{

		$.post(root_path + "../system/prod_temp_no_insert.php", {
			Model: Model
		}, function(result){
			//$("#statu_base_temp").html(result);
			$("#statu_base_temp").html(result).delay(1200).fadeOut(1000, function() { //1.2秒後淡出返回訊息(隱藏)
				$(this).html("&emsp;").show(); //將返回訊息替換成全型空白並顯示
			});	
			if($("#info_base_model").length){
				setTimeout(function(){
					submit1();
				}, 600);				
			}

		});
	}
}

// ------------產品基本資料------------
function update_base_submit(user_action){
	if(typeof(user_action) === "undefined"){
		user_action = "";
	}
	// alert('run update_base_submit');
	// 取得所有欄位資料
	if($("#info_base_model").length){
		var Model = $("#info_base_model").text(); // 標題右側文字
		var Model = Model.replace('(',''); // 移除左側括號
		var Model = Model.replace(')',''); // 移除右側括號
		
	}else if($("#SK_create").length){
		var Model = $("#SK_create").val();
	}
	var Model = $.trim(Model); // 移除所有空格
	if(!Model){
		alert('Model資料錯誤！');
		return false;
	}
	
	var SK_NO1 = $("input[name=SK_NO1]").val();
	var SK_NO2 = $("input[name=SK_NO2]").val();
	var SK_NO3 = $("input[name=SK_NO3]").val();
	var SK_NO4 = $("input[name=SK_NO4]").val();
	
	var base_Price = $("input[name=Price]").val();
	var base_Suggested_Price = $("input[name=Suggested_Price]").val();
	var base_Cost_Price = $("input[name=Cost_Price]").val();
	
	// 顯示處理訊息
	$("#statu_base_check").html( "<span style=\"color:blue;\">更新中...請稍後</span>" );

	// 送出AJAX資料到後端
	$.post(root_path + "../system/prod_base_update.php", {
		Model: Model
		,SK_NO1: SK_NO1
		,SK_NO2: SK_NO2
		,SK_NO3: SK_NO3
		,SK_NO4: SK_NO4
		,base_Price: base_Price
		,base_Suggested_Price: base_Suggested_Price
		,base_Cost_Price: base_Cost_Price
	}).done(function(result){
		$("#statu_base_check").html(result);
		/*$("#statu_base_check").html(result).delay(1200).fadeOut(1000, function() { //1.2秒後淡出返回訊息(隱藏)
			$(this).html("&emsp;").show(); //將返回訊息替換成全型空白並顯示
		});*/
		if(user_action=="create"){
			// alert(" 送出AJAX資料到後端");
			// 送出AJAX資料到後端
			$.post(root_path + "../system/prod_create.php", post_var, function(result){
				
				// 根據後端回應決定訊息停留時間
				if(result.indexOf("更新失敗!") < 0){
					$("#update_preview").html(result).delay(2000).fadeOut(1200, function() { //2.0秒後淡出返回訊息(隱藏)
						$(this).html("&emsp;<br>&emsp;").show(); //將返回訊息替換成全型空白並顯示
					});
					// $("#update_preview").html(result);
				}else{
					$("#update_preview").html(result);
				}
			});	
		}
		
	});
}


/* 送出資料更新 */
function update_submit(user_action){
	if(typeof(user_action) === "undefined"){
		user_action = "";
	}
	
	/* 檢查是否已選擇產品分類 */
	if($('#categories :selected').text()!='選擇產品系列'){
		var Categories_Test = $('#categories :selected').text();
		if($('#ProdType :selected').text()!='選擇產品類別'){
			var ProdType_Test = $('#ProdType :selected').text();
		}else{
			alert("未設定「產品類別」!");
			$("div h4").html(function(index, text) {
				if (text == '產品分類') {
					return text.replace('產品分類', '<b style=\"color:red;\"> ＊產品分類 ( 必填 )</b>');
				}
			});
			/* 畫面滾動至產品分類 */
			$('html, body').animate({
                    scrollTop: $("#show_data").offset().top
			}, 600);
			return false;
		}
	}else{
		alert("未設定「產品分類」!");		
		$("div h4").html(function(index, text) {
			if (text == '產品分類') {
				return text.replace('產品分類', '<b style=\"color:red;\"> ＊產品分類 ( 必填 )</b>');
			}
		});
		/* 畫面滾動至產品分類 */
		$('html, body').animate({
                    scrollTop: $("#show_data").offset().top
         }, 600);
		return false;
	}
	
	
	//取得所有規格輸入欄位並轉成JS物件
	var SpecInputArray = $("#zh-tw_spec input, #en-us_spec input").serializeArray(), 
	SpecInputObj = {};	
    $.each(SpecInputArray, function(i, field){
		if(field.value){
			SpecInputObj[field.name] = field.value; //將JS物件排成 [key] = 'value' 陣列格式
		}
    });
	
	// 若官網有資料，取得產品索引值
	data_id = ""; // 官網產品索引值預設值(避免錯誤)
	if($('.sk_data5 a').attr('href')){
		data_id = $('.sk_data5 a').attr('href').split("=");
		data_id = data_id[1];
	}
	
	// 取得網頁及表單上的資料
	var SK_NO = $("#sk_no1").text();
	var SK_NO1 = $("input[name=SK_NO1]").val();
	var SK_NO2 = $("input[name=SK_NO2]").val();
	var SK_NO3 = $("input[name=SK_NO3]").val();
	var SK_NO4 = $("input[name=SK_NO4]").val();
	//var SK_NO = $("#SK_search").val();
	var TW_description = $("textarea[name=zh-tw_description]").val();
	var EN_description = $("textarea[name=en-us_description]").val();
	var TW_features = $("textarea[name=zh-tw_features]").val();
	var EN_features = $("textarea[name=en-us_features]").val();
	
	//將網頁及表單上的其他資料加入JS物件
	SpecInputObj["data_id"] = data_id; // 官網產品索引值
	SpecInputObj["SK_NO"] = SK_NO; // 料號
	SpecInputObj["SK_NO1"] = SK_NO1; // 料號1
	SpecInputObj["SK_NO2"] = SK_NO2; // 料號2
	SpecInputObj["SK_NO3"] = SK_NO3; // 料號3
	SpecInputObj["SK_NO4"] = SK_NO4; // 料號4
	SpecInputObj["SK_Categories"] = Categories_Test; //產品類別一
	SpecInputObj["SK_ProdType"] = ProdType_Test; //產品類別二
	SpecInputObj["SK_description_tw"] = TW_description; //中文描述
	SpecInputObj["SK_description_en"] = EN_description; //英文描述
	SpecInputObj["SK_features_tw"] = TW_features; //中文特色
	SpecInputObj["SK_features_en"] = EN_features; //英文特色
	SpecInputObj["check_pct_sql_temp"] = bUseTempSql;
	
	SpecInputObj["action"] = user_action;
	
	// 勾選了更新官網資料或新增官網資料
	if ($('#update_PCT').is(':checked')) {
		SpecInputObj["pct_web_update"] = 'Checked';
		//alert('pct_web_update');
	}
	
	// 將整個JS物件轉換為字串
	data = JSON.stringify(SpecInputObj);
	
	// 設定AJAX傳送資料
	post_var = {'action': 'process', 'data': data };
	
	// 顯示處理訊息
	$("#update_preview").html( "<span style=\"color:blue;\">請稍後...</span><br>&emsp;" );
	
	if(user_action=="create"){

		var Model = $("#SK_create").val();
		var Model = $.trim(Model); // 移除所有空格		
		if(!Model){
			alert("未輸入型號!");
			return false;
		}

		var SK_NO1 = $("input[name=SK_NO1]").val();
		var SK_NO2 = $("input[name=SK_NO2]").val();
		var SK_NO3 = $("input[name=SK_NO3]").val();
		var SK_NO4 = $("input[name=SK_NO4]").val();	
			
		if((SK_NO1 + SK_NO2 + SK_NO3 + SK_NO4).length == 0){
			alert("請至少填寫一個料號");
			return false;
		}else if(SK_NO4){
			// 新增臨時料號
			insert_temp_no_data();
		}else{
			
		}
		// 建立基本資料(對照)與後續作業
		insert_data(user_action);

	}else{
		// 送出AJAX資料到後端
		$.post(root_path + "../system/prod_update.php", post_var, function(result){
			
			// 根據後端回應決定訊息停留時間
			if(result.indexOf("更新失敗!") < 0){
				$("#update_preview").html(result).delay(2000).fadeOut(1200, function() { //2.0秒後淡出返回訊息(隱藏)
					$(this).html("&emsp;<br>&emsp;").show(); //將返回訊息替換成全型空白並顯示
				});
				//$("#update_preview").html(result);
			}else{
				$("#update_preview").html(result);
			}
		});	
	}
}