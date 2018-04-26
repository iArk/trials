<?php

namespace App\Presenters;

use Nette;

class PostpagePresenter extends Nette\Application\UI\Presenter
{    
                private $database;
    
                public function __construct(Nette\Database\Context $database)
                {
                    $this->database = $database;
                }
                
                public function renderEdit($postid)
                {
                   $result  = $this->database->query('SELECT * FROM project WHERE id = ?',$postid);
                   if (!$result) {
                        $this->error('Stránka nebyla nalezena');
                    }
                }
                
                public function renderCreate()
                {
                   
                }
                public function createComponentNewProject() {
                    $form = new Nette\Application\UI\Form;
                    $form->addText("name","Název projektu")
                             ->setRequired();
                    $form->addText("date","Datum odevzdání projektu")
                             ->setHtmlType("date")
                             ->setRequired();
                    $form->addSelect('type', 'Typ',[
                            'Časově omezený projekt' => 'Časově omezený projekt',
                            'Continuous Integration' => 'Continuous Integration', 
                    ]);
                    $form->addCheckbox('isWeb', 'Webový projekt');
                    $form->addSubmit('send', 'Přidat plán');
                    $form->onSuccess[] = [$this, 'NewProjectOK'];
                    return $form;
                }
                public function NewProjectOK(Nette\Application\UI\Form $form, $values) {
                    $type = "";
                    if ($values->isWeb == 1) {
                        $type="Ano";
                    } else {  
                        $type="Ne";
                    }
                    $this->database->query("INSERT INTO project VALUES (NULL, ?, ?, ?, ?)", $values->name, $values->date, $values->type, $type);
                    unset($type);
                    $this->redirect('Homepage:');
                }
                
                public function createComponentEditProject() {
                    $form = new Nette\Application\UI\Form;

                    $name = "";
                    $date = "";
                    $type = "";
                    $isWeb = "";
                    
                    $postid = $this->getParameter('postid');
                    $result  = $this->database->query('SELECT * FROM project WHERE id = ?',$postid);
                    
                    foreach ($result as $row) {
                        $name = $row->name;
                        $date = $row->date;
                        $type = $row->type;
                        $isWeb = $row->isWeb;
                    }
                    
                    $form->addText("name","Název projektu")
                             ->setRequired()
                             ->setDefaultValue($name);
                    $form->addText("date","Datum odevzdání projektu")
                             ->setHtmlType("date")
                             ->setRequired()
                             ->setDefaultValue($date);
                    $form->addSelect('type', 'Typ',[
                            'Časově omezený projekt' => 'Časově omezený projekt',
                            'Continuous Integration' => 'Continuous Integration', 
                    ])->setDefaultValue($type);
                    if ($isWeb == "Ano"){
                        $form->addCheckbox('isWeb', 'Webový projekt')->setDefaultValue("1");
                    } else {
                        $form->addCheckbox('isWeb', 'Webový projekt');
                    }                    
                    $form->addSubmit('send', 'Upravit plán');
                    $form->onSuccess[] = [$this, 'EditProjectOK'];
                   
                    return $form;
                }
                public function EditProjectOK(Nette\Application\UI\Form $form, $values) {
                    $postid = $this->getParameter('postid');
                        $type = "";
                    if ($values->isWeb == 1) {
                        $type="Ano";
                    } else {  
                        $type="Ne";
                    }
                    $this->database->query("UPDATE `project` SET `name` = ?, `date` = ?, `type` = ?, `isWeb` = ? WHERE `projekty`.`id` = ?;", $values->name, $values->date, $values->type, $type, $postid);
                    unset($type);
                    $this->redirect('Homepage:');
                }
}
