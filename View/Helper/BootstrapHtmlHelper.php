<?php
App::uses('HtmlHelper', 'View/Helper');
App::uses('Inflector', 'Utility');

class BootstrapHtmlHelper extends HtmlHelper {

	const ICON_PREFIX = 'glyphicon-';

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		if (!empty($settings['configFile'])) {
			$this->loadConfig($settings['configFile']);
		} else {
			$this->loadConfig('TwitterBootstrap.html5_tags');
		}
	}

	public function icon($class) {
		$class = explode(' ', $class);
		foreach ($class as &$_class) {
			if ($_class) {
				$_class = self::ICON_PREFIX . $_class;
			} else {
				unset($_class);
			}
		}
		return '<i class="glyphicon ' . implode(' ', $class) . '"></i>';
	}

	public function link($title, $url = null, $options = array(), $confirmMessage = false) {
		$default = array('icon' => null, 'escape' => true);
		$options = array_merge($default, (array)$options);
		if ($options['icon']) {
			if ($options['escape']) {
				$title = h($title);
			}
			$title = $this->icon($options['icon']) . ' ' . $title;
			$options['escape'] = false;
			unset($options['icon']);
		}
		return parent::link($title, $url, $options, $confirmMessage);
	}

	public function css($url = null, $rel = null, $options = array()) {
		if (empty($url)) {
			$url = 'bootstrap.min.css';
			$pluginRoot = dirname(dirname(DIRNAME(__FILE__)));
			$pluginName = end(explode(DS, $pluginRoot));
			$url = '/' . Inflector::underscore($pluginName) . '/css/' . $url;
		}
		return parent::css($url, $rel, $options);
	}

	public function bootstrapCss($url = 'bootstrap.min.css', $rel = null, $options = array()) {
		$pluginRoot = dirname(dirname(DIRNAME(__FILE__)));
		$pluginName = end(explode(DS, $pluginRoot));

		$url = '/' . Inflector::underscore($pluginName) . '/css/' . $url;
		return parent::css($url, $rel, $options);
	}

	public function script($url = null, $options = array()) {
		if (empty($url)) {
			$url = 'bootstrap.min.js';
			$pluginRoot = dirname(dirname(DIRNAME(__FILE__)));
			$pluginName = end(explode(DS, $pluginRoot));
			$url = '/' . Inflector::underscore($pluginName) . '/js/' . $url;
		}
		return parent::script($url, $options);
	}

	public function bootstrapScript($url = 'bootstrap.min.js', $options = array()) {
		$pluginRoot = dirname(dirname(DIRNAME(__FILE__)));
		$pluginName = end(explode(DS, $pluginRoot));

		$url = '/' . Inflector::underscore($pluginName) . '/js/' . $url;
		return parent::script($url, $options);
	}

	public function breadcrumb($items, $options = array()) {
		$default = array(
			'class' => 'breadcrumb',
		);
		$options = array_merge($default, (array)$options);

		$count = count($items);
		$li = array();
		for ($i = 0; $i < $count - 1; $i++) {
			$text = $items[$i];
			$li[] = parent::tag('li', $text);
		}
		$li[] = parent::tag('li', end($items), array('class' => 'active'));
		return parent::tag('ul', implode("\n", $li), $options);
	}

	public function listGroup($items, $options = array()) {
		$default = array(
			'class' => 'list-group'
		);
		$options = array_merge($default, array($options));

		$itemDefault = array(
			'text' => '',
			'description' => false,
			'url' => false,
			'chevron' => false,
			'badge' => false,
			'wrapperOptions' => array(),
			'headerOptions' => array(),
			'textOptions' => array(
				'tag' => 'p'
			)
		);
		$li = array();
		$itemTag = 'li';
		$wrapperTag = 'ul';
		foreach($items as $item) {
			if(!is_array($item)) {
				$item = array(
					'text' => $item
				);
			}

			$item = array_merge($itemDefault, $item);

			$item['wrapperOptions'] = $this->addClass($item['wrapperOptions'], 'list-group-item');
			$item['headerOptions'] = $this->addClass($item['headerOptions'], 'list-group-item-heading');
			$item['textOptions'] = $this->addClass($item['textOptions'], 'list-group-item-text');	

			if(empty($item['text']) && !empty($item[0])) {
				$item['text'] = $item[0];
			}

			if($item['url'] || $item['description']) {
				$itemTag = 'a';
				$wrapperTag = 'div';
			}

			if($item['url']) {
				$item['wrapperOptions']['href'] = $this->url($item['url']);
			}

			if($item['chevron']) {
				$item['text'] .= '<span class="glyphicon glyphicon-chevron-right"></span>';
			}

			if($item['badge']) {
				if(is_array($item['badge'])) {
					list($badge, $badgeOptions) = $item['badge'];
					$badgeOptions['class'][] = 'badge';
				}
				else {
					$badge = $item['badge'];
					$badgeOptions = array('class' => array('badge'));
				}

				$item['text'] .= parent::tag('span', $badge, $badgeOptions);
			}

			if($item['description']) {
				$itemText = parent::tag('h4', $item['text'], $item['headerOptions']);
				$itemText .= parent::tag($item['textOptions']['tag'], $item['description'], $item['textOptions']);
			}
			else {
				$itemText = $item['text'];
			}

			$li[] = array($itemText, $item['wrapperOptions']);
		}

		foreach($li as &$liItem) {
			$liItem = parent::tag($itemTag, $liItem[0], $liItem[1]);
		}

		return parent::tag($wrapperTag, implode("\n", $li), $options);
	}

}