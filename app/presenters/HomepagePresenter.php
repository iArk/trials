<?php

namespace App\Presenters;

use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
    /** @var Nette\Database\Context */
    private $database;

    public function __construct(Nette\Database\Context $database)
    {
        $this->database = $database;
    }

    public function renderDefault()
    {
        $this->template->posts = $this->database->table('project');
    }
    public function renderCreate(){  
        
    }
    
    
    public function renderEdit($postid)
    {
    $post = $this->database->table('project')->get($postid);
    if (!$post) {
        $this->error('Stránka nebyla nalezena');
    }

    $this->template->post = $post;
    }
    
    public function actionEdit($postid)
    {
    $post = $this->database->table('project')->get($postid);
    if (!$post) {
        $this->error('Příspěvek nebyl nalezen');
    }
    $this['createPlan']->setDefaults($post->toArray());
    $_SESSION['edited'] = true;
    }
    
    
    
    //Vytvoření formuláře
    protected function createComponentCreatePlan()
    {        
    $form = new Nette\Application\UI\Form;

     $form->addText('name', 'Název:')
             ->setRequired()
             ->setAttribute("class", "form-control");
     $form->addText('date', "Datum odevzdání:")
	->setAttribute("placeholder", "dd.mm.rrrr")
                  ->addRule($form::PATTERN, "Datum musí být ve formátu dd.mm.rrrr", "(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(20)\d\d")
                  ->setRequired()
                  ->setAttribute("class", "form-control");
     $form->addSelect('type', 'Typ:',[
            'Časově omezený' => 'Časově omezený',
            'Pokračující' => 'Pokračující', 
     ])->setAttribute("class", "form-control custom-select");
    $form->addCheckbox('isWeb', 'Webový plán');
    $form->addSubmit('send', 'Potvrdit')->setAttribute("class","btn btn-primary");
    $form->onSuccess[] = [$this, 'createPlanSucceeded'];
    $form->addProtection('Vypršel časový limit, odešlete formulář znovu');
    return $form;
    }

    public function createPlanSucceeded(Nette\Application\UI\Form $form, $values)
    {
    $postid = $this->getParameter('postid');
    
    $checkbox = "";
    if (isset($values->isWeb) && $values->isWeb == false){
        $checkbox = "Ne";
    } else {
        $checkbox = "Ano";
    }
    
    $date = date("Y-m-d", strtotime($values->date));
    
    if (isset($_SESSION['edited'])) {
        /*$this->database->table('project')->update([
        'name' => $values->name,
        'date' => $date,
        'type' => $values->type,
        'isWeb' => $checkbox
        ], 'WHERE id = ?', $postid);*/
        
        $this->database->query('UPDATE project SET', [
        'name' => $values->name,
        'date' => $date,
        'type' => $values->type,
        'isWeb' => $checkbox
    ], 'WHERE id = ?', $postid);
        
         unset($_SESSION['edited']);
    } else {
        $this->database->table('project')->insert([
        'id' => $postid,
        'name' => $values->name,
        'date' => $date,
        'type' => $values->type,
        'isWeb' => $checkbox,
    ]);
    }    
    $this->redirect('Homepage:default');
   
    }
    public function actionDelete($postid){
        $this->database->query('DELETE FROM project WHERE id = ?', $postid);
        $this->redirect('Homepage:default');
    }
}
