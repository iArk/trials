<?php
// source: C:\xampp2\htdocs\trialsV1\app\presenters/templates/Homepage/edit.latte

use Latte\Runtime as LR;

class Templatece06e42e34 extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
		'title' => 'blockTitle',
	];

	public $blockTypes = [
		'content' => 'html',
		'title' => 'html',
	];


	function main()
	{
		extract($this->params);
		if ($this->getParentName()) return get_defined_vars();
		$this->renderBlock('content', get_defined_vars());
		return get_defined_vars();
	}


	function prepare()
	{
		extract($this->params);
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>

<div class="container">
        <div class="text-center">
<?php
		$this->renderBlock('title', get_defined_vars());
?>
        </div>

<a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:default")) ?>">← zpět na výpis</a>

<?php
		/* line 12 */ $_tmp = $this->global->uiControl->getComponent("createPlan");
		if ($_tmp instanceof Nette\Application\UI\IRenderable) $_tmp->redrawControl(null, false);
		$_tmp->render();
?>

 </div><?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?>            <h1 class="display-4">Upravit plán</h1>
<?php
	}

}
