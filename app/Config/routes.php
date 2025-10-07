<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to public posts index
 */
	Router::connect('/', array('controller' => 'posts', 'action' => 'public_index'));

/**
 * Rotas específicas do blog
 */
	Router::connect('/blog', array('controller' => 'posts', 'action' => 'public_index'));
	Router::connect('/dashboard', array('controller' => 'dashboard', 'action' => 'index'));
	Router::connect('/admin', array('controller' => 'dashboard', 'action' => 'index'));
	Router::connect('/admin/users', array('controller' => 'users', 'action' => 'index'));
	
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/register', array('controller' => 'users', 'action' => 'add'));
	
	Router::connect('/posts', array('controller' => 'posts', 'action' => 'index'));
	Router::connect('/post/:id', array('controller' => 'posts', 'action' => 'view'), array('id' => '[0-9]+'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
