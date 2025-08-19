<div class="popup_module center_important game_job_upgrade">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="buy_confirm item">
        <div class="preview">
            <div class="image"><img src="/assets/img/transparent.png" /></div>
            <p class="name"></p>
        </div>
        <div class="confirm">
        <div class="item">
                <p class="label text_current"></p>
                <div class="price current"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/icon/mini_exp.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_current_have"></p>
                <div class="price balance"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/transparent.png"/></span></div>
            </div>
            <div class="item">
                <p class="label text_total_get"></p>
                <div class="price take"><span num="0" class="num">0</span> <span class="currency"><img src="/assets/img/level/icon/mini_exp.png"></span></div>
            </div>
        </div>
        <div class="button">
            <button style="width:100%;margin:0;" onclick="job_upgrade(this);" class="text_up"></button>
        </div>
    </div>
</div>
<script>
    async function popup_game_job_upgrade(){
        server_data.game_job = await get_server_data('game_job');
        character_job = await get_character_list('/api/character_list?list=job&character='+my_character);
        var level = getSafe(() => character_job[popup_data.target].level.num, 1);
        var current_exp = getSafe(() => character_job[popup_data.target].level.exp, 0);
        var next_exp = getSafe(() => server_data.job.exp[level+1], 999999);
        var balance = await get_data_by_url(`/api/character_count?character=${my_character}&type=material&sign=job_exp_${level}`);
        $('.popup_module.game_job_upgrade .confirm .current .num').html(number_format(current_exp)+' / '+number_format(next_exp));
        $('.popup_module.game_job_upgrade .confirm .balance .num').html(number_format(balance));
        $('.popup_module.game_job_upgrade .confirm .balance .num').attr('num',balance);
        $('.popup_module.game_job_upgrade .confirm .take .num').html(number_format(server_data.game_job.item_exp[level]));
        $('.popup_module.game_job_upgrade .confirm .balance .currency img').attr('src',`assets/img/level/material/job_exp_${level}.png`);
        $('.popup_module.game_job_upgrade .preview .image img').attr('src',`assets/img/level/menu/job/${popup_data.target}.png`);
        $('.popup_module.game_job_upgrade .preview .name').html(json_data.language['job_'+popup_data.target]+' cấp '+json_data.language['level_'+level]);
    }
    
    function job_upgrade(e){
        var balance = parseInt($('.popup_module .confirm .balance .num').attr('num'));
        if(balance >= 1){
            $(e).attr('disabled',true);
            $.post("/assets/ajax/character.php", { action : "job_upgrade" , target : popup_data.target})
            .done(function(data) {
                $(e).attr('disabled',false);
                $('#result').html(data);
            });
        }else if(balance < 1){
            alertify.error(language_text('text_not_enought_resource'));
        }
    }
    language_render(['text_current','text_current_have','text_total_get','text_up']);
    popup_game_job_upgrade();
</script>