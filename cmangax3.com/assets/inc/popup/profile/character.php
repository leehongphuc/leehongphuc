<div style="width: 500px;" class="popup_module center_div character_profile">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div id="character_profile">

    </div>
</div>
<script>
    character_profile_id = popup_data.id;
    async function popup_character_profile(){
        percent_stats = ["critical","critical_damage","m_def", "p_def", "avoid","skill_atk","skill_def"];
        if(popup_data.target == 'character'){
            load_module('character_profile','game/data/character','no');
        }else if(popup_data.target == 'pet'){
            load_module('character_profile','game/data/pet','no');
        }else if(popup_data.target == 'friend'){
            load_module('character_profile','game/data/friend','no');
        }else{
            var character_profile_data = await get_data_by_url('/api/get_data_by_id?table=game_monster&data=data&id='+character_profile_id);
            character_profile_data = JSON.parse(character_profile_data.data);
            percent_stats = ["critical","critical_damage","m_def", "p_def", "avoid","skill_atk","skill_def"];
            var stats_html = "";
            $.each(character_profile_data.stats, function(key, value) {
                current_stats = value.total;
                if(percent_stats.includes(key)){
                    current_stats = current_stats+"%";
                }
                stats_html += `
                    <tr>
                        <td>${json_data.language['stats_short_'+key]}</td>
                        <td>${current_stats}</td>
                        <td></td>
                    </tr>`
                ;
            });
            var html = `
                <div class="user_game_module character">
                    <div class="avatar">
                        <img class="image" src="/assets/img/level/monster/${character_profile_data.info.sign}.png" />
                        <p class="name">${json_data.language['monster_'+character_profile_data.info.sign]}</p>
                        <div class="level bar_div">${level_render(character_profile_data.info.level)}</div>
                        <div class="skill">
                            <p class="title">${json_data.language["skill_"+character_profile_data.equipment.skill.sign]}</p>
                            <p class="note">${get_skill_detail(character_profile_data.equipment.skill.sign,0,1,1)}</p>
                        </div>
                    </div>
                    <div class="character_stats child_module">
                        <table>
                            ${stats_html}
                        </table>
                    </div>
                </div>
            `;
            $('#character_profile').html(html);
        }
    }
    popup_character_profile();
</script> 