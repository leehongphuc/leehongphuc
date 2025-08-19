<div class="user_game_module shop pet">
    <div class="back_dashboard"><i onclick="load_module('content','game/function/friend')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/friend/friend_list.png" />
        <p class="name"></p>
    </div>
    <div id="bag_render_content">
        <div class="child_module pet_list">
            <div class="item_list_use"></div>
        </div>
        <div style="margin-top: 15px;" class="button_style">
            <button style="width: auto;padding: 0 10px;" onclick="popup_load('game/friend/fast_release')" class="button_yes pet_button"><i class="fa-solid fa-trash"></i> <span>Phóng thích nhanh</span>
        </div>
    </div>
</div>
<script>
    page = 1;
    bag_data = {};
    async function content_render(type) {
        bag_data = {};
        $('.user_game_module .avatar .name').text(json_data.language.function_friend_list);
        await get_server_data('game_item');
        bag_data[type] = await get_character_list(`/api/character_list?character=${my_character}&list=${type}`);
        var list = Object.values(bag_data[type]);
        list.sort(function(a,b) {
            return b.cp - a.cp;
        });
        html = '';
        $.each(list, function(index, data) {
            var quality = quality_convert(data.quality, 'pet');
            var main_level = Math.floor(data.level.num/10);
            var sub_level = data.level.num%10;
            var text_level = json_data.language['character_level_'+main_level]+' '+json_data.language['character_mini_level_'+sub_level];
            var avatar = `<div class="equipment_icon"><img style="height: 100%;" class="image" src="/assets/img/level/friend/avatar/${data.sign}.png" /></div>`;
            var onclick = `popup_data = {'target':'friend','id':'${data.id}'};popup_load('profile/character')`;
            var button = ``;
            var status = `<span>${text_level}</span>`;
            if (data.status == "free") {
                button += `
                    <button class="circle" onclick="popup_data = {'sign':'${data.sign}','tier':'${data.tier}','id':'${data.id}'};popup_load('game/friend/release')"><i class="fa-solid fa-trash"></i></button>
                    <button class="circle" onclick="popup_data = {'sign':'${data.sign}','tier':'${data.tier}','id':'${data.id}'};popup_load('game/friend/skill')"><i class="fas fa-bolt"></i></button>
                `;
            }
            button += `
                <button class="circle" onclick="popup_data = {'sign':'friend_exp','type':'currency','id':'${data.id}'};popup_load('game/friend/level')"><i class="fa-solid fa-angles-up"></i></button>
            `;

            
            html += `
                <div class="item break" id="${type}_${index}">
                    <div onclick="${onclick}" class="info">
                        <div class="image"><img class="avatar" src="/assets/img/level/friend/avatar/${data.sign}.png" /></div>
                        <div class="detail">
                            <p class="name skill_rare_${data.tier}">${json_data.language['friend_name_'+data.sign]} ( <span class="status">${json_data.language['friend_status_'+data.status]}</span> )</p>
                            <p class="text">${status}</p>
                        </div>
                    </div>
                    <div class="button_control">
                        ${button}
                    </div>
                </div>
            `;
        });
        $("#bag_render_content .child_module .item_list_use").html(html);
    }

    function friend_support_add(e){
        $(e).prop('disabled', true);
        var support_id = $(e).attr('target');
        $('.popup_module.friend_support_select').parent().remove();
        $.post("/assets/ajax/character_friend.php", { action : "support_add" , friend_id : character_profile_id , support_id : support_id})
        .done(function(data) {
            friend_data_render();
            $('#result').empty().append(data);
            $(e).prop('disabled', false);
        });
    }

    function friend_support_out(e){
        $(e).prop('disabled', true);
        var support_sign = $(e).attr('target');
        $.post("/assets/ajax/character_friend.php", { action : "support_out" , friend_id : character_profile_id , support_sign : support_sign})
        .done(function(data) {
            friend_data_render();
            $('#result').empty().append(data);
            $(e).prop('disabled', false);
        });
    }
    content_render('friend');
</script>