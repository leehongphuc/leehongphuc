<div class="user_game_dungeon">
    <div class="div_module">
        <h5 class="text_training_title"></h5>
        <div class="list list_fr main">
        </div>
        <div style="display: none;" class="list list_fr sub_main">
        </div>
    </div>
</div>
<div style="display:none;" class="user_game_module game_training">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/transparent.png" />
        <p class="name"></p>
        <p style="margin-top:10px;" class="note"></p>
    </div>
    <div class="proccess">
        <div class="item time">
            <p class="label text_time"></p>
            <p class="value"><span></span> <text class="text_min"></text></p>
        </div>
        <div class="item fee">
            <p class="label text_total_fee"></p>
            <p class="value"><span></span> <img class="item_icon" src="/assets/img/level/currency/gold.png"/></p>
        </div>
        <div class="item take">
            <p class="label text_total_get"></p>
            <p class="value"><span></span> <img class="item_icon" src="/assets/img/transparent.png"/></p>
        </div>
    </div>
    <div class="button center">
        <button onclick="game_training_action('take')" class="button_yes text_get_reward"></button>
        <button onclick="game_training_action('cancel')" class="button_no text_cancel"></button>
    </div>
</div>
<script> 
    async function game_training_render() {
        character_profile_data = await get_data_by_url('/api/get_data_by_id?table=game_character&data=other&id='+my_character+'&v='+server_time);
        character_profile_data.other = JSON.parse(character_profile_data.other);
        server_data.game_training = await get_server_data('game_training');
        html = `
        <div class="item" onclick="load_module('content','game/dashboard')">
            <img class="image" src="/assets/img/level/menu/back.png">
            <p class="title">${language_text('text_game_dashboard')}</p>
            <p class="note">${language_text('text_game_back')}</p>
        </div>`;
        $.each(server_data.game_training, function(key, value) {
            html += `<div class="item" onclick="sub_training_render('${key}')">
                        <img class="image" src="/assets/img/level/menu/training/${key}.png">
                        <p class="title">${json_data.language['activity_training_'+key]}</p>
                        <p class="note">${json_data.language['activity_training_'+key+'_note']}</p>
                    </div>`;
        })
        $(".user_game_dungeon .list.main").html(html);
        if(character_profile_data.other.training){
            $('.user_game_dungeon').hide();
            $('.user_game_module').show();
            $('.user_game_module .avatar .image').attr('src',`/assets/img/level/menu/training/${character_profile_data.other.training.main}.png`);
            $('.user_game_module .avatar .name').text(json_data.language['activity_training_'+character_profile_data.other.training.main]);
            $('.user_game_module .avatar .note').text(`Tầng ${character_profile_data.other.training.level}`);
            var total_minute = Math.floor((server_time-character_profile_data.other.training.time)/60);
            total_minute = total_minute >= 9999 ? 9999 : total_minute;
            $('.user_game_module .proccess .time .value span').text(total_minute);
            var premium_level = getSafe(() => my_profile.info.game_premium.level, 0);
            var fee = server_data.game_training[character_profile_data.other.training.main][character_profile_data.other.training.level].fee*total_minute;
            fee = premium_level > 0 ? Math.floor(fee*(100-server_data.user_premium.game_premium[premium_level].data.reduce_gold_training)/100) : fee;
            $('.user_game_module .proccess .fee .value span').text(Math.floor(fee));
            $('.user_game_module .proccess .take .value span').text(Math.floor(server_data.game_training[character_profile_data.other.training.main][character_profile_data.other.training.level].amount*total_minute));
            $('.user_game_module .proccess .take .value img').attr('src',`/assets/img/level/menu/training/${character_profile_data.other.training.main}.png`);
        }else{
            $('.user_game_dungeon').show();
            $('.user_game_module').hide();
        }
    }

    function sub_training_render(key){
        html = `
            <div class="item" onclick="$('.user_game_dungeon .list.sub_main').hide();$('.user_game_dungeon .list.main').show();">
                <img class="image" src="/assets/img/level/menu/back.png">
                <p class="title">${language_text('text_area_select')}</p>
                <p class="note">${language_text('text_game_back')}</p>
            </div>`;
        $.each(server_data.game_training[key], function(key2, value2) {
            var note = `${value2.amount} <img class="item_icon" src="/assets/img/level/menu/training/${key}.png"/> ${value2.fee} <img class="item_icon" src="/assets/img/level/currency/gold.png"/>`;
            html += `<div onclick="popup_data = {'main':'${key}','level':'${key2}'};popup_load('game/training_confirm')" class="item">
                        <img class="image" src="/assets/img/level/menu/training/${key}.png">
                        <p class="title">${language_text('text_floor')} ${key2}</p>
                        <p class="note">${note}</p>
                    </div>`;
        })
        $(".user_game_dungeon .list.sub_main").html(html);
        $(".user_game_dungeon .list.main").hide();
        $(".user_game_dungeon .list.sub_main").show();
    }

    function game_training_action(target){
        var note = target == "take" ? language_text('text_training_get_reward_note') : language_text('text_training_cancel_note');
        alertify.confirm(language_text('text_confirm'),note, function() {
            $('.user_game_module.game_training .button').hide();
            $.post("/assets/ajax/character_activity.php", { action : "game_training_"+target })
            .done(function(data) {
                $('#result').empty().append(data);
                $('.user_game_module.game_training .button').show();
            });
        }, function() {
        });
    }

    game_training_render();

    language_render(['text_training_title','text_time','text_min','text_total_fee','text_total_get','text_get_reward','text_cancel']);
</script>