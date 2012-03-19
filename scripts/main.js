var max_top_column = 8;

window.sum_caption = 2;
window.sum_caption_active = 2;
window.sum_x_axis = 2;
function set_plus_cap(){
	$(".tambah.top").css({"margin-top":-(($(".basket.top").height()+$(".tambah.top").height()+6)),"margin-left":63+(56*sum_caption_active)});
}
function set_plus_x(){
	$(".tambah.bottom").css({"margin-top":3,"margin-left":-($(".tambah.bottom").width()+9)});
}
function set_remove_cap(){
	$(".basket.top .right .remove").each(function(ind){
		var offset = $("#"+($(this).attr("rel"))).offset();
		$(this).css({
			"left":(offset.left),
			"top":(offset.top)-(($(this).height())+5)
		});
	});
}
function set_remove_x(){
	$(".basket.bottom .remove").each(function(ind){
		var offset = $("#"+($(this).attr("rel"))).offset();
		$(this).css({
			"top":(offset.top),
			"left":(offset.left)-(($(this).width())+11)
		});
	});
}
function append_x(){
	var r='';
	r+='<div class="right" rel="'+sum_x_axis+'">';
	var i;
	for(i=1;i<sum_caption+1;i++){
		r+='<div class="in"><input type="text" size="5" name="v_'+sum_x_axis+'_'+i+'" id="v_'+sum_x_axis+'_'+i+'" /></div>';
	}
	r+='</div>';
	return r;
}
function remove_cap(){
	$(".basket.top .right .remove").each(function(index){
		$(this).click(function(){
			var sum_each_cap = 0;
			$(".basket.top .right .in input").each(function(index){sum_each_cap++;});
			var i;
			for(i=0;i<sum_x_axis;i++){
				$("#v_"+(i+1)+"_"+($(this).attr("rev"))).remove();
			}
			$("#cap_"+($(this).attr("rev"))).remove();
			$(this).remove();

			sum_caption_active = sum_each_cap;
			set_remove_cap();
			set_plus_cap();
		});
	});
}
function remove_x(){
	$(".remove_place_bottom .remove").each(function(){
		$(this).click(function(){
			$(".basket.bottom .main .right[rel="+($(this).attr("rev"))+"]").remove();
			$(".basket.bottom .left .in[rel="+($(this).attr("rev"))+"]").remove();
			$(this).remove();

			set_remove_x();
			set_plus_x();
		});
	});
}
$(document).ready(function(){
	remove_cap();
	remove_x();
	set_remove_cap();
	set_plus_cap();
	set_plus_x();
	set_remove_x();

	$(".tambah.top").click(function(){
		if(sum_caption_active+1<max_top_column){
			sum_caption++;
			sum_caption_active++;
			$("#sum_caption").val(sum_caption);
			$(".basket.top .right").append('<div class="in"><input type="text" size="5" name="cap_'+sum_caption+'" id="cap_'+sum_caption+'" /></div><div class="remove" rel="cap_'+sum_caption+'" rev="'+sum_caption+'" title="Hapus kolom ini?">-</div>');
			$(".basket.bottom .right").each(function(ind){
				$(this).append('<div class="in"><input type="text" size="5" name="v_'+($(this).attr("rel"))+'_'+sum_caption+'" id="v_'+($(this).attr("rel"))+'_'+sum_caption+'" /></div>');
			});
			set_remove_cap();
			set_plus_cap();

			remove_cap();
		}
		else{
			alert("Demi kenyamanan bersama.\nData yang dapat and masukan untuk Caption/Title hanya "+max_top_column+".\nTerimakasih atas kerjasamanya!");
		}
	});
	$(".tambah.bottom").click(function(){
		sum_x_axis++;
		$("#sum_x_axis").val(sum_x_axis);
		$(".basket.bottom .left").append('<div class="in" align="center" rel="'+sum_x_axis+'"><input type="text" size="5" name="x_'+sum_x_axis+'" id="x_'+sum_x_axis+'" /></div>');
		$(".basket.bottom .remove_place_bottom").append('<div class="remove" rel="x_'+sum_x_axis+'" rev="'+sum_x_axis+'" title="Hapus baris ini?">-</div>');
		$(".basket.bottom .main").append(append_x());
		set_plus_x();
		set_remove_x();

		remove_x();
	});
});