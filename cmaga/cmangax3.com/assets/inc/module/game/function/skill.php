<div class="user_game_module skill">
    <div class="back_dashboard"><i onclick="load_module('content','game/dashboard')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/level/menu/skill.png" />
        <p class="name menu_function_skill"></p>
    </div>
    <div class="menu">
    </div>
    <div id="skill_render_content">
    </div>
</div>
<script>
    page = 1;
    async function content_render() {
        server_data.game_skill = await get_server_data('game_skill');
        var character_skill = await get_character_list('/api/character_list?list=skill&character='+my_character);
        html = '';
        menu_html = '';
        $.each(server_data.game_skill.skill_list, function(element, skill_list) {
            menu_html += `<div class="item" target="skill_${element}"><img style="margin-bottom:8px;" src="/assets/img/level/element/${element}.png"></div>`;
            item_html = '';
            $.each(skill_list, function(skill_rare, skill_list_by_element) {
                $.each(skill_list_by_element, function(index, skill_sign) {
                    var strong = getSafe(() => character_skill[skill_sign].strong, 1);
                    var current_exp = getSafe(() => character_skill[skill_sign].level.exp, 0);
                    var current_level = getSafe(() => character_skill[skill_sign].level.num, 0);
                    var next_exp = getSafe(() => server_data.game_exp.skill[current_level+1], 999999);
                    var css = character_skill[skill_sign] ? '' : 'disable';
                    item_html += `
                        <div class="item">
                            <a class="${css}" style="display: flex;gap: 10px;" onclick="popup_data = {'type':'skill','sign':'${skill_sign}','strong':'${strong}','rare':'${skill_rare}','level':'${current_level}'};popup_load('profile/item')">
                                <div class="image"><img src="/assets/img/level/skill/${skill_sign}.png"/></div>
                                <div style="width:200px;" class="info">
                                    <p class="name skill_rare_${skill_rare}">${json_data.language['skill_'+skill_sign]}</p>
                                    <p class="type">Tầng ${strong} · TT ${current_exp}/${next_exp}</p>
                                </div>
                            </a>
                            <ul>
                                ${point_render(current_level,10)}
                            </ul>
                        </div>
                    `;
                });
            });
            html += `
                <div style="display: none;" class="skill_${element} child_module">
                    <div class="list master">
                        ${item_html}
                    </div>
                </div>
            `;
        });
        $('.user_game_module .menu').html(menu_html);
        $("#skill_render_content").html(html);
        $(".user_game_module .menu .item").click(function() {
            $(".user_game_module .menu .item").removeClass('active');
            $(this).addClass('active');
            $(".user_game_module .child_module").hide();
            $(".user_game_module .child_module."+$(this).attr('target')).show();
        });
        $('.user_game_module .menu .item[target=skill_metal]').click();
    }

    content_render();
    language_render(['menu_function_skill']);
</script>