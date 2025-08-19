<div class="popup_module center_important game_market_filter">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="input_screen">
        <ul class="form">
            <li id="market_filter_status">
                <p class="label text_status"></p>
                <select>
                    <option value="all" class="text_all"></option>
                    <option value="selling" class="text_market_status_selling"></option>
                    <option value="sold" class="text_market_status_sold"></option>
                    <option value="cancel" class="text_market_status_cancel"></option>
                </select>
            </li>
            <li id="market_filter_sort">
                <p class="label text_filter_sort"></p>
                <select>
                    <option value="new" class="text_filter_sort_time"></option>
                    <option value="lowest" class="text_filter_sort_lowest"></option>
                    <option value="highest" class="text_filter_sort_highest"></option>
                </select>
            </li>
            <li id="market_filter_type">
                <p class="label text_market_category"></p>
                <select onchange="market_filter_special_type(this)">
                    <option value="all" class="text_all"></option>
                    <option value="equipment" class="text_equipment"></option>
                    <option value="item" class="text_bag_item"></option>
                </select>
            </li>
            <li style="display: none;" class="market_equipment market_sub" id="market_filter_special_type">
                <p class="label text_market_child_category"></p>
                <select onchange="market_filter_sepcial_data(this);">
                </select>
            </li>
            <li style="display: none;" class="market_equipment market_sub" id="market_filter_special_level">
                <p class="label text_level"></p>
                <select>
                </select>
            </li>
            <li class="market_special hide" id="market_filter_special_data">
            </li>
            <li style="display: none;" class="market_item market_sub" id="market_filter_sign">
                <p class="label text_market_filter_name"></p>
                <div class="tags_list overwrite">
                    <div class="tags_module">
                        <input onkeyup="album_tags_search(this,1)" onclick="$('#album_tags_name').html('');album_tags_search(this,2)"/>
                        <div id="market_filter_list" class="tags_create">
                            
                        </div>
                        <div onclick="$(this).hide();$(this).parent('.tags_module').children('.tags_create').hide();" class="tags_icon_close"><i class="far fa-times"></i></div>
                    </div>
                    <div id="album_tags_name" class="tags_show list_fr">
                    </div>
                </div>
            </li>
            <li class="switch_slider">
                <table>
                    <tr>
                        <td class="text_market_my_item"></td>
                        <td>    
                            <label class="switch">
                                <input type="checkbox" id="market_filter_selling" name="market_filter_selling"/>
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                </table>
            </li>
        </ul>
    </div>
    <div class="button">
        <button style="width:100%;margin:0;" onclick="game_market_filter(this);" class="text_button_filter"></button>
        <button class="button_no text_market_quick_buy_button" style="width:100%;margin:20px 0 0;" onclick="popup_load('game/market/quick_buy')"></button>
    </div>
</div>
<script>
    market_item_list= [
        "item_box_treasure_1",
        "item_box_treasure_2",
        "item_box_treasure_3",
        "item_box_treasure_4",
        "item_box_treasure_5",
        "item_box_treasure_6",
        "item_box_treasure_7",
        "item_box_treasure_8",
        "item_box_weapon_1",
        "item_box_weapon_2",
        "item_box_weapon_3",
        "item_box_weapon_4",
        "item_box_weapon_5",
        "item_box_weapon_6",
        "item_box_weapon_7",
        "item_box_weapon_8",
        "item_box_armor_1",
        "item_box_armor_2",
        "item_box_armor_3",
        "item_box_armor_4",
        "item_box_armor_5",
        "item_box_armor_6",
        "item_box_armor_7",
        "item_box_armor_8",
        "item_box_accessory_1",
        "item_box_accessory_2",
        "item_box_accessory_3",
        "item_box_accessory_4",
        "item_box_accessory_5",
        "item_box_accessory_6",
        "item_box_accessory_7",
        "item_box_accessory_8",
        "item_potion_1",
        "item_potion_2",
        "item_potion_3",
        "item_potion_4",
        "item_potion_5",
        "item_potion_6",
        "item_potion_7",
        "item_potion_8",
        "item_skill_1_1",
        "item_skill_1_2",
        "item_skill_1_3",
        "item_skill_1_4",
        "item_skill_1_5",
        "item_skill_2_1",
        "item_skill_2_2",
        "item_skill_2_3",
        "item_skill_2_4",
        "item_skill_2_5",
        "item_skill_3_1",
        "item_skill_3_2",
        "item_skill_3_3",
        "item_skill_3_4",
        "item_skill_3_5",
        "item_skill_4_1",
        "item_skill_4_2",
        "item_skill_4_3",
        "item_skill_4_4",
        "item_skill_4_5",
        "item_medicinal_exp_1",
        "item_medicinal_exp_2",
        "item_medicinal_exp_3",
        "item_medicinal_exp_4",
        "item_medicinal_exp_5",
        "item_medicinal_exp_6",
        "item_medicinal_exp_7",
        "item_medicinal_exp_8",
        "item_medicinal_exp_9",
        "item_medicinal_exp_10",
        "item_medicinal_upgrade_1",
        "item_medicinal_upgrade_2",
        "item_medicinal_upgrade_3",
        "item_medicinal_upgrade_4",
        "item_medicinal_upgrade_5",
        "item_medicinal_upgrade_6",
        "item_medicinal_upgrade_7",
        "item_medicinal_upgrade_8",
        "item_medicinal_upgrade_9",
        "item_medicinal_point_plus",
        "material_ore_weapon_1",
        "material_ore_weapon_2",
        "material_ore_weapon_3",
        "material_ore_weapon_4",
        "material_ore_weapon_5",
        "material_ore_weapon_6",
        "material_ore_weapon_7",
        "material_ore_weapon_8",
        "material_ore_armor_1",
        "material_ore_armor_2",
        "material_ore_armor_3",
        "material_ore_armor_4",
        "material_ore_armor_5",
        "material_ore_armor_6",
        "material_ore_armor_7",
        "material_ore_armor_8",
        "material_ore_accessory_1",
        "material_ore_accessory_2",
        "material_ore_accessory_3",
        "material_ore_accessory_4",
        "material_ore_accessory_5",
        "material_ore_accessory_6",
        "material_ore_accessory_7",
        "material_ore_accessory_8",
        "material_herb_1",
        "material_herb_2",
        "material_herb_3",
        "material_herb_4",
        "material_herb_5",
        "material_herb_6",
        "material_herb_7",
        "material_herb_8",
        "material_herb_upgrade_1",
        "material_herb_upgrade_2",
        "material_herb_upgrade_3",
        "material_herb_upgrade_4",
        "material_herb_upgrade_5",
        "material_herb_upgrade_6",
        "material_herb_upgrade_7",
        "material_herb_upgrade_8",
        "material_death_soul_1",
        "material_death_soul_2",
        "material_death_soul_3",
        "material_death_soul_4",
        "material_death_soul_5",
        "material_death_soul_6",
        "material_death_soul_7",
        "material_death_soul_8",
        "material_guild_ore",
        "material_equipment_upgrade_1",
        "material_equipment_upgrade_2",
        "material_equipment_upgrade_3",
        "material_add_option",
        "material_job_exp_1",
        "material_job_exp_2",
        "material_job_exp_3",
        "material_job_exp_4",
        "item_medicinal_upgrade_king",
        "item_medicinal_talent_plus",
        "material_guild_quest_bar",
        "material_guild_quest_ore",
        "material_guild_quest_cloth",
        "material_guild_quest_wood",
        "material_guild_quest_fish",
        "material_guild_quest_vegetable",
        "material_guild_quest_meat",
        "material_guild_quest_seed",
        "item_guild_boss_1",
        "item_guild_boss_2",
        "item_box_book_1",
        "item_box_book_2",
        "item_box_book_3",
        "item_medicinal_level_4",
        "item_medicinal_level_5",
        "item_medicinal_level_6",
        "material_egg_normal",
        "material_egg_rare",
        "material_egg_legendary",
        "item_pet_exp_chest",
        "material_pet_evolve_1",
        "material_pet_evolve_2",
        "material_pet_evolve_3",
        "material_pet_evolve_4",
        "material_pet_skill_1",
        "material_pet_skill_2",
        "material_pet_skill_3",
        "material_pet_skill_4",
        "item_box_pet_equipment_1",
        "item_box_pet_equipment_2",
        "item_box_pet_equipment_3",
        "item_box_pet_equipment_4",
        "item_box_pet_equipment_5",
        "item_box_pet_equipment_6",
        "item_box_pet_equipment_7",
        "item_box_pet_equipment_8",
        "material_egg_mystic",
        "item_pet_heart_bag",
        "item_wanted_order",
        "material_egg_super_fragment",
        "material_soul_blossom"
    ];

    function popup_game_marker_filter(){
        $('#market_filter_status select').val(market_filter.status);
        $('#market_filter_type select').val(market_filter.type);
        market_filter_special_type($('#market_filter_type select'));
        $('#market_filter_special_type select').val(market_filter.special_type);
        $('#market_filter_special_level select').val(market_filter.special_level);
        $('#market_filter_sort select').val(market_filter.sort);
        $('.item_'+market_filter.sign).click();
        market_filter_sepcial_data($('#market_filter_special_type select '));
    }
    function market_filter_special_type(e){
        var type = $(e).val();
        $('.game_market_filter .market_sub').hide();
        if(type == "equipment"){
            sub_list = ["weapon","armor","helmet","gloves","boots","ring","amulet","belt","pedant","treasure","book","pet_head","pet_body_top","pet_body_bottom","pet_foot_right","pet_foot_left","pet_tail","pet_hand_left","pet_hand_right"];
            var html = `<option value="all">${language_text('text_all')}</option>`;
            $.each(sub_list, function(index, item) {
                html += `<option value="${item}">${json_data.language[type+'_'+item]}</option>`;
            });
            $('#market_filter_special_type select').html(html);
            $('.game_market_filter .market_equipment').show();
            var html = "";
            for(i=1;i<=10;i++){
                html += `<option value="${i}">${json_data.language['level_'+i]}</option>`;
            }
            $('#market_filter_special_level select').html(html);
        }else if(type == "item"){
            var html = "";
            $.each(market_item_list, function(index, value) {
                html = html + `<div onclick="tags_list_select(this);market_filter.sign = '${value}';" class="tags_span item_${value}">${json_data.language[value]}</div>`;
            });
            $("#market_filter_list").html(html);
            $('.game_market_filter .market_item').show();
        }
    }
    function game_market_filter() {
        market_filter.type = $('#market_filter_type select option:selected').val();
        market_filter.sort = $('#market_filter_sort select option:selected').val();
        market_filter.special_type = $('#market_filter_special_type select option').length != 0 ? $('#market_filter_special_type select option:selected').val() : 'all';
        market_filter.special_level = $('#market_filter_special_level select option').length != 0 ? $('#market_filter_special_level select option:selected').val() : 0;
        market_filter.status = $('#market_filter_status select option:selected').val();
        market_filter.special_data = "";
        $.each($('#market_filter_special_data select'), function(index, value) {
            market_filter.special_data += $(value).val() + ",";
        });
        if($("#market_filter_selling").is(":checked")){
            market_filter.owner = my_character;
        }else{
            market_filter.owner = 0;
        }
        page = 1;
        popup_close();
        content_render();
    }

    function market_filter_sepcial_data(e){
        var target = $(e).val();
        if(target == "book"){
            var weapon_list = ["battle_axe","bow","claw","crescents","dual_sword","fan","flute","harp","lute","medium_scimitar","mini_scimitar","moon_blade","pen","robot","rope_whip","scimitar","spear","stick","sword","umbrella","zither"];
            var weapon_html = "<option value=''>Tất cả</option>";
            $.each(weapon_list, function(index, value) {
                if(market_filter.special_data.indexOf(value) != -1){
                    weapon_html = weapon_html + `<option value="${value}" selected>${json_data.language["weapon_"+value]}</option>`;
                }else {
                    weapon_html = weapon_html + `<option value="${value}">${json_data.language["weapon_"+value]}</option>`;
                }
            });
            var element_list = ['metal','natural','fire','water','earth','wind','ice','thunder','light','dark'];
            var element_html = "<option value=''>Tất cả</option>";
            $.each(element_list, function(index, value) {
                if(market_filter.special_data.indexOf(value) != -1){
                    element_html = element_html + `<option value="${value}" selected>${json_data.language["element_"+value]}</option>`;
                }else {
                    element_html = element_html + `<option value="${value}">${json_data.language["element_"+value]}</option>`;
                }
            });

            $('#market_filter_special_data').html(
                `
                    <p class="label">Hỗ trợ Vũ Khí</p>
                    <select style="margin-bottom:20px">
                        ${weapon_html}
                    </select>
                    <p class="label">Hỗ trợ Kỹ Năng</p>
                    <select>
                        ${element_html}
                    </select>
                `
            );
            $('#market_filter_special_data').show();
        }else{
            $('#market_filter_special_data').hide();
        }
    }
    language_render(['text_status','text_market_status_selling','text_market_status_sold','text_market_status_cancel','text_filter_sort','text_filter_sort_time','text_filter_sort_lowest','text_filter_sort_highest','text_market_category','text_equipment','text_bag_item','text_market_child_category','text_level','text_all','text_market_filter_name','text_market_my_item','text_button_filter','text_market_quick_buy_button']);
    popup_game_marker_filter()
</script>