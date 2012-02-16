<?php
	final class BlogEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Blog',
				'description' => 'Blogging platform',
				'icon' => '/blog/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array(
					'plugin' => 'blog',
					'controller' => 'blog',
					'action' => 'dashboard'
				)
			);
		}

		public function onRequireTodoList($event){
			return array(
				array(
					'name' => 'warning no categories',
					'type' => 'warning',
					'url' => array('plugin' => 'categories', 'controlelr' => 'categories', 'action' => 'add')
				),
				array(
					'name' => 'Testing: error',
					'type' => 'error',
					'url' => array('plugin' => 'categories', 'controlelr' => 'categories', 'action' => 'index')
				),
				array(
					'name' => 'Testing: info',
					'type' => 'info',
					'url' => array('plugin' => 'categories', 'controlelr' => 'categories', 'action' => 'add')
				)
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'blog', 'controller' => 'blog', 'action' => 'dashboard'),
				'Posts' => array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'index'),
				'Active' => array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'index', 'BlogPost.active' => 1),
				'Pending' => array('plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'index', 'BlogPost.active' => 0)
			);

			return $menu;
		}

		public function onSetupConfig(){
			return Configure::load('Blog.config');
		}
		
		public function onSetupCache(){
			return array(
				'name' => 'blog',
				'config' => array(
					'prefix' => 'blog.'
				)
			);
		}

		public function onSlugUrl($event, $data){
			if(!isset($data['data'])){
				$data['data'] = $data;
			}
			if(!isset($data['type'])){
				$data['type'] = 'posts';
			}

			$data['data']['BlogPost'] = isset($data['data']['BlogPost']) ? $data['data']['BlogPost'] : $data['data'];
			$categorySlug = 'news-feed';
			
			if(!empty($data['data']['GlobalCategory']['slug'])) {
				$categorySlug = $data['data']['GlobalCategory']['slug'];
			}

			else if(!empty($data['data']['BlogPost']['GlobalCategory']['slug'])) {
				$categorySlug = $data['data']['BlogPost']['GlobalCategory']['slug'];
			}
			
			switch($data['type']){
				case 'posts':
					return array(
						'plugin' => 'blog',
						'controller' => 'blog_posts',
						'action' => 'view',
						'id' => $data['data']['BlogPost']['id'],
						'category' => $categorySlug,
						'slug' => $data['data']['BlogPost']['slug']
					);
					break;

				case 'year':
					return array(
						'plugin' => 'blog',
						'controller' => 'blog_posts',
						'action' => 'index',
						'year' => $data['data']['year']
					);
					break;

				case 'year_month':
					return array(
						'plugin' => 'blog',
						'controller' => 'blog_posts',
						'action' => 'index',
						'year' => $data['data']['year'],
						$data['data']['month']
					);
					break;

				case 'tag':
					return array(
						'plugin' => 'blog',
						'controller' => 'blog_posts',
						'action' => 'index',
						'tag' => $data['data']['tag']
					);
					break;
			} // switch
		}

		public function onRequireHelpersToLoad($event){
			
		}

		public function onRequireCssToLoad($event){
			if($event->Handler->params['plugin'] == 'blog'){
				return '/blog/css/blog';
			}
		}

		public function onSetupRoutes($event, $data = null) {
			Router::connect(
				'/admin/blog',
				array(
					'admin' => true,
					'prefix' => 'admin',
					'plugin' => 'blog',
					'controller' => 'blog',
					'action' => 'dashboard'
				)
			);
		}
	}