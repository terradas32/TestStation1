<?PHP 
 header("Content-type: application/vnd.ms-excel");
 header("Content-Disposition:  filename=\"X2003.XLS\";");
 
 echo "<table border=1>" ;
 echo "<tr><th> Estado </th><th> 2000  </th><th> 2001 </th><th> 2002 </th> </tr>";
 echo "<tr><td> Colima </td><td> 4.6  </td><td> 4.4 </td><td> 3.8 </td> </tr>";
 echo "<tr><td> Aguascalientes </td><td> 6.5  </td><td> 6.5 </td><td> 3.3 </td> </tr>";
 echo "<tr><td> Guerrero </td><td> 7.2  </td><td> 7.8 </td><td> 3.2 </td> </tr>";
 echo "<tr><td> Totales </td><td> =sum(b2:b4)  </td><td>  =c2+c3+c4 </td><td>  =sum(d2:d4) </td> </tr>";
 echo "</table>"; 
?>