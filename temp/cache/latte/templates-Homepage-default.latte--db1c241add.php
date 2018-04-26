<?php
// source: C:\xampp2\htdocs\netteRemaster\app\presenters/templates/Homepage/default.latte

use Latte\Runtime as LR;

class Templatedb1c241add extends Latte\Runtime\Template
{
	public $blocks = [
		'content' => 'blockContent',
	];

	public $blockTypes = [
		'content' => 'html',
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
		if (isset($this->params['post'])) trigger_error('Variable $post overwritten in foreach on line 13');
		Nette\Bridges\ApplicationLatte\UIRuntime::initialize($this, $this->parentName, $this->blocks);
		
	}


	function blockContent($_args)
	{
		extract($_args);
?>

    <table class="table table-hover">
            <tr class="bg-dark text-light">
                <th>Id</th>
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
        <td><?php echo LR\Filters::escapeHtmlText($post->id) /* line 15 */ ?></td>
        <td><?php echo LR\Filters::escapeHtmlText($post->name) /* line 16 */ ?></td>
        <td><?php echo LR\Filters::escapeHtmlText(call_user_func($this->filters->date, $post->date, 'j.m Y')) /* line 17 */ ?></td>
        <td><?php echo LR\Filters::escapeHtmlText($post->type) /* line 18 */ ?></td>
        <td><?php echo LR\Filters::escapeHtmlText($post->isWeb) /* line 19 */ ?></td>
        <td><a class="text-danger" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Homepage:delete", [$post->id])) ?>">Smazat</a> <a class="text-success" href="<?php
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Postpage:edit", [$post->id])) ?>">Editovat</a></td>
    </tr>
<?php
			$iterations++;
		}
?>
        </table>
        <a class="btn btn-primary form-control" href="<?php echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link("Postpage:create")) ?>">Vytvořit nový záznam</a>
    
<?php
	}

}
