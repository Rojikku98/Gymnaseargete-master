<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-08
 * Time: 14:01
 */

namespace Game;


class Characters{

    private $xCord;
    private $yCord;
    private $type;

    function __construct($x,$y,$type){
        $this->xCord=$x;
        $this->yCord=$y;
        $this->type=$this;
    }


}