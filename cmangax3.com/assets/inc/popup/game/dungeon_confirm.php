<div class="popup_module center_important dungeon_confirm">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <img class="icon" src="/assets/img/transparent.png">
    <p class="name"></p>
    <p class="note"></p>
    <div class="reward">
        <div class="list_reward list_item" style="margin: 20px auto;">
        </div>
    </div>
    <div style="justify-content: center;margin:30px 0 10px;" class="button_style">
        <button target="one" onclick="dungeon_fight(this)" ><i class="fas fa-swords"></i> <text class="text_one_turn"></text></button>
        <button target="all" onclick="dungeon_fight(this)" ><i class="fas fa-swords"></i> <text class="text_all_turn"></text></button>
    </div>
</div>
<script>
    async function popup_render(){
        $('.dungeon_confirm .name').text(json_data.language['activity_dungeon_'+popup_data.main]);
        $('.dungeon_confirm .note').text(`${language_text('text_floor')} ${popup_data.sub}`);
        $('.dungeon_confirm .icon').attr('src',`/assets/img/level/menu/dungeon/${popup_data.main}.png`);
        var reward_list = {};

        if(server_data.game_dungeon[popup_data.main].type == 'material'){
            $.each(server_data.game_dungeon[popup_data.main].data[popup_data.sub].reward, function(key3, value3) {
                reward_list[value3] = {};
                reward_list[value3].type = server_data.game_dungeon[popup_data.main].type;
                reward_list[value3].amount = 1;
            })
        }else{
            reward_list[popup_data.main] = {};
            reward_list[popup_data.main].type = popup_data.main == 'exp' ? 'currency' : server_data.game_dungeon[popup_data.main].type;
            reward_list[popup_data.main].amount = server_data.game_dungeon[popup_data.main].data[popup_data.sub].amount;
        }
        html = "";
        $.each(reward_list, function(key, value) {
            html += item_render(key,value.type,value.amount);
        })
        $(".dungeon_confirm .list_reward").html(html);
    }

    function dungeon_fight(e){
        $('.dungeon_confirm .button_style').hide();
        var target = $(e).attr('target');
        $.post('assets/ajax/character_activity.php',{action : "dungeon_fight",main : popup_data.main,sub : popup_data.sub,target : target},function(data){
            $('#result').html(data);
            $('.dungeon_confirm .button_style').show();
        });
    }
    popup_render();
    language_render(['text_one_turn','text_all_turn']);
</script> 