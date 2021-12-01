<?php
class getLine
{
    //declare private variables
    private $count = 0;
    private $line = 0;
    private $dateTime = 0;
    private $line1;
    private $line2;
    private $line3;
    private $line4;
    private $line5;
    private $line6;
    private $line7;
    private $line8;
    private $line9;
    private $line10;
    private $line11;
    private $line12;
    private $line13;
    private $line14;
    private $line15;

   // set to null
    public function refresh()
    {
        $this->line1= null;
        $this->line2= null;
        $this->line3= null;
        $this->line4= null;
        $this->line5= null;
        $this->line6= null;
        $this->line7= null;
        $this->line8= null;
        $this->line9= null;
        $this->line10= null;
        $this->line11= null;
        $this->line12= null;
        $this->count++;
    }

    //get count 
    public function getCount(){return $this->count;}
    
    //function to add more line
    public function setincrease($line){$this->line= $this->line+$line;}
    
    //set data
    public function setDateTime($dateTime){$this->dateTime= $dateTime;}
    public function setline1($line1){$this->line1= $line1;}
    public function setline2($line2){$this->line2= $line2;}
    public function setline3($line3){$this->line3= $line3;}
    public function setline4($line4){$this->line4= $line4;}
    public function setline5($line5){$this->line5= $line5;}
    public function setline6($line6){$this->line6= $line6;}
    public function setline7($line7){$this->line7= $line7;}
    public function setline8($line8){$this->line8= $line8;}
    public function setline9($line9){$this->line9= $line9;}
    public function setline10($line10){$this->line10= $line10;}
    public function setline11($line11){$this->line11= $line11;}
    public function setline12($line12){$this->line12= $line12;}

    public function getline13(){return "CNT+16:".$this->getCount()."'\n"; $this->setincrease(1); $this->setincrease(1);}
    public function getline14(){return "UNT+".$this->line."+".$this->dateTime."'\n";}
    public function getline15(){return "UNZ+1+".$this->dateTime."'";}
    
    //function display string
    public function toString(){
        return $this->line1.$this->line2.$this->line3.$this->line4.
               $this->line5.$this->line6.$this->line7.$this->line8.
               $this->line9.$this->line10.$this->line11.$this->line12;
    } 
}

?>