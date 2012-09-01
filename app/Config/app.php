<?php
define('TODAY_DATE',        date('Y-m-d'));        // 今日の日付
define('YESTERDAY_DATE',    date('Y-m-d 00:00:00', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')))); // 昨日の日付
define('TITLE',             'Birthday Card Maker');     // タイトル
define('SUB_TITLE',         '友人の誕生日にオリジナルの色紙を贈ろう！'); // サブタイトル
define('PRODUCT_FROM',      'PGHouse-Gotokuji'); // 製作元
define('PRODUCT_FROM_URL',  'http://pgh-gotokuji.net/'); // 製作元URL
define('AJAX_SELECT_LIMIT', 10);            // ajax-select上限

// 画像系
define('MAX_FILE_UPLOAD_SIZE', '5000000'); // 画像アップロード上限サイズ(5MB)
define('PLAN_PHOTO_DIR',       '/usr/local/data/birthday_card_maker/plan_photos'); // プラン画像保存先
define('COLLABO_PHOTO_DIR',    '/usr/local/data/birthday_card_maker/collabo_photos'); // コラボ画像保存先
