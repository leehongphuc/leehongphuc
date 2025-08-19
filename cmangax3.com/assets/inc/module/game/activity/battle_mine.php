<div class="user_game_module battle_champion battle_mine">
    <div class="back_dashboard"><i onclick="battle_firebase_disconnect();count_down_second = 0;load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="button_right button_style">
        <button style="display: none;" class="button_yes button_champion_top" onclick="popup_data= {'type':'battle_champion_team','limit':100};popup_load('game/score')"><i class="fas fa-trophy"></i> <span class="text_button_top"></span></button>
    </div>
    <div onclick="popup_load('game/buy/energy_mine')"  class="current_energy currency_div">
        <div class="item">
            <img class="icon" src="/assets/img/level/item/energy_mine.png">
            <p class="num"></p>
            <i class="fas fa-plus"></i>
            <div style="display: none;" end="" class="next_time">00:00</div>
        </div>
    </div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/battle_mine.png" />
        <p class="name"></p>
        <div style="display:none;" class="time_count_down" style="margin-top: 10px;" time="">00:00</div>
    </div>
    <div class="battle_content">
        <div onclick="popup_load('game/battle/battle_mine_area')" class="area_detail">
            <div class="cp">
                <p class="name">Mỏ cá nhân</p>
                <p class="value"></p>
            </div>
            <div class="redirect">
                <i class="center_div fa-solid fa-chevron-right"></i>
            </div>
        </div>
        <div style="display: none;" class="area_list">
        </div>
        <div class="area_private">
            <div class="mine_upgrade_list">
                <div onclick="battle_mine_upgrade(this)" class="mine_upgrade upgrade">
                    <div class="image">
                        <img src="/assets/img/level/currency/mine_ore.png">
                    </div>
                    <div class="detail">
                        <p class="progress"><span class="current"></span> / <span class="need"></span></p>
                        <p class="text">Nâng cấp</p>
                    </div>
                </div>
                <div onclick="popup_load('game/battle/battle_mine_protect')" class="mine_upgrade protect">
                    <div class="image">
                        <img src="/assets/img/level/item/mine_protect.png">
                    </div>
                    <div class="detail">
                        <p class="progress"><span class="current">0</span></p>
                        <p class="text">Bảo vệ</p>
                    </div>
                </div>
                <div onclick="popup_load('game/battle/battle_mine_bonus')" class="mine_upgrade bonus">
                    <div class="image">
                        <img src="/assets/img/transparent.png">
                    </div>
                    <div class="detail">
                        <p class="progress"><span class="current">0</span></p>
                        <p class="text">Đổi</p>
                    </div>
                </div>
            </div>
            <img class="image" src="/assets/img/level/icon/mine/1.png" alt="">
            <p class="name">Cấp <span class="mine_level"></span></p>
            <div class="reward_list main_reward">
            </div>
            <div class="area_take">
                <div class="area_take_item private">
                    <p class="title"></p>
                    <div class="reward_list">
                    </div>
                    <button target="private" onclick="battle_mine_take_reward(this)">Nhận</button>
                </div>
                <div class="area_take_item public hide">
                    <p class="title"></p>
                    <div class="reward_list">
                    </div>
                    <button target="public" onclick="battle_mine_take_reward(this)" class="disabled">Nhận</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    battle_map = {
        "area": 0,
        "list": []
    }
    activity_position_data = {
        "activity": "battle_mine"
    };
    FirebaseBattleApp = "";
    isProtect = false;
    async function character_energy_reload() {
        my_character_energy = await get_data_by_url('/api/character_energy_mine?character=' + my_character);
        $(".user_game_module .current_energy .num").html(my_character_energy.current);
        $(".user_game_module .current_energy .time_count_down").attr("end", "");
        if (my_character_energy.current != 10) {
            var time_to_reload = 7200 - (server_time - my_character_energy.time);
            if (time_to_reload > 0) {
                $(".user_game_module .current_energy .next_time").addClass('time_count_down');
                $(".user_game_module .current_energy .next_time").attr("time", server_time + time_to_reload);
                $(".user_game_module .current_energy .next_time").attr("end", "character_energy_reload");
            }
        }
    }
    async function content_render() {
        character_energy_reload();
        $('.user_game_module .avatar .name').html(json_data.language['menu_activity_battle_mine']);
        server_data.battle_mine = await get_server_data('battle_mine');
        if (battle_map.area != 0) {
            $('.battle_mine .battle_content .area_detail .name').html("Tầng " + battle_map.area + " : ");
            $('.battle_mine .area_detail .value').html("+" + server_data.battle_mine.setting.area[battle_map.area].bonus + "%");
            var mine_list = await get_data_by_url(`/api/score_list?type=battle_mine&area=${battle_map.area}`);
            $.each(mine_list, function(index, value) {
                battle_map.list[index] = json_convert(value.data);
                battle_map.list[index].id_score = value.id_score;
                battle_map.list[index].target = value.target;

            });
            battle_map.list.sort(function(a, b) {
                return b.rare - a.rare;
            });

            $('.battle_mine .area_list').empty();
            var html = ``;
            $.each(battle_map.list, function(index, mine) {
                let bonusText = "+" + server_data.battle_mine.setting.rare[mine.rare].bonus + "%";
                let mineName = language_text('text_battle_mine_rare_' + mine.rare);
                let miner = ``;
                if (mine.miner.info) {
                    miner = `
                        <img class="player mining" src="/assets/tmp/avatar/${mine.miner.info.avatar}" alt="">
                        <img class="effect" src="/assets/img/level/icon/mine/effect.png" alt="">
                    `;
                    bonusText = mine.miner.times + " phút";
                    var data_reward = mine.miner.reward;
                } else {
                    let gold = server_data.battle_mine.gold * (100 + server_data.battle_mine.setting.area[battle_map.area].bonus + server_data.battle_mine.setting.rare[mine.rare].bonus) / 100;
                    let ore = server_data.battle_mine.ore * (100 + server_data.battle_mine.setting.area[battle_map.area].bonus + server_data.battle_mine.setting.rare[mine.rare].bonus) / 100;
                    var data_reward = {};
                    data_reward['gold'] = {
                        "type": "currency",
                        "amount": gold
                    };
                    data_reward['mine_ore'] = {
                        "type": "currency",
                        "amount": ore
                    };
                }
                let reward_html = ``;
                $.each(data_reward, function(key, value) {
                    reward_html += `
                        <div class="item">
                            <img src="/assets/img/level/${value.type}/${key}.png" alt="">
                            <p>${value.amount}</p>
                        </div>
                    `;
                });
                var protect_time = getSafe(() => mine.miner.protect, 0);
                var protect_image = ``;
                var protect_text = ``;  
                if(protect_time > server_time){
                    protect_image = `<img class="protect" src="/assets/img/level/icon/mine/protect.png" alt="">`;
                    protect_text = `<i class="fa-solid fa-shield-cat"></i> <span time="${protect_time}" class="time_count_down protect"></span>`;
                }
                html += `
                    <div onclick="battle_map.target=${index};battle_map.mine_id=${mine.id_score};popup_load('game/battle/battle_mine_detail')" class="area_item">
                        <div class="image">
                            ${miner}
                            <img class="mine" src="/assets/img/level/icon/mine/${mine.rare}.png" alt="">
                            ${protect_image}
                        </div>
                        <div class="info">
                            <p class="name rare_${mine.rare}">${mineName}</p>
                            <p class="text">${bonusText} ${protect_text}</p>
                        </div>
                        <div class="reward_list">
                            ${reward_html}
                        </div>
                    </div>
                `;
            });
            $('.battle_mine .area_list').html(html);
            $('.battle_mine .area_private').hide();
            $('.battle_mine .area_list').show();
        } else {
            $(`.battle_mine .area_private .mine_upgrade.protect`).removeClass('active');
            var current_ore = await get_data_by_url(`/api/character_count?type=currency&sign=mine_ore&character=${my_character}&v=${server_time}`);
            var current_protect = await get_data_by_url(`/api/character_count?type=item&sign=mine_protect&character=${my_character}&v=${server_time}`);
            $(`.battle_mine .area_private .mine_upgrade.upgrade .detail .current`).html(number_format(current_ore));
            $(`.battle_mine .area_private .mine_upgrade.protect .detail .current`).html(number_format(current_protect));
            battle_mine_history = await get_data_by_url('/api/get_data_by_id?table=game_character&data=other&id=' + my_character + '&v=' + server_time);
            battle_mine_history.other = JSON.parse(battle_mine_history.other);
            var bonus_reward = getSafe(() => battle_mine_history.other.battle_mine.private.bonus, "friend_exp");
            private_mine_data = getSafe(() => battle_mine_history.other.battle_mine.private, {});
            var mine_level = getSafe(() => private_mine_data.level, 1);
            var need_ore = server_data.battle_mine.private[mine_level].upgrade;
            $(`.battle_mine .area_private .mine_upgrade.upgrade .detail .need`).html(number_format(need_ore));
            $('.battle_mine .area_private .mine_level').html(mine_level);
            if (current_ore >= need_ore) {
                $('.battle_mine .area_private .mine_upgrade.upgrade').addClass('active');
            } else {
                $('.battle_mine .area_private .mine_upgrade.upgrade').removeClass('active');
            }
            $('.battle_mine .area_private .mine_upgrade.bonus img').attr('src', `/assets/img/level/currency/${bonus_reward}.png`);
            $('.battle_mine .area_private .mine_upgrade.bonus .progress .current').html(number_format(server_data.battle_mine.private[mine_level].bonus[bonus_reward]));
            var reward_html = `
                <div class="item">
                    <img src="/assets/img/level/currency/gold.png" alt="">
                    <p>${number_fix(server_data.battle_mine.private[mine_level].gold,4)}</p>
                </div>
                <div class="item">
                    <img src="/assets/img/level/currency/${bonus_reward}.png" alt="">
                    <p>${number_fix(server_data.battle_mine.private[mine_level].bonus[bonus_reward],4)}</p>
                </div>
            `;

            $('.battle_mine .area_private .reward_list.main_reward').html(reward_html);
            if (private_mine_data) {
                $('.battle_mine .area_private .area_take_item.private .title').html(`Mỏ cá nhân: <span>${private_mine_data.times} phút</span>`);
                let reward_html = ``;
                $.each(private_mine_data.reward, function(key, value) {
                    reward_html += `
                        <div class="item">
                            <img src="/assets/img/level/${value.type}/${key}.png" alt="">
                            <p>${number_fix(value.amount,4)}</p>
                        </div>
                    `;
                });
                $('.battle_mine .area_private .area_take_item.private .reward_list').html(reward_html);
            }
            var mine_public = await get_data_by_url(`/api/score_list?type=battle_mine_target&target=${my_character}`);
            if (mine_public.length > 0) {
                $.each(mine_public, function(index, value) {
                    mine_public_data = json_convert(value.data);
                    $('.battle_mine .area_private .area_take_item.public .title').html(`Tầng ${mine_public_data.area} : <span>${mine_public_data.miner.times} phút</span> <span class="protect"></span>`);
                    let reward_html = ``;
                    $.each(mine_public_data.miner.reward, function(key, value) {
                        reward_html += `
                            <div class="item">
                                <img src="/assets/img/level/${value.type}/${key}.png" alt="">
                                <p>${number_fix(value.amount,4)}</p>
                            </div>
                        `;
                    });
                    $('.battle_mine .area_private .area_take_item.public .reward_list').html(reward_html);
                    $('.battle_mine .area_private .area_take_item.public').removeClass('hide');
                    if (mine_public_data.miner.times >= 60) {
                        $('.battle_mine .area_private .area_take_item.public button').removeClass('disabled');
                    }
                    var protect_time = getSafe(() => mine_public_data.miner.protect, 0);
                    if(protect_time > server_time){
                        isProtect = true;
                        $('.battle_mine .area_private .area_take_item.public .protect').html(`<i class="fa-solid fa-shield-cat"></i> ${time_format(protect_time,'yes','yes')}`);
                    }
                });
                if (current_protect > 0) {
                    $(`.battle_mine .area_private .mine_upgrade.protect`).addClass('active');
                } 
            }
            $('.battle_mine .area_list').hide();
            $('.battle_mine .area_private').show();
        }
    }

    function battle_mine_private_check() {
        $.post("../assets/ajax/character_activity.php", {
            action: "battle_mine_private",
        }).done(function(data) {
            content_render();
        });
    }

    function battle_mine_upgrade(e) {
        if (private_mine_data.times < 10) {
            $(e).prop('disabled', true);
            $.post("../assets/ajax/character_activity.php", {
                action: "battle_mine_upgrade",
            }).done(function(data) {
                content_render();
                $(e).prop('disabled', false);
            });
        } else {
            alertify.error("Bạn cần thu thập Mỏ trước khi nâng cấp");
        }
    }

    function battle_mine_take_reward(e) {
        var target = $(e).attr('target');
        $(e).prop('disabled', true);
        $.post("../assets/ajax/character_activity.php", {
            action: "battle_mine_take_reward",
            target: target,
        }).done(function(data) {
            $('#result').html(data);
            $(e).prop('disabled', false);
        });
    }

    function battle_firebase_connect() {
        if (FirebaseBattleApp == '') {
            FirebaseBattleApp = "yes";
            var load_battle = 1;
            FireBaseApp.child(`Activity/battle_mine/`).on('value', function(snap) {
                if (load_battle != 1) {
                    var data = snap.val();
                    if (data.target == 'reload' && data.area == battle_map.area) {
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
            FireBaseApp.child(`Activity/battle_mine/`).off('value');
        }
    }
    battle_mine_private_check();
    battle_firebase_connect();
    language_render(['text_button_guide']);
    $('#party_code').attr('placeholder', language_text('text_party_code'));
</script>