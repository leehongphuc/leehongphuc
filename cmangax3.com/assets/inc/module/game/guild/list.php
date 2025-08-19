<div class="user_game_module guild_building legendary">
    <div class="back_dashboard"><i onclick="load_module('content','game/function/guild')" class="fas fa-chevron-square-left"></i></div>
    <div class="avatar">
        <img class="image" src="/assets/img/transparent.png" />
        <p class="name"></p>
    </div>
    <div class="top guild_list child_module">
        <ul>
        </ul>
    </div>
</div>
<script>
    page = 1;
    async function content_render(){
        var level = getSafe(() => guild_data.building.member, 1);
        $('.guild_building .avatar .image').attr('src',`assets/img/level/menu/guild.png`);
        $('.guild_building .avatar .name').html(json_data.language['guild_list']);

        var html = '';
        var guild_list = await get_data_by_url('/api/game_guild_list');
        $.each(guild_list,function(key,value){
            var guild_data = JSON.parse(value.data);
            html+=`
                <li>
                    <div onclick="popup_data = {'guild_id':'${guild_data.id}'};popup_load('profile/guild');" class="info">
                        <img src="/assets/tmp/game/guild/${guild_data.avatar}">
                        <div class="name">${guild_data.name}</div>
                    </div>
                    <div class="score"><img class="item_icon" src="/assets/img/level/icon/guild_level.png"> ${json_data.language['level_'+guild_data.building.home]} <img class="item_icon" src="/assets/img/level/icon/guild_member.png"> ${guild_data.member.current}/${guild_data.member.max}</div>
                </li>                     
            `;
        });
        $('.guild_building .top.guild_list ul').html(html);
    }

    $(".user_game_module .menu .item").click(function() {
        $(".user_game_module .menu .item").removeClass('active');
        $(this).addClass('active');
        $(".user_game_module .child_module").hide();
        $(".user_game_module .child_module."+$(this).attr('target')).show();
    });

    content_render();
</script>