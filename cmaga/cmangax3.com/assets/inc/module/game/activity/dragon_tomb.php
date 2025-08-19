<div class="user_game_module dragon_tomb">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/transparent.png" />
        <p class="name"></p>
        <div class="level bar_div"></div>
    </div>
    <div class="button center">
        <button onclick="dragon_tomb_fight(this)" class="button_yes text_fight"></button>
    </div>
    <div class="menu">
        <div target="guide" class="item active text_intro"></div>
        <div target="top" class="item text_top_board"></div>
    </div>
    <div class="guide child_module">
        <ul>
            <li class="text_dragon_tomb_guide_1"></li>
            <li class="text_dragon_tomb_guide_2"></li>
            <li class="text_dragon_tomb_guide_3"></li>
            <li class="text_dragon_tomb_guide_4"></li>
            <li class="text_dragon_tomb_guide_5"></li>
            <li class="text_dragon_tomb_guide_6"></li>
            <li class="text_dragon_tomb_guide_7"></li>
            <li class="text_dragon_tomb_guide_8"></li>
            <li class="text_dragon_tomb_guide_9"></li>
            <li class="text_dragon_tomb_guide_10"></li>
            <li class="text_dragon_tomb_guide_11"></li>
            <li class="text_dragon_tomb_guide_12"></li>
            <li class="text_dragon_tomb_guide_13"></li>
        </ul>
    </div>
    <div style="display:none;" class="top child_module">
        <ul>
        </ul>
    </div>
</div>
<script>
    page = 1;
    async function content_render(){
        top_data = await get_data_by_url('/api/score_list?type=game_dragon_tomb&limit=100');
        var html = '';
        $.each(top_data,function(index,value){
            var character_info = JSON.parse(value.info);
            html+=`
                <li>
                    <div class="stt">${index+1}</div>
                    <div onclick="character_profile('character',${value.target})" class="info">
                        <img src="/assets/tmp/avatar/${character_info.avatar}">
                        <div class="name">${character_info.name}</div>
                    </div>
                    <div class="score">${number_format(value.score)} <i class="fas fa-swords"></i></div>
                </li>                     
            `;
        });
        $('.user_game_module .top ul').html(html);
        monster_data = await get_data_by_url('/api/get_monster_by_type?type=dragon_tomb&level=1&v='+server_time);
        monster_data = monster_data[0];
        monster_data.data = JSON.parse(monster_data.data);

        var monster_name = monster_data.data.element == "none" ? json_data.language['monster_'+monster_data.data.info.sign] : json_data.language['monster_'+monster_data.data.info.sign] + "<img style='margin-left: 7px;margin-top: -10px;' class='item_icon' src='assets/img/level/element/"+monster_data.data.element+".png'>";
        $('.user_game_module .avatar').html(`
            <a onclick="popup_data = {'target':'monster','id':'${monster_data.id_game_monster}'};popup_load('profile/character');">
                <img style="max-height: 150px;" class="image" src="/assets/img/level/monster/${monster_data.data.info.avatar}" />
                <p class="name">${monster_name}</p>
                <div class="level bar_div"></div>
            </a>
        `);
        $('.user_game_module .avatar .level').html(level_render(monster_data.data.info.level));
    }

    function dragon_tomb_fight(e){
        $(e).hide();
        $.post('assets/ajax/character_activity.php',{action:"dragon_tomb_fight"},function(data){
            $('#result').html(data);
        });
    }

    $(".user_game_module .menu .item").click(function() {
        $(".user_game_module .menu .item").removeClass('active');
        $(this).addClass('active');
        $(".user_game_module .child_module").hide();
        $(".user_game_module .child_module."+$(this).attr('target')).show();
    });
    
    content_render();

    language_render(['text_fight','text_intro','text_top_board','text_dragon_tomb_guide_1','text_dragon_tomb_guide_2','text_dragon_tomb_guide_3','text_dragon_tomb_guide_4','text_dragon_tomb_guide_5','text_dragon_tomb_guide_6','text_dragon_tomb_guide_7','text_dragon_tomb_guide_8','text_dragon_tomb_guide_9','text_dragon_tomb_guide_10','text_dragon_tomb_guide_11','text_dragon_tomb_guide_12','text_dragon_tomb_guide_13']);
</script>