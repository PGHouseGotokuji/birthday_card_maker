<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?= h($title_for_layout); ?><?= ($this->name != 'Pages' && $this->action != 'index') ? '｜' . TITLE : '' ; ?></title>
    <? if (($this->name == 'Pages' && $this->action == 'arrange') || ($this->name == 'Collaborators' && $this->action == 'accept')) { ?>
    <? } else { ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" />
    <? } ?>
    <meta property="og:title" content="<?= TITLE ?>" />
    <meta property="og:site_name" content="<?= TITLE ?>｜<?= SUB_TITLE ?>" />
    <meta property="og:url" content="<?= SITE_URL ?>" />
    <meta property="og:image" content="<?= SITE_URL ?>/img/card.jpg" />
    <meta property="og:locale" content="ja_JP" />
    <meta name="keywords" content="<?= TITLE ?>, 誕生日, 色紙">
    <meta name="description" content="<?= TITLE ?>は友人の誕生日にオリジナルの色紙を贈ることができるアプリです。">
    <?= $this->Html->charset(); ?>
    <?= $this->Html->meta('icon'); ?>
    <?= $scripts_for_layout; ?>
    <?= $this->Html->css('style'); ?>
    <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
    <script type="text/javascript">
        $(function(){
            $('#flash-message-success').fadeIn(1000).delay(3000).fadeOut(1000);
            if (window.location.hash == '#_=_') {
                window.location.hash = '';
            }
        });

<? if (ENV_MODE == 'pro') { ?>


<? } ?>

</script>
</head>
<body>
<? if (ENV_MODE != 'pro') { ?>
<? } ?>
    <?= $this->Session->flash(); ?>
    <div id="wrapper">
        <div id="header">
            <div class="logo">
                <a href="<?= (!empty($loginUser)) ? '/mypage' : '/'; ?>"><?= TITLE ?></a>
            </div>
            <? if (!empty($loginUser)) { ?>
            <div class="setting"><a href="/logout">ログアウト</a></div>
            <? } ?>
        </div>
        <div class="content clearfix">
            <?= $content_for_layout; ?>
        </div>
        <div id="footer">
            Copyright &copy; <?= date('Y'); ?> <?= PRODUCT_FROM ?><br />
            all rights reserved.
        </div>
        <? if (ENV_MODE != 'pro') { ?>
        <hr>
        <?= $this->element('sql_dump');  ?>
        <? } ?>
    </div>
</body>
</html>
