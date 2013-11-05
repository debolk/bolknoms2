<?php

class Front extends ApplicationController
{
  public function index()
  {

  }

  public function inschrijven_specifiek()
  {
    //FIXME implement method
  }

  public function aanmelden_specifiek()
  {
    //FIXME implement method
  }
  
  public function uitgebreidinschrijven()
  {
    //FIXME implement method
  }
  
  public function aanmelden()
  {
    //FIXME implement method
  }

  public function uitgebreidaanmelden()
  {
    //FIXME implement method
  }
  
  public function afmelden()
  {
    //FIXME implement method
  }
  
  
  public function disclaimer()
  {
    $this->layout->content = View::make('front/disclaimer');
  }
  
  public function privacy()
  {
    $this->layout->content = View::make('front/privacy');
  }

  private function valideer_aanmelding()
  {
    //FIXME implement method
  }
  
  private function valideer_uitgebreideaanmelding()
  {
    //FIXME implement method
  }

  public function errors()
  {
    //FIXME implement method
  }

  private function find_meal()
  {
    //FIXME implement method
  }
  
}