<?php
App::uses('BootstrapHtmlHelper', 'TwitterBootstrap.View/Helper');
App::uses('View', 'View');

class BootstrapHtmlHelperTest extends CakeTestCase {

	public function setUp() {
		$controller = null;
		$View = new View($controller);
		$this->BootstrapHtml = new BootstrapHtmlHelper($View);
	}

	public function tearDown() {
		unset($this->BootstrapHtml);
	}

	public function testIcon() {
		$class = 'glass';
		$expected = '<i class="glyphicon glyphicon-glass"></i>';
		$this->assertSame($expected, $this->BootstrapHtml->icon($class));
	}

	public function testLinkWithIcon() {
		$title = '<b>test</b>';
		$url = '/';
		// single
		$icon = 'glass';
		$result = $this->BootstrapHtml->link($title, $url, compact('icon'));
		$expected = '<a href="/"><i class="glyphicon glyphicon-glass"></i> &lt;b&gt;test&lt;/b&gt;</a>';
		$this->assertSame($expected, $result, 'normal icon link with escape string');

		// white icon without escape
		$icon = 'glass';
		$escape = false;
		$result = $this->BootstrapHtml->link($title, $url, compact('icon', 'escape'));
		$expected = '<a href="/"><i class="glyphicon glyphicon-glass"></i> <b>test</b></a>';
		$this->assertSame($expected, $result, 'white icon link without escape');
	}

}
