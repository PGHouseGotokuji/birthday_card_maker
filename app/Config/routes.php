<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/',                  array('controller' => 'Pages', 'action' => 'index', 'home'));
Router::connect('/fblogin',           array('controller' => 'Links', 'action' => 'fbLogin'));

// ユーザー, プラン, 参加者取得api
Router::connect('/get_user',                  array('controller' => 'Users', 'action' => 'getUser'));
Router::connect('/get_plan',                  array('controller' => 'Plans', 'action' => 'getPlan'));
Router::connect('/get_collaborators/:planId', array('controller' => 'Collaborators', 'action' => 'getCollaborators'), array(array('planId' => '[0-9]+')));

// マイページ取得
Router::connect('/mypage',            array('controller' => 'Users', 'action' => 'mypage'));

// fbの友達取得
Router::connect('/get_friends',       array('controller' => 'Users', 'action' => 'getFriends'));

// 誕生日ユーザーを登録するapi
Router::connect('/insert_plan',       array('controller' => 'Plans', 'action' => 'insertPlan'));

// 自分のタイムラインへ投稿
Router::connect('/plan/:planId/post/confirm_fb_timeline',  array('controller' => 'Posts', 'action' => 'confirmPostFbTimeline'), array(array('planId' => '[0-9]+')));
Router::connect('/plan/:planId/post/post_fb_timeline',     array('controller' => 'Posts', 'action' => 'postFbTimeline'), array(array('planId' => '[0-9]+')));

// 相手のタイムラインへ投稿
Router::connect('/plan/:planId/post/confirm_friend_fb_timeline',  array('controller' => 'Posts', 'action' => 'confirmPostFriendFbTimeline'), array(array('planId' => '[0-9]+')));
Router::connect('/plan/:planId/post/post_friend_fb_timeline',  array('controller' => 'Posts', 'action' => 'postFriendFbTimeline'));

// 参加者がログインして参加
Router::connect('/plan/:planId/collaborator/confirm', array('controller' => 'Collaborators', 'action' => 'confirm'), array(array('planId' => '[0-9]+')));
Router::connect('/plan/:planId/collaborator/accept', array('controller' => 'Collaborators', 'action' => 'accept'), array(array('planId' => '[0-9]+')));
Router::connect('/plan/:planId/collaborator', array('controller' => 'Collaborators', 'action' => 'joinCollaborator'), array(array('planId' => '[0-9]+')));

// 画像を生成
Router::connect('/plan/:planId/post/confirm',       array('controller' => 'Posts', 'action' => 'confirm'));
Router::connect('/plan/:planId/card',          array('controller' => 'Posts', 'action' => 'postCard'));

// ログアウト
Router::connect('/logout',            array('controller' => 'auths', 'action' => 'userLogout'));

//Router::connect('/user/add',          array('controller' => 'users', 'action' => 'frontAddUser'));

// エラー
Router::connect('/error',             array('controller' => 'App', 'action' => 'error'));


/**
 * Load all plugin routes.  See the CakePlugin documentation on 
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
