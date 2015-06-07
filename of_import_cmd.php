<?
include("fonction.php");
include("fonction_affaire.php");

if (!d_ok(959)){header("location: accueil.php");exit;}



if($save == 1)
	{
	//Création du tableau à double dimension $t1
	$t = explode("\n", $import);
	for($i=0; $i<count($t); $i++)
		$t[$i] = explode("\t", $t[$i]);
	

	//Vérifications et enregistrements des lignes dans $t2 
	for($i=1; $i<count($t); $i++)
		{
		
		if(is_array($t[$i]) //si c'est un tableau
		   and is_numeric($t[$i][0]) and $t[$i][0] > 0  
		   and $t[$i][1] <> "" 
		   and is_numeric($t[$i][2]) and $t[$i][2] > 0 )
				{
				$t[$i][0]=trim($t[$i][0]);
				$t[$i][1]=trim($t[$i][1]);
				$t[$i][2]=trim($t[$i][2]);
				$t[$i][3]=trim($t[$i][3]);
				if(nombre_de("select id from of where id = ".$t[$i][0])>0){$t2[] = $t[$i];}else{$t3[] = $t[$i];}//on enregistre dans $t2
				}
		}

	

	foreach($t2 as $k => $l)
		{
		
		
		$vf = new valid_form;
		$vf->add("numero_client", ($l[1]));
		$vf->add("poste", ($l[2]));
		if(isdf($l[3]))$vf->add("delai_client", dftoda($l[3]));
		$t2[$k][10]=$vf->update("of"," where id = ".$l[0]." and numero_client = "" and poste = 0;");
		$vf->log(__FILE__,__LINE__,DL_1);
		}

	}


$page = new page;
$page->head("Mise à jour des OF ");
$page->entete("Mise à jour des OF");
$page->add_button(1,0);
$page->add_button(2,1,parent(959));
$page->add_button(3,0);
$page->add_button(0,2);
$page->add_button(4,1,"validation()","Enregistrer");
$page->fin_entete();
$page->datescript();


?>
<SCRIPT LANGUAGE="JavaScript">

fois = 0;
function validation()
{
	if(fois == 0) {fois++; document.formulaire.submit();}
}

</SCRIPT>


<?
if($save == 1)
{
print_r2($t2);
	echo '<TABLE class=forumline cellSpacing=1 cellPadding=4 width="60%" align=center>
		<TR class=m3>
			<TD >OF</TD>
			<TD >Cde client</TD>
			<TD >Poste</TD>
			<TD >Délai client</TD>
			<TD >Maj</TD>
		</TR>';
	
	foreach($t2 as $l)
	{
		 $cid = "class= \"cel1\"";
		
		echo   '<TR '.$cid.' align=center>
			<TD >'.$l[0].'</TD>
			<TD >'.$l[1].'</TD>
			<TD >'.$l[2].'</TD>
			<TD >'.$l[3].'</TD>
			<TD ><img src="images/statut'.$l[10].'.gif"></TD>
			</TR>';
		$ic++;
	}
	foreach($t3 as $l)
	{
		$cid = "class= \"cel3\"";
		
		echo   '<TR '.$cid.' align=center>
			<TD >'.$l[0].'</TD>
			<TD >'.$l[1].'</TD>
			<TD >'.$l[2].'</TD>
			<TD >'.$l[3].'</TD>
			<TD >OF Inexistant</TD>
			</TR>';
		$ic++;
	}	
	echo   '</TABLE><BR><BR>';
}
?>


<FORM method="post" name="formulaire" action="of_import_cmd.php"  target="principal">
	<INPUT  type="hidden" name="save" value="1">
		<TABLE class=forumline cellSpacing=1 cellPadding=4  align=center>
			<TR class="m3">
				<TD>Coller ici la commande</TD>
			</TR>
			<TR class="cel1">
				<TD><TEXTAREA rows=20 cols=100 name="import" ></TEXTAREA></TD>
			</TR>
		</TABLE>
</FORM>


<?
echo pied_page();
?>
