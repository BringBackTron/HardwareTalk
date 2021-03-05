<?php
  class Admin extends User{
    private $_adminControls;
  
    /**
     * @return mixed
     */
    public function getAdminControls()
    {
      return $this -> _adminControls;
    }
    
  }
