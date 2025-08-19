<div class="user_game_module battle_champion battle_doa">
    <div class="back_dashboard"><i onclick="battle_firebase_disconnect();count_down_second = 0;load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="button_right button_style">
        <button style="display: none;" class="button_yes button_champion_top" onclick="popup_data= {'type':'pet_champion_team','limit':100};popup_load('game/score')"><i class="fas fa-trophy"></i> <span class="text_button_top"></span></button>
        <button class="button_yes" onclick="window.open('https://doc.cmanga.com/whitepaper-tieng-viet/gameplay/hoat-dong/linh-thu-chien')"><i class="fas fa-question"></i> <span class="text_button_guide"></span></button>
    </div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/pet_champion.png" />
        <p class="name"></p>
        <div style="display:none;" class="time_count_down" style="margin-top: 10px;" time="">00:00</div>
    </div>
    <div class="battle_register">
        <div class="element_limit">
        </div>
        <div style="justify-content: center;" class="button_style">
            <button target="create" onclick="popup_load('game/pet/champion')" class="button_yes">Tham gia</button>
        </div>
        <div class="time_count_down" style="margin-top: 15px;text-align: center;font-size: 20px;" time="">00:00</div> 
    </div>
    <div style="display: none;" class="progress">
        <div class="team_list">
        </div>
    </div>
</div>
<script>
    FirebaseBattleApp = "";
    async function content_render(){
        team_vote = 0;
        $('.battle_register button').attr('disabled',false);
        server_data.pet_champion = await get_server_data('pet_champion');
        $('.user_game_module .name').html(json_data.language['menu_activity_pet_champion']);
        if(server_time >= server_data.pet_champion.time.start){
            $('.battle_champion .button_champion_top').show();
        }
        var html = '';
        $.each(server_data.pet_champion.element_limit, function (index, value) {
            html += `<img src="assets/img/level/element/${value}.png">`;
        });
        $('.battle_champion .element_limit').html(html);
        if(server_time >= server_data.pet_champion.time.register){
            $('.battle_champion .time_count_down').attr('time',server_data.pet_champion.time.start);
            var player_score = await get_data_by_url(`/api/score_check?target=${my_character}&type=pet_champion_team&only_num=no&v=${server_time}`);
            if(player_score){
                player_score.data = json_convert(player_score.data);
                var member_html = '';
                for(i=1;i<=6;i++){
                    var img_level = "/assets/img/transparent.png";
                    var character_name = language_text('text_battle_position') + i;
                    var character_avatar = "/assets/img/level/icon/party_male.png";
                    var onclick = '';
                    if(player_score.data.position[i]){
                        var pet_profile = player_score.data.profile[i];
                        var main_level = Math.floor(pet_profile.level.num / 10);
                        onclick = `popup_data = {'target':'pet','id':'${pet_profile.id}'};popup_load('profile/character');`;
                        character_avatar = `/assets/img/level/pet/${pet_profile.sign}_${pet_profile.evolve}.png`;
                        character_name = `${json_data.language['pet_'+pet_profile.sign]} <img class="item_icon" src="/assets/img/level/element/${pet_profile.element}.png">`
                        img_level = `assets/img/level/icon/level/${main_level}.png`;
                    }
                    member_html += `
                        <li onclick="${onclick}">
                            <div class="image"><img src="${character_avatar}" /></div>
                            <p>${character_name}</p>
                            <img class="level" src="${img_level}" />
                        </li>
                    `;
                }
                var team_name = language_text('text_your_party')+` (${player_score.target})`;
                var html = `
                    <div class="item">
                        <h6>${team_name}</h6>
                        <ul>
                            ${member_html}
                        </ul>
                        <div class="bet">
                            <p><i class="fas fa-swords"></i> ${player_score.score}</p>
                            <button onclick="popup_data = {'team':${my_character},'type':'pet_champion_team'};popup_load('game/battle/champion_history')" class="button_style_one no battle_doa_bet_button">${language_text('text_battle_history')}</button>
                        </div>
                    </div>
                `;
                $('.progress .team_list').html(html);
                $('.battle_register').hide();
                $('.progress').show();
            }else{
                $('.battle_register').show();
            }
            if(server_time >= server_data.pet_champion.time.start){
                $('.battle_register').hide();
                $('.progress').show();
                var html = "";
                var my_team = getSafe(() => player_score.target, 0);
                battle_team_data = await get_data_by_url(`/api/score_list?type=pet_champion_team&limit=500`);
                $.each(battle_team_data, function (team, team_data) {
                    if(team_data.target != my_team){
                        team_data.data = json_convert(team_data.data);
                        var member_html = '';
                        for(i=1;i<=6;i++){
                            var img_level = "/assets/img/transparent.png";
                            var character_name = language_text('text_battle_position') + i;
                            var character_avatar = "/assets/img/level/icon/party_male.png";
                            var onclick = '';
                            var other_button = '';
                            var history_type = "pet_champion_team";
                            if(team_data.data.position[i]){
                                var pet_profile = team_data.data.profile[i];
                                var main_level = Math.floor(pet_profile.level.num / 10);
                                onclick = `popup_data = {'target':'pet','id':'${pet_profile.id}'};popup_load('profile/character');`;
                                character_avatar = `/assets/img/level/pet/${pet_profile.sign}_${pet_profile.evolve}.png`;
                                character_name = `${json_data.language['pet_'+pet_profile.sign]} <img class="item_icon" src="/assets/img/level/element/${pet_profile.element}.png">`
                                img_level = `assets/img/level/icon/level/${main_level}.png`;
                            }
                            member_html += `
                                <li onclick="${onclick}">
                                    <div class="image"><img src="${character_avatar}" /></div>
                                    <p>${character_name}</p>
                                    <img class="level" src="${img_level}" />
                                </li>
                            `;
                        }
                        html += `
                            <div class="item">
                                <h6>Team ${team_data.target}</h6>
                                <ul>
                                    ${member_html}
                                </ul>
                                <div class="bet">
                                    <p><i class="fas fa-swords"></i> ${team_data.score}</p>
                                    ${other_button}
                                    <button onclick="popup_data = {'team':${team_data.data.author[1]},'type':'${history_type}'};popup_load('game/battle/champion_history')" class="button_style_one no battle_doa_bet_button">${language_text('text_battle_history')}</button>
                                </div>
                            </div>
                        `;
                    }
                });
                $('.progress .team_list').append(html);
            }
        }else if(server_time < server_data.pet_champion.time.register){
            $('.battle_champion .time_count_down').attr('time',server_data.pet_champion.time.update);
            $('.battle_register button').attr('disabled',true);
            $('.battle_register button').css('filter','grayscale(1)');
            $('.battle_register').show();
        }
    }

    function pet_champion_register(e){
        var target = $(e).attr('target');
        $(e).attr('disabled',true);
        $.post("/assets/ajax/character_activity.php", { action : 'pet_champion_register' , target : target})
        .done(function(data) {
            $('#result').html(data);
            $(e).attr('disabled',false);
        });
    }

    function battle_firebase_connect(){
        if (FirebaseBattleApp == '') {
            FirebaseBattleApp = "yes";
            var load_battle = 1;
            FireBaseApp.child(`Activity/pet_champion/`).on('value', function (snap) {
                if (load_battle != 1) {
                    var data = snap.val();
                    if (data.target == 'team_render') {
                        content_render();
                    }
                }
                load_battle = 2;
            });
        }
    }
    function battle_firebase_disconnect() {
        if (FirebaseBattleApp != "") {
            FirebaseBattleApp = "";
            FireBaseApp.child(`Activity/pet_champion/`).off('value');
        }
    }
    battle_firebase_connect();
    content_render();
    language_render(['text_or','text_party_join','text_party_create','text_party_disband','text_button_top','text_button_guide']);
</script>