<?php
namespace app\page;

use compact\mvvm\impl\ViewModel;
use compact\handler\impl\http\HttpStatus;
use compact\Context;

class IssueController{

    public function issue($issue){
        $method = "issue".$issue;
        if (method_exists($this, $method)){
            return $this->$method($issue);
        }
        
        return new ViewModel('issues/'.$issue.'.html');
    }
    
    public function issue17($issue){
        return new HttpStatus(301, Context::siteUrl().'/issue/17/redirect');
    }
    
    public function issue17Redirect(){
        return "redirect succeeded!";
    }
}