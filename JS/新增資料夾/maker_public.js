$(document).ready(function(){
	var clipboard = new Clipboard('.btn');

	clipboard.on('success', function(e) {
		
		console.info('Action:', e.action);
		console.info('Text:', e.text);
		console.info('Trigger:', e.trigger);

		e.clearSelection();
		$('#copy_statu').css("display","block");
		$("#copy_statu").html("複製成功！")
		setTimeout(function() {
		 $('#copy_statu').fadeOut();
		 $('#copy_statu').val('')
		}, 800 );
	});

	clipboard.on('error', function(e) {
		console.error('Action:', e.action);
		console.error('Trigger:', e.trigger);
		$('#copy_statu').css("display","block");
		$("#copy_statu").html("複製失敗:(")
		setTimeout(function() {
		 $('#copy_statu').fadeOut();
		 $('#copy_statu').val('')
		}, 800 );
	});
	
	/*setTimeout(function() {
		 $('#copy_statu').fadeOut();
		 $('#copy_statu').val('')
	}, 3000 );*/
	//new Clipboard('.btn');
	//clipboard.destroy();
	/*document.getElementById("copyButton").addEventListener("click", function() {
		copyToClipboard(document.getElementById("statu_check"));
	});*/
	
	mainWidth0 = $('#main_content').width();
	$(document).on('click', "[id^='show_NOWQTY']", function() {
		var show_NOWQTY_Id = $(this).attr("id");
		var arr = show_NOWQTY_Id.split('show_NOWQTY');
		show_qty(this,arr[1]);
	});
	/*$(document).on('click', '.cb_', function() {
		var cb_Id = $(this).attr("id");
		cb_Id.attr(div).prop('checked', true);
		alert("1");
	});*/
	if($("#cb_PP").is(':checked')) {
			$('input[id="SF1"]').val("PP");
		}
		else{
			$('input[id="SF1"]').val("");
	}
	$("#cb_PP").change(function() {
		if(this.checked) {
			$('input[id="SF1"]').val("PP");
		}
		else{
			$('input[id="SF1"]').val("");
		}
	});
	$("#cb_PN").change(function() {
		if(this.checked) {
			$('input[id="SF2"]').val("PN");
		}
		else{
			$('input[id="SF2"]').val("");
		}
	});
	$("#cb_S").change(function() {
		if(this.checked) {
			$('input[id="SF3"]').val("S");
		}
		else{
			$('input[id="SF3"]').val("");
		}
	});
	$("#cb_PCB").change(function() {
		if(this.checked) {
			$('input[id="SF4"]').val("PCB");
		}
		else{
			$('input[id="SF4"]').val("");
		}
	});
	$("#cb_IC").change(function() {
		if(this.checked) {
			$('input[id="SF5"]').val("IC");
		}
		else{
			$('input[id="SF5"]').val("");
		}
	});
	
	
	// 鍵盤Enter鍵事件
	$(function() {
		$("form input").keypress(function (e) {
			if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
				$('input[type=button]').click();
				return false;
			} else {
				return true;
			}
		});
	});

	$(window).resize(function() {
		//mainWidth = $('#main_content').width();
		$('#main_content').css("max-width",mainWidth);
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
});

function show_SPEC(obj,n){
	var layer;
	eval(layer='SK_SPEC' + n);
	if ($(window).width() > 704 ) {
		$('#SK_SPEC'+n).slideToggle(100,
			function() {
				if($("#SK_SPEC"+n).is(':visible')) {
					$(obj).text("關閉");
				}
				else {
					$(obj).text("顯示");
				}
			}
		);
	}
	else {
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
	}
	
};

function check(){
		if(lys.SK_NAME.value == "") 
		{
			$("#statu_check").html( "<span style=\"color:red;\"><b>請填寫資料再送出!</b></span>" );
			return false;
		}
		else {
			lys.SK_NAME.value = lys.SK_NAME.value.trim();
			submit_data();
			$('input[type=Text]').blur(); 
		}
 }

function submit_data(){
	$("#statu_check").html("查詢中...請稍後");
	var txt1 = $("#SK_search").val();
	$.post("template3.php", {
		SK_NAME: txt1
		//, SK_filter: txt2
		}, function(result){
		$("#statu_check").html(result);
	});
};
function show_BOM(obj,n){
	var mainWidth = $('#main_content').width();
	//$('#main_content').css("max-width",mainWidth);
	if(!$("#BOM"+n).text()){
		$("#BOM"+n).html("查詢中...請稍後");
		var txt = $("#sk_no"+n).text();
		$.post("show_bom.php", {BD_USKNO: txt}, function(result){
			$("#BOM"+n).html(result);
		});
	}
	
	$('#BOM'+n).slideToggle(300,"linear",
		function() {
			if($("#BOM"+n).is(':visible')) {
				//$('#main_content').css("max-width",mainWidth);
				$(obj).text("關閉");
				
			}
			else {
				$(obj).text("展開");
				//$('#main_content').css("max-width","");
				if($("[id^='BOM']").is(':visible')) {
				}
				else {
					//$('#main_content').css("max-width","");
				}
			}	
		// Animation complete.
		}
	);
	//$('#main_content').css("max-width",mainWidth);
	/*if($("[id^='BOM']").is(':visible')) {
	}
	else {
		$('#main_content').css("max-width","");
	}*/
};
function show_qty(obj,n){
	if(!$("#show_qty"+n).text()){
		$("#show_qty"+n).html("查詢中...請稍後");
		var txt = $("#sk_no"+n).text();
		$.post("show_qty.php", {WD_SKNO: txt}, function(result){
			$("#show_qty"+n).html(result);
		});
	}
	$('#show_qty'+n).slideToggle(100);
};

function show_use(obj,n){
	if(!$("#USE"+n).text()){
		$("#USE"+n).html("查詢中...請稍後");
		var txt = $("#sk_no"+n).text();
		$.post("show_use.php", {BD_USKNO: txt}, function(result){
			$("#USE"+n).html(result);
		});
	}
	
	$('#USE'+n).slideToggle(300,"linear",
		function() {
			if($("#USE"+n).is(':visible')) {
				//$('#main_content').css("max-width",mainWidth);
				$(obj).text("關閉");
				
			}
			else {
				$(obj).text("展開");
				//$('#main_content').css("max-width","");
				if($("[id^='USE']").is(':visible')) {
				}
				else {
					//$('#main_content').css("max-width","");
				}
			}	
		// Animation complete.
		}
	);
	
};

function copyToClipboard(elem) {
	  // create hidden text element, if it doesn't already exist
    var targetId = "_hiddenCopyText_";
    var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
    var origSelectionStart, origSelectionEnd;
    if (isInput) {
        // can just use the original source element for the selection and copy
        target = elem;
        origSelectionStart = elem.selectionStart;
        origSelectionEnd = elem.selectionEnd;
    } else {
        // must use a temporary form element for the selection and copy
        target = document.getElementById(targetId);
        if (!target) {
            var target = document.createElement("textarea");
            target.style.position = "absolute";
            target.style.left = "-9999px";
            target.style.top = "0";
            target.id = targetId;
            document.body.appendChild(target);
        }
        target.textContent = elem.textContent;
    }
    // select the content
    var currentFocus = document.activeElement;
    target.focus();
    target.setSelectionRange(0, target.value.length);
    
    // copy the selection
    var succeed;
    try {
    	  succeed = document.execCommand("copy");
    } catch(e) {
        succeed = false;
    }
    // restore original focus
    if (currentFocus && typeof currentFocus.focus === "function") {
        currentFocus.focus();
    }
    
    if (isInput) {
        // restore prior selection
        elem.setSelectionRange(origSelectionStart, origSelectionEnd);
    } else {
        // clear temporary content
        target.textContent = "";
    }
    return succeed;
}