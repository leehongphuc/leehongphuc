<div id="main_menu">
    <div class="logo">
        <a href="/"><img class="brand_logo_white" alt="logo" src="assets/img/transparent.png"></a>
    </div>
    <div>
        <ul class="main">
            <li class="main one">
                <div onclick="load_module('content','user/setting/profile');" class="title click active">
                    <div class="icon"><i class="far fa-user"></i></div>
                    <p class="text_menu_user_page"></p>
                </div>
            </li>
            <li class="main list">
                <div target="off" class="title">
                    <div class="icon"><i class="fa-regular fa-stars"></i></div>
                    <p>Danh vọng</p>
                    <div class="more_icon"><i class="far fa-angle-down"></i></div>
                </div>
                <div class="clear"></div>
                <ul>
                    <li onclick="load_module('content','user/fame/intro');" class="click">Giới thiệu</li>
                    <li onclick="load_module('content','user/fame/level');" class="click">Cấp độ</li>
                    <li onclick="load_module('content','user/fame/quest');" class="click">Sảnh nhiệm vụ</li>
                    <li onclick="load_module('content','user/fame/badge');" class="click">Thành tựu </li>
                    <li onclick="load_module('content','user/fame/frame');" class="click">Khung trò truyện</li>
                    <li onclick="load_module('content','user/fame/top');" class="click">Bảng xếp hạng</li>
                </ul>
            </li>
            <li class="main list">
                <div target="off" class="title">
                    <div class="icon"><i class="fa-regular fa-atom-simple"></i></div>
                    <p>Quảng cáo</p>
                    <div class="more_icon"><i class="far fa-angle-down"></i></div>
                </div>
                <div class="clear"></div>
                <ul>
                    <li onclick="load_module('content','user/ads/intro');" class="click">Giới thiệu</li>
                    <li onclick="filter_add = {user_id:token_user};load_module('content','user/ads/list');" class="click">Danh sách quảng cáo</li>
                </ul>
            </li>
            <li class="main list">
                <div target="off" class="title">
                    <div class="icon"><i class="fas fa-list"></i></div>
                    <p class="text_menu_album_list"></p>
                    <div class="more_icon"><i class="far fa-angle-down"></i></div>
                </div>
                <div class="clear"></div>
                <ul>
                    <li onclick="load_module('content','user/album/follow');" class="click text_menu_album_list_follow"></li>
                    <li onclick="load_module('content','user/album/history');" class="click text_menu_album_list_history"></li>
                </ul>
            </li>
            <li class="main list">
                <div target="off" class="title">
                    <div class="icon"><i class="fas fa-swords"></i></div>
                    <p class="text_menu_leveling"></p>
                    <div class="more_icon"><i class="far fa-angle-down"></i></div>
                </div>
                <div class="clear"></div>
                <ul>
                    <li onclick="load_module('content','game/dashboard');" class="click text_menu_game_dashboard"></li>
                    <li onclick="load_module('content','game/function/market');" class="click text_menu_game_market"></li>
                    <li onclick="window.open('https://doc.cmanga.com/')" class="click text_menu_game_guide"></li>
                </ul>
            </li>
            <li class="main one">
                <div onclick="load_module('content','user/setting/beenoti');" class="title click">
                    <div class="icon"><i class="fa-regular fa-bell"></i></div>
                    <p class="text_menu_notification"></p>
                </div>
            </li>
            <li class="main list">
                <div target="off" class="title">
                    <div class="icon"><i class="fa-regular fa-gift"></i></div>
                    <p class="text_menu_gift"></p>
                    <div class="more_icon"><i class="far fa-angle-down"></i></div>
                </div>
                <div class="clear"></div>
                <ul>
                    <li onclick="load_module('content','user/gift/list');" class="click text_list"></li>
                    <li onclick="load_module('content','user/gift/history');" class="click text_menu_gift_history"></li>
                </ul>
            </li>
            <li class="main list">
                <div target="off" class="title">
                    <div class="icon"><i class="far fa-cog"></i></div>
                    <p class="text_menu_setting"></p>
                    <div class="more_icon"><i class="far fa-angle-down"></i></div>
                </div>
                <div class="clear"></div>
                <ul>
                    <li onclick="load_module('content','user/setting/email');" class="click">Email</li>
                    <li onclick="load_module('content','user/setting/security');" class="click text_menu_setting_security"></li>
                    <li onclick="load_module('content','user/setting/password_change');" class="click text_menu_setting_password"></li>
                    <li onclick="load_module('content','user/setting/lock');" class="click">Khóa tài khoản</li>
                    <li onclick="popup_load('user/auto_unlock')" class="click">Tự động mở khóa</li>
                </ul>
            </li>
        </ul>
    </div>
    <script>
        $("#nav_menu ul.main li.list .title").click(function() {
            if ($(this).attr('target') == 'off') {
                $('#nav_menu ul.main li.list .title').attr('target', 'off');
                $('#nav_menu ul.main li.list .more_icon').html('<i class="far fa-angle-down"></i>');
                $('#nav_menu ul.main li.list ul').slideUp(200);
                $(this).attr('target', 'on');
                $(this).children('.more_icon').html('<i class="far fa-angle-up"></i>');
                $(this).parent('li').children('ul').slideDown(200);
            } else {
                $(this).attr('target', 'off');
                $(this).children('.more_icon').html('<i class="far fa-angle-down"></i>');
                $(this).parent('li').children('ul').slideUp(200);
            }
        });
        $("#nav_menu ul.main li .click").click(function() {
            $("#nav_menu ul .active").removeClass('active');
            $(this).addClass('active');
        });
    </script>
</div>
<div class="nav_menu_control">
    <div target="on" onclick="open_menu(this)" class="nav_menu_outside"><i class="center_div far fa-times"></i></div>
</div>
<script>
    load_brand_data();
    language_render(['text_menu_user_page','text_menu_album_list','text_menu_album_list_follow','text_menu_album_list_history','text_menu_leveling','text_menu_game_dashboard','text_menu_game_market','text_menu_game_guide','text_menu_notification','text_menu_gift','text_list','text_menu_gift_history','text_menu_setting','text_menu_setting_phone','text_menu_setting_security','text_menu_setting_password']);
</script>