<div class="popup_module center_div equipment_select">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="preview">
        <p class="name"></p>
        <p class="quality"></p>
        <div class="other"></div>
        <div class="stats"></div>
    </div>
    <div class="equipment_list"></div>
    <div style="display: none;" class="button">
        <button style="width: 100%;margin: 10px 0;" onclick="equipment_select_confirm(this)" class="text_wearing"></button>
    </div>
</div>
<script>
    async function popup_render(){
        await get_server_data('game_battle');
        var html = '';
        if(popup_data.type == "skill"){
            equipment_list = await get_character_list('/api/character_list?list='+popup_data.type+'&character='+my_character);
            $.each(equipment_list,function(k,v){
                html += `
                    <div target="${k}" onclick="equipment_select(this)" class="item equipment_icon skill_rare_${v.rare}">
                        <img src="/assets/img/level/skill/${k}.png">
                    </div>
                `;
            });
        }else if(popup_data.type == "pet"){
            equipment_list = await get_character_list('/api/character_list?list=pet&character='+my_character);
            var list = Object.values(equipment_list);
            list.sort(function(a,b) {
                return b.evolve - a.evolve;
            });
            $.each(list,function(k,v){
                if(v.status == "free"){
                    var quality = quality_convert(v.quality, 'pet');
                    html += `
                        <div target="${v.id}" onclick="equipment_select(this)" class="item equipment_icon border_level_${quality.num}">
                            <img src="/assets/img/level/pet/${v.sign}_${v.evolve}.png">
                        </div>
                    `;
                }
            });
        }else if(popup_data.type == "friend"){
            equipment_list = await get_character_list('/api/character_list?list=friend&character='+my_character);
            var list = Object.values(equipment_list);
            list.sort(function(a,b) {
                return b.cp - a.cp;
            });
            $.each(list,function(k,v){
                if(v.status == "free"){
                    html += `
                        <div target="${v.id}" onclick="equipment_select(this)" class="item equipment_icon skill_rare_${v.tier}">
                            <img src="/assets/img/level/friend/avatar/${v.sign}.png">
                        </div>
                    `;
                }
            });
        }else if(popup_data.type == "potion"){
            equipment_list = await get_character_list('/api/character_list?list=item&character='+my_character);
            $.each(equipment_list,function(k,v){
                if(server_data.game_battle.potion[k] && v.amount > 0){
                    html += `
                        <div target="${k}" onclick="equipment_select(this)" class="item equipment_icon">
                            <img src="/assets/img/level/item/${k}.png">
                        </div>
                    `;
                }
            });
        }else{
            equipment_list = await get_character_list('/api/character_equipment_list?type='+popup_data.type+'&character='+my_character);
            $.each(equipment_list,function(k,v){
                if(v.info.status == "free"){
                    html += `
                        <div target="${k}" onclick="equipment_select(this)" class="item equipment_icon border_level_${v.info.level}">
                            <img src="/assets/img/level/equipment/${v.info.type}/${v.info.sign}_${v.info.level}.png">
                        </div>
                    `;
                }
            });
        }
        $('.equipment_select .equipment_list').html(html);
    }
    function equipment_select(e){
        var target = $(e).attr('target');
        $('.equipment_select .equipment_list .item').removeClass('active');
        $(e).addClass('active');
        $('.equipment_select .preview .name').attr('class','name');
        if(popup_data.type == "skill"){
            $('.equipment_select .preview .name').text(json_data.language['skill_'+target]);
            $('.equipment_select .preview .name').addClass('skill_rare_'+equipment_list[target].rare);
            $('.equipment_select .preview .quality').html(get_skill_detail(target,equipment_list[target].level.num,equipment_list[target].strong,equipment_list[target].rare));
        }else if(popup_data.type == "pet"){
            $('.equipment_select .preview .name').text(json_data.language['pet_'+equipment_list[target].sign]);
            var quality = quality_convert(equipment_list[target].quality, 'pet');
            var main_level = Math.floor(equipment_list[target].level.num/10);
            var sub_level = equipment_list[target].level.num%10;
            var text = json_data.language['character_level_'+main_level]+' '+json_data.language['character_mini_level_'+sub_level];
            $('.equipment_select .preview .quality').html(`${text} · <span class="level_${quality.num}">${quality.name}</span>`);
        }else if(popup_data.type == "friend"){
            $('.equipment_select .preview .name').html(`<span class="skill_rare_${equipment_list[target].tier}">${json_data.language['friend_name_'+equipment_list[target].sign]}</span>`);
            var main_level = Math.floor(equipment_list[target].level.num/10);
            var sub_level = equipment_list[target].level.num%10;
            var text = json_data.language['character_level_'+main_level]+' '+json_data.language['character_mini_level_'+sub_level];
            $('.equipment_select .preview .quality').html(`${text}`);
        }else if(popup_data.type == "potion"){
            $('.equipment_select .preview .name').text(json_data.language['item_'+target]);
            $('.equipment_select .preview .quality').html(json_data.language['item_detail_'+target]);
        }else if(popup_data.type == "book"){
            $('.equipment_select .preview .name').text(json_data.language[equipment_list[target].info.type+'_'+equipment_list[target].info.sign+'_'+equipment_list[target].info.level]);
            $('.equipment_select .preview .name').addClass('level_'+equipment_list[target].info.level);
            var book_note = json_data.language['book_main_stats'];
            book_note = book_note.replace(':value_0:',equipment_list[target].data.main.value);
            book_note = book_note.replace(':value_1:',json_data.language['stats_'+equipment_list[target].data.main.stats]);
            $('.equipment_select .preview .quality').text(book_note);
            html = '';
            $.each(equipment_list[target].data.option, function(key, option_data) {
                var option_text = json_data.language['book_option_'+key].replace(':value_0:',option_data.value);
                if(key.indexOf('weapon') !== -1){
                    option_text = option_text.replace(':value_1:',json_data.language['weapon_'+option_data.target]);
                }else if(key.indexOf('skill') !== -1){
                    option_text = option_text.replace(':value_1:',json_data.language['element_'+option_data.target]);
                }
                html += `<div style="font-size:12px;" class="item"><p class="label option">${option_text}</p></div>`;
            })
            $('.equipment_select .preview .other').html(html);
        }else if(popup_data.type == "tactical"){
            $('.equipment_select .preview .name').text(json_data.language[equipment_list[target].info.type+'_'+equipment_list[target].info.sign+'_'+equipment_list[target].info.level]);
            $('.equipment_select .preview .name').addClass('level_'+equipment_list[target].info.level);
            html = '';
            var rate = equipment_list[target].data.main.rate;
            $.each(equipment_list[target].data.element, function(target, element) {
                html += `<div style="font-size:12px;" class="item"><p class="label option">Tăng ${rate}% chúc phúc khi có ${json_data.language['tactical_require_'+target]} hệ ${json_data.language['element_'+element]}</p></div>`;
            })
            $('.equipment_select .preview .other').html(html);
        }else{
            $('.equipment_select .preview .name').text(json_data.language[equipment_list[target].info.type+'_'+equipment_list[target].info.sign+'_'+equipment_list[target].info.level]);
            $('.equipment_select .preview .name').addClass('level_'+equipment_list[target].info.level);
            percent_stats = ["critical","critical_damage", "avoid","skill_atk","skill_def"];
            $('.equipment_select .preview .stats').html(equipment_stats_render(equipment_list[target].stats));
            var quality = quality_convert(equipment_list[target].info.quality);
            $('.equipment_select .preview .quality').html(`<span class="level_${quality.num}">${quality.name}</span>`);
            if(equipment_list[target].option){
                html = '';
                $.each(equipment_list[target].option, function(key, option_data) {
                    var option_text = json_data.language['option_'+option_data.sign].replace(':value:',option_data.data.value);
                    html += `<div style="font-size:12px;" class="item"><p class="label option">${option_text}</p></div>`;
                })
                $('.equipment_select .preview .other').html(html);
            }
        }
        
        $('.equipment_select .button').show();
    }

    function equipment_select_confirm(e){
        var target = $('.equipment_select .equipment_list .item.active').attr('target');
        if(popup_data.target == "character"){
            if(character_deteriorate != 0){
                alertify.error(language_text('text_you_need_repair'));
            }else{
                $(e).hide();
                $.post('assets/ajax/character.php',{action : "equipment_add", equipment_id: target , equipment_select_type : popup_data.type},function(data){
                    $('#result').html(data);
                    $(e).show();
                });
            }
        }else if(popup_data.target == "pet"){
            $(e).hide();
            $.post('assets/ajax/character_pet.php',{action : "equipment_add", equipment_id: target , pet_id: popup_data.id},function(data){
                $('#result').html(data);
                $('.equipment_select .close').click();
                $(e).show();
            });
        }
    }
    language_render(['text_wearing']);
    popup_render(); 
</script> 