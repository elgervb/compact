<?php
namespace compact\mvvm;

interface IView
{

    /**
     * Renders the view
     *
     * @return String the view's content
     */
    public function render();
}