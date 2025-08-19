<div class="user_game_module shop bag">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/bag.png" />
        <p class="name">Túi đồ</p>
    </div>
    <div class="menu">
        <div target="equipment" class="item active text_bag_equipment"></div>
        <div target="item" class="item text_bag_item"></div>
        <div target="material" class="item text_bag_material"></div>
        <div target="currency" class="item text_bag_currency"></div>
    </div>
    <div id="bag_render_content">
        <div class="child_module">
            <div class="bag_menu hide">
                <button onclick="popup_load('game/bag_fast_delete')" class="fast_delete button_style_one"><i class="fa-regular fa-trash"></i> <text class="text_bag_fast_delete"></text></button>
            </div>
            <div class="item_list_use">
                
            <div>
        </div>
    </div>
</div>
<script>
    page = 1;
    bag_data = {};
    async function content_render(type) {
        await get_server_data('game_item');
        if(!bag_data[type]){
            bag_data[type] = await get_character_list(`/api/character_list?character=${my_character}&list=${type}`);
        }
        html = '';
        $.each(bag_data[type], function(index, data) {
            var avatar = "";
            var name = "";
            var onclick = "";
            var amount = "";
            var button = "";
            if(type == "equipment" || type != "equipment" && data.amount != 0){
                if(type == "equipment"){
                    if(data.status == "free"){
                        if(data.selfLock){
                            var button = `<button class="selflock lock circle" target="${index}" status="unlock" onclick="equipment_selfLock(this)"><i class="fa-regular fa-lock"></i></button>`;
                            button += `<button class="delete hide circle" target="${index}" level="${data.level}" type="${data.type}" onclick="equipment_delete([${index}])"><i class="fa-regular fa-trash"></i></button>`;
                        }else{
                            var button = `<button class="selflock circle" target="${index}" status="lock" onclick="equipment_selfLock(this)"><i class="fa-regular fa-unlock"></i></button>`;
                            button += `<button class="delete circle" target="${index}" level="${data.level}" type="${data.type}" onclick="equipment_delete([${index}])"><i class="fa-regular fa-trash"></i></button>`;
                        }
                        var quality = quality_convert(data.quality);
                        avatar = `<div class="equipment_icon border_level_${data.level} image"><img class="avatar" src="/assets/img/level/equipment/${data.type}/${data.sign}_${data.level}.png" /></div>`;
                        name = `<div class="detail"><p class="name level_${data.level}">${json_data.language[data.type+'_'+data.sign+'_'+data.level]}</p><p class="text"><span class="level_${quality.num}">${quality.name}</span> · +${data.upgrade} </p></div>`;
                        onclick = `popup_data = {'type':'${type}','id':'${index}'};popup_load('profile/item')`;
                    }
                }else{
                    avatar = `<div class="image"><img class="avatar" src="/assets/img/level/${type}/${index}.png" /></div>`;
                    if(data.amount_lock > 0){
                        name = `<div class="detail"><p class="name">${json_data.language[type+'_'+index]}</p><p class="text">Số lượng: ${number_format(data.amount)} - Khóa: ${number_format(data.amount_lock)}</p></div>`;
                    }else{
                        name = `<div class="detail"><p class="name">${json_data.language[type+'_'+index]}</p><p class="text">Số lượng: ${number_format(data.amount)}</p></div>`;
                    }
                    onclick = `popup_data = {'type':'${type}','sign':'${index}'};popup_load('profile/item')`;
                }
                if(type == "item" && server_data.game_item[index]){
                    if(server_data.game_item[index].multi){
                        button = `<button onclick="popup_data = {'sign':'${index}','type':'item'};popup_load('game/item_use')">Dùng</button>`;
                    }else{
                        button = `<button target="${index}" onclick="item_used(this)">Dùng</button>`;
                    }
                }
                if(name != ""){
                    html += `
                        <div class="item" id="${type}_${index}">
                            <div onclick="${onclick}" class="info">
                                ${avatar}
                                ${name}
                            </div>
                            <div class="button_control">
                                ${button}
                            </div>
                        </div>
                    `;
                }
            }
        });
        $("#bag_render_content .child_module .item_list_use").html(html);
    }

    $(".user_game_module .menu .item").click(function() {
        $(".user_game_module .menu .item").removeClass('active');
        $(this).addClass('active');
        content_render($(this).attr('target'));
        if($(this).attr('target') == "equipment"){
            $(".bag_menu").show();
        }else{
            $(".bag_menu").hide();
        }
    });

    function item_used(e){
        var item = $(e).attr('target');
        $(e).hide();
        var current_amount = parseInt($(e).parent().parent().find('.num').html());
        if(current_amount <= 1){
            $(e).parent().parent().remove();
        }else{
            $(e).parent().parent().find('.num').html(current_amount-1);
        }
        $.post("/assets/ajax/character_item.php", {action: "use",item:item , amount : 1}, function(data) {
            $("#result").html(data);
            $(e).show();
        });
    }


    function game_bag_fast_delete(e){
        list_delete = [];
        var select_level = $('#bag_delete_level select').val();
        var select_type = $('#bag_delete_type select').val();
        if(select_level && select_type){
            $('#bag_render_content .item_list_use .item button.delete').each(function(){
                var level = $(this).attr('level');
                var type = $(this).attr('type');
                var target = $(this).attr('target');
                if(select_level >= level && (select_type == "all" || select_type == type) && $(this).css('display') != 'none'){
                    list_delete.push(target);
                }
            });
            equipment_delete(list_delete);
            popup_close();
        }
    }

    function equipment_delete(list){
        if(list.length != 0){
            alertify.confirm(language_text('text_bag_delete_confirm'), function(){ 
                $.each(list, function(index, target){
                    $('#equipment_'+target).fadeOut(500, function() {
                        $(this).remove(); 
                    });
                });
                $.post('assets/ajax/character.php',{action:"equipment_delete", equipment_list: list},function(data){
                    $('#result').html(data);
                });
            }).set('labels', {ok:language_text('text_yes'), cancel:language_text('text_no')}); ;
        }else{
            alertify.error(language_text('text_bag_delete_success'));
        }
    }

    function equipment_selfLock(e){
        var status = $(e).attr('status');
        var target = $(e).attr('target');
        if(status == "unlock"){
            $(e).attr('status','lock');
            $(e).html('<i class="fa-regular fa-unlock"></i>');
            $(e).removeClass('lock');
            $(e).parent().children('.delete').show();
        }else{
            $(e).attr('status','unlock');
            $(e).html('<i class="fa-regular fa-lock"></i>');
            $(e).addClass('lock');
            $(e).parent().children('.delete').hide();
        }
        $.post('assets/ajax/character.php',{action:"equipment_selfLock", equipment_id: target , status : status},function(data){
            $('#result').html(data);
        });
    }

    content_render('equipment');
    language_render(['text_bag_delete_success','text_bag_equipment','text_bag_item','text_bag_material','text_bag_currency','text_bag_fast_delete','text_bag_delete_confirm']);
</script>