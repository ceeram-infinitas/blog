<p class="tags">
	<?php
		if(!isset($tags)){
			$tags = ClassRegistry::init('Blog.Post')->GlobalTagged->find(
				'cloud',
				array(
					'limit' => 50
				)
			);
		}
		
		// format is different of views / the find above
		if(!isset($tags[0]['GlobalTag'])){
			$_tags = array();
			foreach($tags as $tag){
				$_tags[]['GlobalTag'] = $tag;
			}
			$tags = $_tags;
			unset($_tags);
		}

		echo $this->TagCloud->display(
			$tags,
			array(
				'before' => '<li size="%size%" class="tag">',
				'after'  => '</li>',
				'url' => array(
					'plugin' => 'blog',
					'controller' => 'posts',
					'action' => 'index'
				),
				'named' => 'tag'
			)
		);
	?>
</p>