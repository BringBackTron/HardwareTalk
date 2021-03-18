<?php
class DataLayer
{
  private $_f3;

  /**
   * DataLayer constructor.
   * @param $f3 object fat free object
   */
  public function __construct($f3)
  {
    $this -> _f3 = $f3;
  }


  function getCommunityName($id)
  {
    switch ($id){
      case 1:
        return "Gaming";
      case 2:
        return "Setups";
      case 3:
        return "CustomPC";
      case 4:
        return "Laptops";
      case 5:
        return "Phones";
      case 6:
        return "Deals";
      case 7:
        return "News";
      case 8:
        return "Coding";
      case 9:
        return "DIY";
    }
  }
}