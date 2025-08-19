<div id="item_profile" class="popup_module center_important item_profile">
    <div onclick="$(this).parent('.popup_module').parent('.popup_content').remove();" class="close"><i class="fal fa-times center_div"></i></div>
    <div class="icon_list">
        <div onclick="send_equipment();" style="display:none;" class="item share">
            <i class="center_div fas fa-comments"></i>
        </div>
        <div style="display:none;" class="item lock">
            <i class="center_div fas fa-lock"></i>
        </div>
        <div style="display:none;" onclick="popup_item_profile('source');"class="item source">
            <i class="center_div fa-regular fa-code"></i>
        </div>
    </div>
    <div class="image">
        <img class="icon" src="/assets/img/transparent.png">
    </div>
    <p class="name"></p>
    <p class="note"></p>
    <div class="info">

    </div>
</div>
<script>
    function send_equipment(){
        popup_close();
        $("#private_chat_open").click();
        setTimeout(() => {
            current_text = $('#chat_add').val();
            current_text = current_text + `<equipment>${popup_data.id}</equipment>`;
            $('#chat_add').val(current_text);
        },300)
    }
    async function popup_item_profile(view_special = 'no'){
        if(["currency","item","material","user_currency"].includes(popup_data.type)){
            $('#item_profile .icon').attr('src','/assets/img/level/'+popup_data.type+'/'+popup_data.sign+'.png');
            $('#item_profile .name').text(json_data.language[popup_data.type+'_'+popup_data.sign]);
            $('#item_profile .note').text(json_data.language[popup_data.type+'_detail_'+popup_data.sign]);
            $('#item_profile .note').show();
        }else if(popup_data.type == "skill"){
            $('#item_profile .icon').attr('src','/assets/img/level/'+popup_data.type+'/'+popup_data.sign+'.png');
            $('#item_profile .name').text(json_data.language[popup_data.type+'_'+popup_data.sign]);
            $('#item_profile .name').addClass('skill_rare_'+popup_data.rare);
            $('#item_profile .note').text(get_skill_detail(popup_data.sign,popup_data.level,popup_data.strong,popup_data.rare));
            $('#item_profile .note').show();
            var skill_type = json_data.skill.profile[popup_data.sign].sign;
            var skill_mp = json_data.skill.data[skill_type].mp_reduce ? json_data.skill.data[skill_type].mp - (parseInt(popup_data.rare*3)+parseInt(popup_data.level)+(parseInt(popup_data.strong)*2)) : json_data.skill.data[skill_type].mp;
            var html = `
                <div class="item">
                    <p class="label">${language_text('text_quality')}</p>
                    <p class="value skill_rare_${popup_data.rare}">${json_data.language['skill_rare_'+popup_data.rare]}</p>
                </div>
                <div class="item">
                    <p class="label">${language_text('text_floor')}</p>
                    <p class="value">${popup_data.strong}</p>
                </div>
                <div class="item">
                    <p class="label">${language_text('text_master')}</p>
                    <p class="value">${popup_data.level}</p>
                </div>
                <div class="item">
                    <p class="label">${language_text('text_mp')}</p>
                    <p class="value">${skill_mp}</p>
                </div>
            `;
            $('#item_profile .info').html(html);
        }else if(popup_data.type == "equipment"){
            $('#item_profile .icon_list .share').show();
            $('#item_profile .icon_list .source').show();
            percent_stats = ["critical","critical_damage", "avoid","skill_atk","skill_def"];
            var get_data = await get_data_by_url('/api/get_data_by_id?table=game_equipment&data=data&id='+ popup_data.id);
            var equipment_data = JSON.parse(get_data.data);
            var quality = quality_convert(equipment_data.info.quality);
            $('#item_profile .icon').attr('src',`/assets/img/level/${popup_data.type}/${equipment_data.info.type}/${equipment_data.info.sign}_${equipment_data.info.level}.png`);
            $('#item_profile .image').addClass('equipment_background_level_'+equipment_data.info.level);
            if(['book','tactical'].includes(equipment_data.info.type)){
                $('#item_profile .name').text(json_data.language[equipment_data.info.type+'_'+equipment_data.info.sign+'_'+equipment_data.info.level]);
            }else{
                if(view_special == "source"){
                    $('#item_profile .name').text(json_data.language[equipment_data.info.type+'_'+equipment_data.info.sign+'_'+equipment_data.info.level]+' +0');
                }else{
                    $('#item_profile .name').text(json_data.language[equipment_data.info.type+'_'+equipment_data.info.sign+'_'+equipment_data.info.level]+' +'+equipment_data.info.upgrade);
                }
            }
            $('#item_profile .name').addClass('level_'+equipment_data.info.level);
            var current_add_option = getSafe(() => equipment_data.info.add_option, 1);
            if(equipment_data.info.lock){
                $('#item_profile .icon_list .lock').show();
            }
            var weapon_type = equipment_data.info.type == 'weapon' ? json_data.language['weapon_'+equipment_data.info.sign]+' - ' : '';
            if(equipment_data.info.type == "book"){
                if(equipment_data.info.training.num == 100){
                    var next_exp = 999999;
                }else{
                    var next_exp = Math.floor(server_data.game_exp.book.default[equipment_data.info.training.num+1]*server_data.game_exp.book.rate[equipment_data.info.level]);
                }
                var html = `
                    <div class="item">
                        <p class="label">${language_text('text_equipment_type')}</p>
                        <p class="value">${weapon_type}${json_data.language['equipment_'+equipment_data.info.type]}</p>
                    </div>
                    <div class="item">
                        <p class="label">${language_text('text_quality')}</p>
                        <p class="value">${json_data.language['book_rare_'+equipment_data.info.level]}</p>
                    </div>
                    <div class="item">
                        <p class="label">${language_text('text_level')}</p>
                        <p class="value">${equipment_data.info.training.num}</p>
                    </div>
                    <div class="item border_bottom">
                        <p class="label">${language_text('text_floor')}</p>
                        <p class="value">${equipment_data.data.main.level}</p>
                    </div>
                    <div class="item border_bottom">
                        <p class="label">${language_text('text_master')}</p>
                        <p class="value">${number_format(equipment_data.info.training.exp)}/${number_format(next_exp)}</p>
                    </div>
                `;
                var book_note = json_data.language['book_main_stats'];
                book_note = book_note.replace(':value_0:',equipment_data.data.main.value);
                book_note = book_note.replace(':value_1:',json_data.language['stats_'+equipment_data.data.main.stats]);
                $('#item_profile .note').text(book_note);
                $.each(equipment_data.data.option, function(key, option_data) {
                    var option_text = json_data.language['book_option_'+key].replace(':value_0:',option_data.value);
                    if(key.indexOf('weapon') !== -1){
                        option_text = option_text.replace(':value_1:',json_data.language['weapon_'+option_data.target]);
                    }else if(key.indexOf('skill') !== -1){
                        option_text = option_text.replace(':value_1:',json_data.language['element_'+option_data.target]);
                    }
                    html += `<div style="font-size:12px;" class="item"><p class="label option">${option_text}</p></div>`;
                })
            }else if(equipment_data.info.type == "tactical"){
                var html = `
                    <div class="item">
                        <p class="label">${language_text('text_equipment_type')}</p>
                        <p class="value">${weapon_type}${json_data.language['equipment_'+equipment_data.info.type]}</p>
                    </div>
                    <div class="item border_bottom">
                        <p class="label">${language_text('text_level')}</p>
                        <p class="value">${json_data.language['level_'+equipment_data.info.level]}</p>
                    </div>
                    <div class="item border_bottom">
                        <p class="label">Chúc phúc</p>
                        <p class="value">${equipment_data.data.main.rate}%</p>
                    </div>
                `;
                $.each(equipment_data.data.element, function(target, element) {
                    html += `<div style="font-size:12px;" class="item"><p class="label option">Tăng ${equipment_data.data.main.rate}% chúc phúc khi có ${json_data.language['tactical_require_'+target]} hệ ${json_data.language['element_'+element]}</p></div>`;
                })
            }else{
                var html = `
                    <div class="item">
                        <p class="label">${language_text('text_equipment_type')}</p>
                        <p class="value">${weapon_type}${json_data.language['equipment_'+equipment_data.info.type]}</p>
                    </div>
                    <div class="item">
                        <p class="label">${language_text('text_level')}</p>
                        <p class="value">${json_data.language['level_'+equipment_data.info.level]}</p>
                    </div>
                    <div class="item">
                        <p class="label">${language_text('text_quality')}</p>
                        <p class="value level_${quality.num}">${quality.name}</p>
                    </div>
                    <div class="item border_bottom">
                        <p class="label">${language_text('text_add_option')}</p>
                        <p class="value">${current_add_option-1} ${language_text('text_times')}</p>
                    </div>
                `;
                if(equipment_data.option){
                    $.each(equipment_data.option, function(key, option_data) {
                        var option_text = json_data.language['option_'+option_data.sign].replace(':value:',option_data.data.value);
                        html += `<div style="font-size:12px;" class="item"><p class="label option">${option_text}</p></div>`;
                    })
                    html += `
                        <div class="item border_bottom">
                        </div>
                    `;
                }
                if(equipment_data.info.type == 'weapon'){
                    $.each(json_data.counter.weapon[equipment_data.info.sign], function(key, value) {
                        html += `<div style="font-size:12px;" class="item"><p class="label counter">${language_text('text_dmg_to_weapon',[value,json_data.language['weapon_'+key]])}</p></div>`;
                    })
                    html += `
                        <div class="item border_bottom">
                        </div>
                    `;
                }
                html += equipment_stats_render(equipment_data.stats,view_special);
            }
            $('#item_profile .info').html(html);
        }
    }
    popup_item_profile();
</script> 