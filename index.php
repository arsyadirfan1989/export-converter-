<!--php side--->
<?php

  //link file for function to get excel file (https://github.com/shuchkin/simplexlsx/) 
  //require "Classes/PHPExcel/IOFactory.php"
  require "code/SimpleXLSX.php";
  require "main.php";
  $object = new getLine(); 
?>

<!--html-->
<html>
    <!--title of the webpage-->
    <title>Assessment</title>
    <head>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <!---link href="stylesheet" type="type/css" href="style/css/bootstrap.min.css"-->
     <!--open source link for css-->
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    </head>
  <!--body-->
  <body style="background-color: navajowhite;">
    <div class="container">
     <div class="card">
      <div class="card-body">
        <h5 class="card-title">Export Booking Excel to Coprar Converter</h5>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="export" enctype="multipart/form-recor$record"> <!--post to php in same page-->

          <div class="form-group">
           <label for="receive">Receiver Code</label>
           <input type="text" name ="receive" value="RECEIVER" class="form-control">
           <p><small>Please change before select the excel file</small></p>
          </div>
           
           <div class="form-group">
            <label for="call">Call Sign Code</label>
            <input type="text" name ="call" value="XXXX"class="form-control" >
            <p><small>Please change before select the excel file</small></p>
           </div>
           
          <div class="form-group">
           <label for="file">Export booking excel</label>
           <input type="file" name ="file"class="form-control" >
           <p><a href="sample/sample.xlsx"><small>Sample file</small></a></p><!--link for sample excel file--->
          </div>
           
          <div class="form-group">
           <input type="submit" name = "convert" value="Convert" ><!--button to submit-->
          </div>   
        </form>
        
        <!--get inputs-->
        <?php 
          if(isset($_POST['convert']))
          {
            $rec = $_POST['receive'];
            $call = $_POST['call'];
            $file = $_FILES['file']['name'];
            $date = date("Ymd"); //current server date
            $time = date("Hi"); // current server time
            $datetime = date("Ymd").date("His"); //server's time and date
            $object->setDateTime($datetime);
        ?>
        <!--display output-->
        <div class="form-group"> 
         <textarea class="form-control" rows ="15" cols= "20" name="display">
          <?php 
             //display top part of the record
             $header = "UNB+UNOA:2+KMT+". $rec . "+". $date .":". $time ."+". $datetime. "\n";
             $header.= "UNH+". $datetime."COPRAR:D:00B:UN:SMDG21+LOADINGCOPRAR"; $object->setincrease(1);
             echo $header;
             
             //retrive excel file
             $xlsx = new SimpleXLSX('file/'. $file);
             /*$excelObject =PHPExcel_IOFactory::load($filePath . "/". $file);
             $getSheet = $excelObject->getActiveSheet()->toArray(null);;*/
             
             /*echo "<pre>";
             echo var_dump($getSheet);*/
            if($xlsx->success())
             {
                 $dim = $xlsx->dimension();
                 $column = $dim[0];
                 $row =$dim[1];
                 
              //get by rows
              foreach($xlsx->rows() as $h->$r)
              {
                 //get by column
                 for($i=0; $i<$column;$i++)
                {
                  if($h<8)
                  {
                    // second row in excel
                    if(($h == 1)&&($i==1))
                    {
                      $record =$r[$i];
                      $year = substr($record,6,4);
                      $month = substr($record,3,2);
                      $day = substr($record,0,2);
                      $hour = substr($record,11,2);
                      $min = substr($record,14,2);
                      $second = substr($record,17);

                      echo "BGM+45+". $year.$month.$day.$hour.$min.$second."+5"."'\n";$object->setincrease(1);
                    }
                    // fourth row inexcel
                    elseif($h == 3)
                    {
                      if(!empty($r[$i]))
                      {
                       $vessel = $r[$i]; //get vessel name
                      }

                      if($i == 3)
                      {
                          $record = $r[$i];
                          $voy = $substr($record,0,2); // get voyage 
                          $opr = substr($record,8); //get opr
                      }

                      //display after get voyage and opr 
                      if((!empty($voy))&&(!empty($opr))&&(!empty($vessel)))
                      {
                        echo "TDT+20".$voy. "+1++172:". $opr . "+++ ". $call . ":103::". $vessel . "\n";
                        $object->setincrease(1);
                        echo "RFF+VON:". $voy."\n";
                        $object->setincrease(1);
                        echo "NAD+CA+".$opr."'\n";
                        $object->setincrease(1);
                      }

                    }
                  } // end if k <8
                  else
                  {
                    for($j=0; $j<=$row ;$j++)
                    {
                      if($j==$h)
                      {
                       // EQD+CN
                       if($i==3)
                       {
                         // E/F
                         if($r[$i]=="E")
                         {
                           $fe = 4;
                         }
                         elseif($r[$i]=="F")
                         {
                           $fe = 5;
                         }
                         //ts
                         if($r[11]=="N")
                         {
                          $ts = 2;
                         }
                         elseif($r[11]=="Y")
                         {
                          $ts = 6;
                         }
                         $container = $r[1];
                         $iso = $r[7];
                         // display 1st line 
                         $line1 = "EQD+CN+". $container."+". $iso . "102:55++". $ts . "+" .$fe ."'\n";
                         $object->setline1($line1);
                       }
                       //LOC+11 & LOC+7
                       if($i == 6)
                       {
                        if(!empty($r[$i]))
                        {
                            $line2 = "LOC+11+".$r[5].":139:6"."'\n";
                            $object->setincrease(1);
                            $line2 .= "LOC+7+".$r[6].":139:6"."'\n";
                            $object->setincrease(1);
                            $object->setline2($line2);
                        }
                       }
                       //LOC +9
                       if($i == 19)
                       {
                        if(!empty($r[$i]))
                        {
                            $line3 = "LOC+9+".$r[$i].":139:6"."'\n";
                            $object->setincrease(1);
                            $object->setline3($line3);
                        }
                       }
                       //MEA
                       if($i == 13)
                       {
                        if(!empty($r[$i])){
                            $line4 = "MEA+AAE+VGM+KGM:".$r[$i]."'\n";$object->setincrease(1);
                            $object->setline4($line4);
                        }
                       }
                       // DIM
                       if($i == 17 )
                       { 
                          if(!empty($r[$i]))
                          {
                            $part = explode("/", $r[$i]);
                            if($part[0]=="OF")
                            {
                                $object->setline5( "DIM+5+CMT:::".$part[1]."'\n");
                                $object->setincrease(1);
                            }
                            elseif($part[0]=="OB")
                            {
                                $object->setline5( "DIM+6+CMT:::".$part[1]."'\n");
                                $object->setincrease(1);
                            }
                            elseif($part[0]=="OR")
                            {
                                $object->setline5( "DIM+7+CMT:::".$part[1]."'\n");
                                $object->setincrease(1);
                            }
                            elseif($part[0]=="OL")
                            {
                                $object->setline5( "DIM+8+CMT:::".$part[1]."'\n");
                                $object->setincrease(1);
                            }
                            elseif($part[0]=="OH")
                            {
                                $object->setline5( "DIM+9+CMT:::".$part[1]."'\n");
                                $object->setincrease(1);
                            }
                          }
                        }
                        // temperature
                        if($i == 15 )
                        {
                            if(!empty($r[$i]))
                            {
                                $temperature= $r[$i];
                                $temperature = str_replace(" ","", $r[$i]);
                                $temperature = str_replace("C","", $r[$i]);
                                $temperature = str_replace("+","", $r[$i]);
                                $object->setline6(  "TMP+2+".$temperature.":CEL"."'\n");
                                $object->setincrease(1);
                            }
                        }
                        //seal no
                        if($i == 25 )
                        {
                            if(!empty($r[$i]))
                            {
                                
                                $tmp = explode(",", $r[$i]);
                                
                                if($tmp[0]=="L") {
                                $object->setline7(  "SEL+".$tmp[1]."+CA"."'\n");$object->setincrease(1);  //seal L - CA, S - SH, M - CU
                                }
                                elseif($tmp[0]=="S") {
                                $object->setline7(  "SEL+".$tmp[1]."+SH"."'\n");  $object->setincrease(1);//seal L - CA, S - SH, M - CU
                                }
                                elseif($tmp[0]=="M") {
                                $object->setline7(  "SEL+".$tmp[1]."+CU"."'\n"); $object->setincrease(1); //seal L - CA, S - SH, M - CU
                                }
                            }
                            echo $object->toString();
                            $object->refresh();
                        }
                        // LG 
                        if($i == 8 )
                        {
                            if(!empty($r[$i]))
                            {
                             $object->setline8(  "FTX+AAI+++".$r[$i]."'\n");
                             $object->setincrease(1);
                            }
                        }
                        //commodity
                        if($i == 12 )
                        {
                            if(!empty($r[$i]))
                            {
                             $object->setline9(  "FTX+AAA+++".$r[$i]."'\n");
                             $object->setincrease(1);
                            }
                        }
                        //storage indicator
                        if($i == 18 )
                        {
                            if(!empty($r[$i]))
                            {
                             $object->setline10(  "FTX+HAN++".$r[$i]."'\n");
                             $object->setincrease(1);
                            }
                        }
                        //DG
                        if($i == 14 )
                        {
                            if(!empty($r[$i])){
                                $part = explode("/", $r[$i]);
                                if($part[0] != "" && $part[1] != ""){
                                $object->setline11(  "DGS+IMD+".$part[0]."+".$part[1]."'\n"); 
                                $object->setincrease(1);
                                }
                            }
                        }
                        // box opr
                        if($i == 2 )
                        {
                            if(!empty($r[$i]))
                            {
                            $object->setline12(  "NAD+CF+".$r[$i].":160:ZZZ"."'\n");
                            $object->setincrease(1);
                            }
                        }
                     }
                    }
                  }// end else
                } // end for loop
               } // end foreach

               // display last 3 lines
               echo $object->getline13();
               echo $object->getline14();
               echo $object->getline15();
             }
            else
            {
                echo "\nError! Cannot retrieve excel file";
            }//end if xslsx success
           }
           else
           {
               echo "\nPlease insert the form properly";
           }
           //end if isset post
          ?>
         </textarea>
        </div>

        <p style="color: red;"><small>***end of line***</small></p>
      </div>
     </div>
    </div>
  </body>
</html>

