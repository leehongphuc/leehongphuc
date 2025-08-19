<div class="frame_content_div">
    <div class="battle_screen">
        <div class="center_div game_battle_emulator">
            <div target="top" class="team_stand top"></div>
            <div target="bottom" class="team_stand bottom"></div>
            <div class="skill_name center_div"></div>
        </div>
        <div class="battle_child_screen top_damage center_div">
            <h5>Team 1</h5>
            <ul class="enemy_team">
            </ul>
            <h5>Team 2</h5>
            <ul class="my_team">
            </ul>
            <div onclick="$('.battle_child_screen').hide();" class="close"><i class="fal fa-times center_div"></i></div>
        </div>
        <div class="battle_child_screen text_damage center_div">
            <div class="list">
            </div>
            <div onclick="$('.battle_child_screen').hide();" class="close"><i class="fal fa-times center_div"></i></div>
        </div>
        <div class="battle_child_screen battle_reward center_div">
            <img style="display: none;" class="image" src="/assets/img/transparent.png" />
            <ul>

            </ul>
            <div onclick="$('.battle_child_screen').hide();" class="close"><i class="fal fa-times center_div"></i></div>
        </div>
    </div>
    <div class="icon">
        <i onclick="battle_text_damage_render();$('.battle_child_screen.text_damage').show();" class="fa-solid fa-text"></i>
        <i onclick="battle_reward_render();$('.battle_child_screen.battle_reward').show();" class="fas fa-gift"></i>
        <i onclick="battle_top_damage_render();$('.battle_child_screen.top_damage').show();" class="fas fa-chart-bar"></i>
        <i onclick="send_battle(this);" class="fas fa-comments"></i>
        <i onclick="frame_close();clear_time_out(battle_emulator);" class="fal fa-times"></i>
    </div>
</div>
<div class="frame_background"><img src="/assets/img/level/battle_background/trans.png" /></div>
<script>
    character_buff = {};
    battle_emulator = [];
    battle_effect_version = 0;
    async function battle_emulator_render(id){
        clear_time_out(battle_emulator);
        clear_time_out(battle_emulator);
        clear_time_out(battle_emulator);
        setTimeout(() => {
            $('.frame_content_div .close').show();
       }, 5000);
        battle_data = await get_data_by_url('/api/get_data_by_id?table=game_battle&data=data&id='+ id);
        battle_data = JSON.parse(battle_data.data);
        console.log(battle_data);
        $('.frame_content_div .battle_screen').addClass(battle_data.format);
        $('#frame_content .frame_background img').attr("src","/assets/img/level/battle_background/"+battle_data.background);
        battle_data.real_time = {};
        $.each(battle_data.team, function(team, team_data) {
            if(team != my_character && $('.battle_screen .top .standing').length == 0){
                $('.battle_screen .game_battle_emulator .top').addClass("team_"+team)
            }else{
                $('.battle_screen .game_battle_emulator .bottom').addClass("team_"+team)
            }
            battle_data.real_time[team] =  JSON.parse(JSON.stringify(battle_data.team[team]));
            battle_team_render(team,team_data);
        });
        battle_emulator_play();
    }
    function battle_team_render(team,team_data){
        
        html = "";
        var team_list = Object.values(team_data.list);
                
        position_check = {};
        for(i=1;i<=6;i++){
            position_check[i] = i;
        }
        $.each(team_list, function(id, character) {
            delete position_check[character.position];
        });
        $.each(position_check, function(index, value) {
            data = {};
            data.position = value;
            data.info = "";
            team_list.push(data);
        });
        
        team_list.sort(function(a,b) {
            return a.position - b.position;
        });
        if(team == 'monster' || team == 'boss'){
            target = 'monster';
        }else{
            target = 'character';
        }
        if(team == 'boss' || team == 'monster'){
            default_status = '<li class="status_antiEffect"><img src="/assets/img/level/effect/icon/antiEffect.png"></li>';
            setTimeout(() => {
                $('.frame_content .battle_screen .game_battle_emulator .team_boss .standing').css('zoom','1');
            }, 300)
        }else{
            default_status = '';
        }
        $.each(team_list, function(id, character) {
            if(id<=5){
                if(character.info != ""){
                    new_html =  `
                        <div class="standing total_${team_list.length}">
                            <div team ="${team}" id="character_battle_${team}_${character.info.id}">
                                ${battle_avatar_render(team+"_"+character.info.id)}
                                <img class="effect" src="/assets/img/level/effect/attack.png" />
                                <div class="hp_mp_bar">
                                    <div class="bar_div hp">
                                        <div style="width:100%" class="bar"></div>
                                    </div>
                                    <div class="bar_div mp">
                                        <div style="width:0%" class="bar"></div>
                                    </div>
                                    <div class="status_icon">
                                        <ul>${default_status}</ul>
                                    </div>
                                </div>
                                <div class='damage_value'>
                                    <p></p>
                                </div>
                            </div>
                            <div class="stand"></div>
                        </div>
                    `;
                }else{
                    new_html =  `
                        <div class="standing total_${team_list.length}">
                            <div class="stand"></div>
                        </div>
                    `;
                }
                if($('.battle_screen .team_'+team).attr('target') == 'bottom' || battle_data.format == 'solobo5'){
                    html = html + new_html;
                }else{
                    html = new_html+ html;
                }
            }else{
                return false;
            }
        })
        $('.battle_screen .team_'+team).html(html);
    }
    function battle_emulator_play(){
        time_out = 0;
        last_action = "";
        setTimeout(() => {
            if(battle_data.buff){
                $(`.battle_screen .standing .effect`).attr("src","");
                $(".battle_screen .standing .avatar").removeClass("attack");
                $(".battle_screen .standing .effect").removeClass("show");
                $(".battle_screen .standing .damage_value").removeClass("show");
                $(".battle_screen .standing .damage_value p").attr("class","");
                $.each(battle_data.buff, function(index, data) {
                     var buff_value = Math.round((1-data)*100);
                     var buff_icon = data < 1 ? 'debuff' : 'abuff';
                     var buff_sign = data < 1 ? '-' : '+';
                     $(`#character_battle_${index} .effect`).attr("src",`assets/img/level/effect/auto${buff_icon}.png`);
                     $(`#character_battle_${index} .effect`).addClass("show");
                     $(`#character_battle_${index} .damage_value p`).addClass(buff_icon);
                     $(`#character_battle_${index} .damage_value p`).text(buff_sign+buff_value+"%"); 
                     $(`#character_battle_${index} .damage_value`).addClass("show");
                     real_id = index.split("_");
                     real_id = real_id[1];
                     character_buff[real_id] = data;
                     
                });
            }
            if(battle_data.current_hp){
                $.each(battle_data.current_hp, function(team, team_data) {
                     $.each(team_data, function(character, hp) {
                        character = character.split("_");
                        character = character[1];
                        character = team+'_'+character; 
                        total_hp = battle_data.battle[character].hp;
                        hp_bar_width = Math.floor(hp/total_hp*100);
                        $(`#character_battle_${character} .bar_div.hp .bar`).css("width",hp_bar_width+"%");
                        if(hp <= 0){
                            $(`#character_battle_${character} .avatar`).css("filter","grayscale(1)");
                        }
                     });
                });
            }
        }, 400)
        setTimeout(() => {
            $.each(battle_data.emulator, function(index, data) {
                $.each(data, function(index1, data1) {
                    $.each(data1, function(index2, data2) {
                        if(last_action == 'skill'){
                            time_out = time_out + 2500;
                        }else{
                            time_out = time_out + 1000;
                        }
                        last_action = data2.action;
                       battle_emulator.push( setTimeout(() => {
                            battle_emulator_action(index2,data2);
                       }, time_out));
                    });
                });
            });
            battle_emulator.push( setTimeout(() => {
                battle_emulator_end();
            }, time_out+1000));
        }, 800)
    }

    function battle_emulator_end(){
        $(`.battle_screen .standing .effect`).attr("src","");
        $(".battle_screen .standing .avatar").removeClass("attack");
        $(".battle_screen .standing .avatar").removeClass("skill");
        $(".battle_screen .standing .avatar").removeClass("attacked");
        $(".battle_screen .standing .effect").removeClass("show");
        $(".battle_screen .standing .damage_value").removeClass("show");
        $(".battle_screen .standing .damage_value p").attr("class","");
        $(".battle_screen .skill_name").hide();
        battle_reward_render();
    }
    function battle_emulator_action(attacker,data){
        $(`.battle_screen .standing .effect`).attr("src","");
        $(".battle_screen .standing .avatar").removeClass("attack");
        $(".battle_screen .standing .avatar").removeClass("skill");
        $(".battle_screen .standing .avatar").removeClass("attacked");
        $(".battle_screen .standing .effect").removeClass("show");
        $(".battle_screen .standing .damage_value").removeClass("show");
        $(".battle_screen .standing .damage_value p").attr("class","");
        $(".battle_screen .skill_name").hide();
        setTimeout(() => {
            if(data.action != "none"){
                $.each(data.list, function(index1, data1) {
                    if(data.action == 'skill' || data.action == 'heal' || data.action == 'buff' || data.action == 'debuff'){
                        $(`#character_battle_${index1} .effect`).attr("src",`assets/img/level/effect/skill/${data.skill}.png`);
                        $(`#character_battle_${index1} .avatar`).addClass("attacked");
                        $(`#character_battle_${attacker} .avatar`).addClass("skill");
                        $(".battle_screen .skill_name").text(json_data.language['skill_'+data.skill]);
                        $(".battle_screen .skill_name").fadeIn(300);
                    }else if(data1.damage < 0){
                        $(`#character_battle_${index1} .effect`).attr("src",`assets/img/level/effect/autoheal.png`);
                        $(`#character_battle_${attacker} .avatar`).addClass("skill");
                    }else{
                        $(`#character_battle_${index1} .effect`).attr("src",`assets/img/level/effect/${data.action}.png?v=${battle_effect_version}`);
                        $(`#character_battle_${index1} .avatar`).addClass("attacked");
                        $(`#character_battle_${attacker} .avatar`).addClass("attack");
                    }
                    $(`#character_battle_${index1} .effect`).addClass("show");
                    $(`#character_battle_${index1} .damage_value p`).addClass(data.action);
                    if(data1.damage != 99999 && (data.action == "autobuff" || data.action == "autodebuff")){
                        $(`#character_battle_${index1} .damage_value`).addClass("show");
                        $(`#character_battle_${index1} .damage_value p`).text(data1.damage+"%");    
                    }else if(data.action == "autoheal"){
                        $(`#character_battle_${index1} .damage_value`).addClass("show");
                        $(`#character_battle_${index1} .damage_value p`).text("+"+data1.damage);    
                        $(`#character_battle_${index1} .damage_value p`).addClass('heal');
                    }else if(data1.damage < 0){
                        $(`#character_battle_${index1} .damage_value`).addClass("show");
                        data1.damage = 0 - data1.damage;
                        $(`#character_battle_${index1} .damage_value p`).text("+"+number_format(data1.damage)); 
                        $(`#character_battle_${index1} .damage_value p`).addClass('heal');
                    }else if(data.action == "skill" || data.action == "attack"){
                        $(`#character_battle_${index1} .damage_value`).addClass("show");
                        $(`#character_battle_${index1} .damage_value p`).text("-"+number_format(data1.damage));
                    }
                    
                    if(data.critical == "yes"){
                        $(`#character_battle_${index1} .damage_value p`).addClass("critical");    
                    }
                });
                battle_effect_version++;
                if(Object.keys(data.list).length == 0 && data.effect){
                    $.each(data.effect, function(index1, data1) {
                        $(`#character_battle_${index1} .effect`).attr("src",`assets/img/level/effect/skill/${data.skill}.png`);
                        $(`#character_battle_${index1} .effect`).addClass("show");
                        $(`#character_battle_${index1} .damage_value p`).addClass(data.action);
                        if(data1 != 99999){
                            $(`#character_battle_${index1} .damage_value`).addClass("show");
                            $(`#character_battle_${index1} .damage_value p`).text(data1+"%");    
                        }
                    });
                }
            }
            if(data.now){
                if(data.now.hp){
                    $.each(data.now.hp, function(index2, now_hp) {
                        total_hp = battle_data.battle[index2].hp;
                        hp_bar_width = Math.floor(now_hp/total_hp*100);
                        $(`#character_battle_${index2} .bar_div.hp .bar`).css("width",hp_bar_width+"%");
                        if(now_hp <= 0){
                            $(`#character_battle_${index2} .avatar`).css("filter","grayscale(1)");
                            if(battle_data.format == 'onevone'){
                                setTimeout(() => {
                                    team = $(`#character_battle_${index2}`).attr('team');
                                    delete battle_data.real_time[team].list[index2];
                                    battle_team_render(team,battle_data.real_time[team]);
                                },500)
                            }
                        }
                    });
                }
                if(data.now.mp){
                    $.each(data.now.mp, function(index2, now_mp) {
                        skill_sign = battle_data.battle[index2].skill.sign; 
                        skill_type = json_data.skill.profile[skill_sign].sign;
                        mp_total = json_data.skill.data[skill_type].mp;
                        mp_bar_width = Math.floor(now_mp/mp_total*100);
                        $(`#character_battle_${index2} .bar_div.mp .bar`).css("width",mp_bar_width+"%");
                    });
                }
            }
            if(data.icon){
                if(data.icon.add){
                    $.each(data.icon.add, function(target, icon_data) {
                        $.each(icon_data, function(index, icon) {    
                            if($(`#character_battle_${target} .status_icon ul li.status_${icon}`).length == 0){
                                $(`#character_battle_${target} .status_icon ul`).prepend(`<li class="status_${icon}"><img src="/assets/img/level/effect/icon/${icon}.png"></li>`);
                            };
                        });
                    });
                }
                if(data.icon.remove){
                    $.each(data.icon.remove, function(target, icon_data) {
                        $.each(icon_data, function(index, icon) {    
                            $(`#character_battle_${target} .status_icon ul li.status_${icon}`).remove();
                        });
                    });
                }
            }
            data = [];
        }, 100)
    }
    function battle_top_damage_render(){
          $('.battle_child_screen.top_damage li').remove();
          $.each(battle_data.team, function(team, data) {
              get_html = '';
              get_dmg = 0;
              list_damage = [];
              $.each(data.total_damage, function(character, damage) {
                list_damage[character] = [];
                list_damage[character]['id'] = character;
                list_damage[character]['damage'] = damage;
              });
                var list_damage = Object.values(list_damage);
                list_damage.sort(function(a,b) {
                    return b.damage - a.damage;
                });
                if(team == 'monster' || team == 'boss'){
                    target = 'monster';
                }else{
                    target = 'character';
                }
              $.each(list_damage, function(index, value) {
                if(get_dmg == 0){
                    get_dmg = value.damage;
                }
                var bar_damage =  Math.floor((value.damage/get_dmg)*100);
                get_html = get_html+`
                    <li>
                        ${battle_avatar_render(value.id)}
                        <div class="detail">
                            <p class="name"></p>
                            <div class="damage_bar" style="width: ${bar_damage}%;"><p class="damage">${number_format(value.damage)}</p></div>
                        </div>
                        <div class="clear"></div>
                    </li>
                `;
              });
            if(team == token_user || $('.battle_child_screen.top_damage .enemy_team li').length != 0){
                $('.battle_child_screen.top_damage .my_team').html(get_html);
            }else{
                $('.battle_child_screen.top_damage .enemy_team').html(get_html);
            }
          });
    }

    function battle_avatar_render(id){
        var id_split = id.split("_");
        var team = id_split[0];
        var real_id = id_split[1];
        var avatar_html = '';
        var type = getSafe(() => battle_data.team[team].list[id].info.type, "monster");   
        if(team == 'monster' || team == 'boss'){
            avatar_html = `<img onclick="popup_data = {'target':'monster','id':'${real_id}'};popup_load('profile/character');" class="avatar" src="/assets/img/level/monster/${battle_data.team[team].list[id].info.avatar}" />`;
        }else{
            if(type == "pet"){
                avatar_html = `<img onclick="popup_data = {'target':'${type}','id':'${real_id}'};popup_load('profile/character');" class="avatar" src="/assets/img/level/pet/${battle_data.team[team].list[id].info.avatar}" />`;
            }else if(type == "friend"){
                avatar_html = `<img onclick="popup_data = {'target':'${type}','id':'${real_id}'};popup_load('profile/character');" class="avatar" src="/assets/img/level/friend/100/${battle_data.team[team].list[id].info.avatar}.gif" />`;
            }else{
                avatar_html = `<img onclick="popup_data = {'target':'${type}','id':'${real_id}'};popup_load('profile/character');" class="avatar" src="/assets/tmp/avatar/${battle_data.team[team].list[id].info.avatar}" />`;
            }
        }
        return avatar_html;
    }

    function battle_text_damage_render(){
        $('.battle_child_screen.text_damage .list').html('');
        var html = '';
        $.each(battle_data.emulator, function(turn, data) {
                turn = turn.split("_");
                turn = turn[1];
                html += `
                    <div class="item">
                        <h6>Lượt ${turn}</h6>
                    </div>
                `;
            $.each(data, function(index_1, data1) {
                $.each(data1, function(character, battle_data) {
                    if(battle_data.action){
                        if(battle_data.action == "attack"){
                            var text_action = "phát động tấn công.";
                            var text_action_data = " nhận <span class='damage'>:value_0:</span> sát thương."
                            var target = battle_data.list;
                        }else if(battle_data.action == "skill"){ 
                            var text_action = `phát động kỹ năng <span class='skill'>[${json_data.language['skill_'+battle_data.skill]}]</span>.`;
                            var text_action_data = " nhận <span class='damage'>:value_0:</span> sát thương."
                            var target = battle_data.list;
                        }else if(battle_data.action == "heal"){
                            var text_action = `phát động kỹ năng <span class='skill'>[${json_data.language['skill_'+battle_data.skill]}]</span>.`;
                            var text_action_data = " hồi phục <span class='heal'>:value_0:</span> máu."
                            var target = battle_data.list;
                        }else if(battle_data.action == "buff" || battle_data.action == "autobuff"){
                            var text_action = `phát động kỹ năng <span class='skill'>[${json_data.language['skill_'+battle_data.skill]}]</span>.`;
                            var text_action_data = " tăng cường <span class='buff'>:value_0:%</span>."
                            var target = battle_data.effect;
                        }else if(battle_data.action == "debuff" || battle_data.action == "autodebuff"){
                            var text_action = `phát động kỹ năng <span class='skill'>[${json_data.language['skill_'+battle_data.skill]}]</span>.`;
                            var text_action_data = " nguyền rủa <span class='debuff'>:value_0:%</span>."
                            var target = battle_data.effect;
                        }else{
                            var text_action = "";
                            var text_action_data = "";
                            var target = [];
                        }
                        if(battle_data.critical == "yes"){
                            text_action += " <span class='critical'>[Chí Mạng]</span>";
                        }
                        var list = "<ul>";
                        $.each(target, function(character, damage_data) {
                            var hp_now = "";
                            var check_hp = getSafe(() => battle_data.now.hp[character], "no");
                            if(check_hp != "no"){
                                hp_now = " Máu còn lại: <span class='heal'>"+number_format(check_hp)+"</span>.";
                            }
                            var damage_add = getSafe(() => damage_data.add, []);
                            var add_text = "";
                            $.each(damage_add, function(key, value) {
                                value = number_fix(value);
                                value = value > 0 ? "+"+value : value;
                                add_text += ` <span>[${json_data.language['text_battle_damage_add_'+key]}: ${value}%]</span> `;
                            });
                            var damage = getSafe(() => damage_data.damage, damage_data);
                            var text = text_action_data.replace(":value_0:", number_format(damage));
                            list += `<li>${battle_avatar_render(character)} ${text} ${add_text} ${hp_now}</li>`;
                        });
                        list += "</ul>";

                        html += `
                            <div class="item damage_value">
                                ${battle_avatar_render(character)} ${text_action}
                                ${list}
                            </div>
                        `;
                    }
                });
            });
        });
        $('.battle_child_screen.text_damage .list').html(html);
    }
    
    function battle_reward_render(){
        if(battle_data.winner == "monster" || battle_data.winner == "boss"){
            $('.battle_child_screen.battle_reward .image').attr("src","/assets/img/level/icon/lose.png");
            $('.battle_child_screen.battle_reward .image').show();
        }
        if(battle_data.reward){
            var html = "";
            $.each(battle_data.reward, function(character, reward_data) {
                var item_html = "";
                $.each(reward_data, function(key, value) {
                    item_html += item_render(key,value.type,value.amount);
                })
                html += `
                    <li>
                        <img class="avatar" src="/assets/tmp/avatar/${battle_data.team[battle_data.winner].list[character].info.avatar}">
                        <div class="list_item">
                            ${item_html}
                        </div>
                    </li>
                `;
            });
            $(".battle_child_screen.battle_reward ul").html(html);
            $('.battle_child_screen.battle_reward').show();
        }
    }

    function send_battle(){
        frame_close();
        $("#private_chat_open").click();
        setTimeout(() => {
            current_text = $('#chat_add').val();
            current_text = current_text + `<battle>${battle_id}</battle>`;
            $('#chat_add').val(current_text);
        },300)
    }
    battle_emulator_render(battle_id);
</script>