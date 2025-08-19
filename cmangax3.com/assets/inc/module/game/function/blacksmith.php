<div style="max-width: none;" class="user_game_module user_game_dungeon shop equipment_upgrade">
    <div class="div_module">
        <h5 class="menu_function_equipment_upgrade"></h5>
        <div class="list list_fr main">
        </div>
        <div style="display: none;" id="bag_render_content">
            <div class="back_dashboard"><i onclick="$('#bag_render_content').hide();$('.user_game_dungeon .list.main').show();$('.user_game_dungeon h5').html('Nâng cấp trang bị');" class="fas fa-chevron-square-left"></i></div>

            <div class="child_module">
                <table>
                    
                </table>
                <div style="display: none;" class="equipment_recycle_div">
                    <p class="amount"><span class="text_amount">:</span> <span class="current_select">0</span>/7</p>
                    <div class="button_style">
                        <button class="button_yes function_equipment_upgrade_recycle" onclick="equipment_recycle(this)"></button>
                        <button onclick="equipment_recycle_unselect(this)" target="all" class="text_unset"></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script> 
    async function game_equipment_upgrade_render() {
        var function_list = {"upgrade":1,"add_option":1,"recycle":1,"inherit":1,"combine":2};
        html = `
        <div class="item" onclick="load_module('content','game/dashboard')">
            <img class="image" src="/assets/img/level/menu/back.png">
            <p class="title">${language_text('text_game_dashboard')}</p>
            <p class="note">${language_text('text_back')}</p>
        </div>`;
        $.each(function_list, function(key, value) {
            var onclick = `sub_equipment_upgrade_render('${key}')`;
            if(value == 2){
                var onclick = `load_module('content','game/blacksmith/${key}')`;
            }
            html += `<div class="item" onclick="${onclick}">
                        <img class="image" src="/assets/img/level/menu/equipment_upgrade/${key}.png">
                        <p class="title">${json_data.language['function_equipment_upgrade_'+key]}</p>
                        <p class="note"></p>
                    </div>`;
        })
        $(".user_game_dungeon .list.main").html(html);
    }

    async function sub_equipment_upgrade_render(key){
        recycle_list = {};
        inherit_id = "";
        $(".user_game_dungeon .list.main").hide();
        $(".user_game_dungeon h5").html(json_data.language['function_equipment_upgrade_'+key]);
        bag_equipment = await get_character_list(`/api/character_list?character=${my_character}&list=equipment`);
        html = '';
        $.each(bag_equipment, function(index, data) {
            if(data.status == "using" && ["upgrade", "add_option"].includes(key) && !["tactical", "book"].includes(data.type) || data.status == "free" && !["tactical", "book"].includes(data.type) || data.status == "free" && key == "recycle"){
                var quality = quality_convert(data.quality);
                var avatar = `<div class="equipment_icon border_level_${data.level}" style="float: left;margin-right: 10px;height: 26px;width: 26px;"><img style="height: 80%;" class="image" src="/assets/img/level/equipment/${data.type}/${data.sign}_${data.level}.png" /></div>`;
                var name = `<p class="name level_${data.level}">${json_data.language[data.type+'_'+data.sign+'_'+data.level]} +${data.upgrade}</p>`;
                var onclick = `popup_data = {'type':'equipment','id':'${index}'};popup_load('profile/item')`;
                var style = "";
                if(key == "recycle"){
                    var button = `<td><button target="${index}" onclick="equipment_recycle_select(this)">${language_text('text_select')}</button></td>`;
                }else if(key == "inherit"){
                    var button = `<td><button target="${index}" onclick="equipment_inherit_select(this)">${language_text('text_select')}</button></td>`;
                    if(data.upgrade < 2){
                        style = "display: none;";
                    }
                }else{
                    var button = `<td><button target="${index}" onclick="popup_data = {'id':'${index}'};popup_load('game/blacksmith/${key}')">${json_data.language['function_equipment_upgrade_'+key]}</button></td>`;
                }
                html += `
                    <tr style="${style}" upgrade="${data.upgrade}" id="equipment_${index}" class="equipment_type_${data.type} equipment_level_${data.level}">
                        <td>
                            <div class="info">
                                <a onclick="${onclick}">
                                    ${avatar}
                                    ${name}
                                    <div class="clear"></div>
                                </a>
                            </div>
                        </td>
                        ${button}
                    </tr>
                `;
            }
        });
        $("#bag_render_content .child_module table").html(html);
        $("#bag_render_content").show();
    }

    function equipment_recycle_select(e){
        var equipment_id = $(e).attr("target");
        var equipment_data = bag_equipment[equipment_id];
        recycle_list[equipment_id] = 1;
        $(`#equipment_${equipment_id} button`).attr("onclick",`equipment_recycle_unselect(this)`);
        $(`#equipment_${equipment_id} button`).html("Bỏ chọn");
        $(`#equipment_${equipment_id} button`).addClass("disable");
        if(Object.keys(recycle_list).length == 1){
            $("#bag_render_content .child_module table tr").hide();
            $(`#bag_render_content .equipment_type_${equipment_data.type}.equipment_level_${equipment_data.level}`).show();
            $('.user_game_module.equipment_upgrade .equipment_recycle_div').show();
        }
        $('.equipment_recycle_div .current_select').html(Object.keys(recycle_list).length);
    }

    function equipment_inherit_select(e){
        var equipment_id = $(e).attr("target");
        var equipment_data = bag_equipment[equipment_id];
        inherit_id = equipment_id;
        $("#bag_render_content .child_module table tr button").attr("onclick",`popup_data={'id':$(this).attr('target')};popup_load('game/blacksmith/inherit')`);
        $("#bag_render_content .child_module table tr button").html("Kế thừa");
        $(`#equipment_${equipment_id} button`).attr("onclick",`equipment_inherit_unselect()`);
        $(`#equipment_${equipment_id} button`).html("Bỏ chọn");
        $(`#equipment_${equipment_id} button`).addClass("disable");
        $("#bag_render_content .child_module table tr").hide();
        $(`#bag_render_content .equipment_type_${equipment_data.type}`).show();
    }

    function equipment_inherit_unselect(){
        sub_equipment_upgrade_render("inherit");
    }

    function equipment_recycle_unselect(e){
        var equipment_id = $(e).attr("target");
        if(equipment_id == "all"){
            recycle_list = {};
            $("#bag_render_content .child_module table tr").show();
            $("#bag_render_content .child_module table tr button").html("Chọn");
            $("#bag_render_content .child_module table tr button").removeClass("disable");
            $("#bag_render_content .child_module table tr button").attr("onclick",`equipment_recycle_select(this)`);
        }else{
            delete recycle_list[equipment_id];
            $(`#equipment_${equipment_id} button`).attr("onclick",`equipment_recycle_select(this)`);
            $(`#equipment_${equipment_id} button`).html("Chọn");
            $(`#equipment_${equipment_id} button`).removeClass("disable");
        }
        if(Object.keys(recycle_list).length == 0){
            $("#bag_render_content .child_module table tr").show();
            $('.user_game_module.equipment_upgrade .equipment_recycle_div').hide();
        }
        $('.equipment_recycle_div .current_select').html(Object.keys(recycle_list).length);
    }

    function equipment_recycle(e){
        if(Object.keys(recycle_list).length == 7){
            popup_load('game/blacksmith/loading');
            $(e).prop('disabled', true);
            setTimeout(function(){
                popup_close();
                $.post("/assets/ajax/character.php", { action : 'equipment_recycle' , recycle_list : JSON.stringify(recycle_list) })
                .done(function(data) {
                    $('#result').html(data);
                    $(e).prop('disabled', false);
                });
            }, 2000);
        }else{
            alertify.error(language_text('text_equipment_recycle_need'));
        }
    }
    

    game_equipment_upgrade_render();
    language_render(['menu_function_equipment_upgrade','function_equipment_upgrade_recycle','text_amount','text_unset','function_equipment_upgrade_upgrade','function_equipment_upgrade_add_option','function_equipment_upgrade_recycle','function_equipment_upgrade_inherit','function_equipment_upgrade_combine'])
</script>