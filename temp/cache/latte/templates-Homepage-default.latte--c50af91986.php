<?php
// source: C:\xampp2\htdocs\trials\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Templatec50af91986 extends Latte\Runtime\Template
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
		if (isset($this->params['post'])) trigger_error('Variable $post overwritten in foreach on line 16');
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
        <table class="table table-hover">
            <tr class="bg-dark text-light">
                <th>Název</th>
                <th>Datum odevzdání</th>
                <th>Typ projektu</th>
                <th>Webový projekt</th>
                <th>Akce</th>
            </tr>
    
<?php
		$iterations = 0;
		foreach ($posts as $post) {
?>
    <tr>
        <td><?php echo LR\Filters::escapeHtmlText($post->name) /* line 18 */ ?></td>
        <td><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $post->date, 'F j, Y')) /* line 19 */ ?></td>
        <td><?php echo LR\Filters::escapeHtmlText($post->type) /* line 20 */ ?></td>
        <td><?php echo LR\Filters::escapeHtmlText($post->isWeb) /* line 21 */ ?></td>
        <td class="text-danger"><a class="text-danger" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:delete", [$post->id])) ?>">Smazat</a> <a href="<?php
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:edit", [$post->id])) ?>">Upravit</a></td>
    </tr>
<?php
			$iterations++;
		}
?>
        </table>

        <h2><a href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:create")) ?>" class="btn btn-primary">Vytvořit nový</a></h2>
        
        
      </div>
<?php
	}


	function blockTitle($_args)
	{
		extract($_args);
?>            <h1 class="display-4">Tabulky</h1>
<?php
	}

}
