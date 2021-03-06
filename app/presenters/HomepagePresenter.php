<?php

namespace App\Presenters;

use Nette;

class HomepagePresenter extends Nette\Application\UI\Presenter
{
                
                private $database;
    
                public function __construct(Nette\Database\Context $database)
                {
                    $this->database = $database;
                }
                
                public function renderDefault()
                {
                   $this->template->posts= $this->database->query('SELECT * FROM project');
                }
                public function actionDelete($postid)
                {
                   $this->database->query('DELETE FROM project WHERE id = ?', $postid);
                   $this->redirect('Homepage:default');
                }
}
