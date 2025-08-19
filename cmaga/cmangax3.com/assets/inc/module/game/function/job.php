<div class="div_module">
    <div class="user_game_job">
        <div class="back_dashboard main"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
        <div style="display: none;" class="back_dashboard child"><i onclick="game_job_reset()" class="fas fa-chevron-square-left"></i></div>
        <h5 class="menu_function_job"></h5>
        <div class="current_energy currency_div">
            <div class="item">
                <img class="icon" src="/assets/img/level/item/heart.png">
                <p class="num"></p>
            </div>
        </div>
        <div  class="child_module job_list">
            <div class="list list_fr main">

            </div>
        </div>
        <div style="display:none;" class="child_module create_list">
            <div class="choose_list">
                <div class="item_list_use"></div>
            </div>
        </div>
    </div>
</div>
<script> 
    async function character_heart_reload(){
        var my_energy = await get_data_by_url('/api/character_other_energy?type=heart&character='+my_character);
        $(".user_game_job .current_energy .num").html(my_energy.current);
    }
    async function content_render() {
        character_job = await get_character_list('/api/character_list?list=job&character='+my_character);
        server_data.job = await get_server_data('game_job');
        var list_job = {'weapon':1,'armor':1,'medicinal':1,'pet_equipment':1};
        html = '';
        $.each(list_job, function(key, value) {
            var opacity = value==1?'1':'0.5';
            var current_exp = getSafe(() => character_job[key].level.exp, 0);
            var current_level = getSafe(() => character_job[key].level.num, 1);
            var next_exp = getSafe(() => server_data.job.exp[current_level+1], 999999);
            html += `
                <div style="opacity:${opacity}" class="item">
                    <img class="image" src="/assets/img/level/menu/job/${key}.png">
                    <p class="name">${json_data.language['job_'+key]}</p>
                    <div class="other">
                        <p><img class="item_icon" src="/assets/img/level/icon/mini_level.png"/> ${language_text('text_level')}</p>
                        <p>${json_data.language['level_'+current_level]}</p>
                    </div>
                    <div class="other">
                        <p><img class="item_icon" src="/assets/img/level/icon/mini_exp.png"/> ${language_text('text_master')}</p>
                        <p>${current_exp}/${next_exp}</p>
                    </div>
                    <div style="justify-content: center;margin: 20px 0;" class="button_style">
                        <button onclick="popup_data= {'target':'${key}'};popup_load('game/job_upgrade')" class="button_no">${language_text('text_up')}</button>
                        <button onclick="create_list_render('${key}')" class="button_yes">${json_data.language['job_'+key+'_button']}</button>
                    </div>
                </div>
            `;
        })
        $('.user_game_job .list_fr.main').html(html);
        character_heart_reload();
    }
    function create_list_render(type){
        $('.user_game_job .child_module').hide();
        var html = '';
        $.each(server_data.job[type], function(level,level_data) {
            var current_level = getSafe(() => character_job[type].level.num, 1);
            var opacity = current_level>=level?'1':'0.5';
            $.each(level_data, function(item,item_data) {
                if(current_level > level){
                    var item_exp = 0;
                }else{
                    var item_exp = item_data.exp;
                }
                html += `

                    <div class="item break" style="opacity:${opacity};">
                        <div onclick="popup_data = {'type':'${item_data.type}','sign':'${item_data.sign}'};popup_load('profile/item');" class="info">
                            <div class="image"><img class="avatar" src="/assets/img/level/${item_data.type}/${item_data.sign}.png"></div>
                            <div class="detail">
                                <p class="name level_${level}">${item_data.amount} ${json_data.language[item_data.type+'_'+item_data.sign]}</p>
                                <div class="text">
                                    <img class="item_icon" src="/assets/img/level/icon/mini_level.png"/> <p>${json_data.language['level_'+level]}</p> 
                                    <img class="item_icon" src="/assets/img/level/icon/mini_exp.png"/> <p>${number_format(item_exp)}</p> 
                                    <img class="item_icon" src="/assets/img/level/item/heart.png"/> <p>${number_format(item_data.heart)}</p> 
                                </div>
                            </div>
                        </div>
                        <div class="button_control">
                            <button onclick="popup_data= {'main':'${type}','level':'${level}','sub':'${item}','amount':'${item_data.amount}'};popup_load('game/job_create')">${language_text('text_select')}</button>
                        </div>
                    </div>
                `;
            });
        });
        $('.user_game_job .create_list .choose_list .item_list_use').html(html);
        $('.user_game_job .create_list').show();
        $('.user_game_job .back_dashboard').hide();
        $('.user_game_job .back_dashboard.child').show();
    }
    function game_job_reset(){
        $('.user_game_job .child_module').hide();
        $('.user_game_job .job_list').show();
        $('.user_game_job .back_dashboard').hide();
        $('.user_game_job .back_dashboard.main').show();
    }
    content_render();

    language_render(["menu_function_job"]);
</script>