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

     $form->addText('name', 'Název:')->setRequired();
     $form->addText('date', "Datum")
	->setAttribute("placeholder", "dd.mm.rrrr")
                  ->addRule($form::PATTERN, "Datum musí být ve formátu dd.mm.rrrr", "(0[1-9]|[12][0-9]|3[01])\.(0[1-9]|1[012])\.(20)\d\d")
                  ->setRequired();
     $form->addSelect('type', 'Typ',[
            'omezený' => 'Časově omezený',
            'continuous' => 'Neomezený/pokračující', 
     ]);
    $form->addCheckbox('isWeb', 'Webový plán');
    $form->addSubmit('send', 'Přidat plán');
    $form->onSuccess[] = [$this, 'createPlanSucceeded'];
    return $form;
    }

    public function createPlanSucceeded(Nette\Application\UI\Form $form, $values)
    {
    $postid = $this->getParameter('id');
    
    $checkbox = "";
    if ($values->isWeb == true){
        $checkbox = "Ano";
    } else {
        $checkbox = "Ne";
    }
    
    $date = date("Y-m-d", strtotime($values->date));
    
    if (isset($_SESSION['edited'])) {
        $this->database->query('UPDATE `project` SET `name` = ?, `date` = ?, `type` = ?, `isWeb` = ? WHERE `project`.`id` = ?;', $values->name, $date, $values->type, $checkbox, $postid);
/*
         $this->database->table('project')->update([
        'name' => $values->name,
        'date' => $values->date,
        'type' => $values->type,
        'isWeb' => $checkbox,
    ]);*/
         unset($_SESSION['edited']);
    } else {
        $this->database->query('INSERT INTO `project` VALUES (NULL, ?, ?, ?, ?);', $values->name, $date, $values->type, $checkbox);
/*        
        $this->database->table('project')->insert([
        'id' => $postid,
        'name' => $values->name,
        'date' => $values->date,
        'type' => $values->type,
        'isWeb' => $checkbox,
    ]);*/
    }    
    $this->redirect('Homepage:default');
   
    }
    public function actionDelete($postid){
        $this->database->query('DELETE FROM project WHERE id = ?', $postid);
        $this->redirect('Homepage:default');
    }
}
