<?
include("fonction.php");
if ((!d_ok(1182))){header("location: accueil.php");exit;}
$_SESSION['en_cour']="fiche_5p_ajouter.php";

$j_interne=dbtodata("select id , nom from interne ");


//suppression de fichier .pdf
if ($del_fichier>0)
	{
	$vf = new valid_form ;
	$vf->add("d_fichier", '0000-00-00');
	$vf->update("fiche_5p"," where id = '".$del_fichier."';");
	$vf->log(__FILE__,__LINE__,DL_2);
	echo exec("rm ".$path_srv."data/fiche_5p/F5P".format_0($del_fichier,6).".pdf");
	}


if ($save == 1)
{

	$vf = new valid_form ;

	$vf->add("id_pilote", $id_pilote);
	$vf->add("id_dtnc", $id_dtnc);
	$vf->add("d_creation", dftoda($d_creation));
	$vf->add("des", $des);
	$vf->add("q_piece", $q_piece);
	$vf->add("q_pb", $q_pb);
	$vf->add("q_loc", $q_loc);
	$vf->add("p_pb", $p_pb);
	$vf->add("q_date", dftoda($q_date));
	$vf->add("ou_pb", $ou_pb);
	$vf->add("q_detect", $q_detect);
	$vf->add("q_detect_date", dftoda($q_detect_date));
	$vf->add("ou_pb_detect", $ou_pb_detect);
	$vf->add("c_pb_detect", $c_pb_detect);
	$vf->add("situation", $situation);
	$vf->add("pq1", $pq1);
	$vf->add("pq1_verif", $pq1_verif);
	$vf->add("pq2", $pq2);
	$vf->add("pq2_verif", $pq2_verif);
	$vf->add("pq3", $pq3);
	$vf->add("pq3_verif", $pq3_verif);
	$vf->add("pq4", $pq4);
	$vf->add("pq4_verif", $pq4_verif);
	$vf->add("pq5", $pq5);
	$vf->add("pq5_verif", $pq5_verif);
	$vf->add("verif1", $verif1);
	$vf->add("verif1_comment", $verif1_comment);
	$vf->add("verif2", $verif2);
	$vf->add("verif2_comment", $verif2_comment);
	$vf->add("verif3", $verif3);
	$vf->add("verif3_comment", $verif3_comment);
	$vf->add("verif4", $verif4);
	$vf->add("verif4_comment", $verif4_comment);
	$vf->add("eff_action_fab", $eff_action_fab);
	$vf->add("eff_action_sem", $eff_action_sem);
	$vf->add("d_cloture", dftoda($d_cloture));
	$vf->add("id_nom", $id_nom);
	$vf->add("observation", $observation);
	$vf->add("maj", date("Y-m-d"));


	
	if ($util>0)
		{
		//upload de fichier
		if($_FILES['fichier']['tmp_name']<>'')
			{
			if( is_uploaded_file($_FILES['fichier']['tmp_name']) )
				{
				
				$dossier = './data/fiche_5p/';
				// récupère la partie de la chaine à partir du dernier . pour connaître l'extension.
				$extension = strrchr($_FILES['fichier']['name'], '.');
	
				//Ensuite on teste
				if($extension==".pdf")
					{
					$_FILES['fichier']['name']="F5P".format_0($util,6);
					$fichier = basename($_FILES['fichier']['name']);
					if(move_uploaded_file($_FILES['fichier']['tmp_name'], $dossier . $fichier.".pdf")){$vf->add("d_fichier", date("Y-m-d"));} //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
					}
				}
			}
			
		$vf->update("fiche_5p"," where id = '".$util."';");
		}
	else
		{
		$vf->add("d_creation", date('Y-m-d'));
		$util=$vf->insert("fiche_5p");{echo "<center>Enregistrement OK<br><center>";}
		}
		
	$vf->log(__FILE__,__LINE__,DL_1);	

	
 }
 
		

 if ($del_fa > 0)
	{
	del_visit(__FILE__,__LINE__,DL_1,"fiche_5p_action","where id = '$del_fa';");
	}

if($util > 0){$_SESSION["fiche_5p_en_cour"]=$util;}
else if($new > 0){$_SESSION["fiche_5p_en_cour"]=0;}
$util=$_SESSION["fiche_5p_en_cour"];


function aff_form($name,$value)
{
		echo '<tr>
		<td  class="cel1" >'.$name.'</td>
		<td  class="cel2" >&nbsp;'.$value.'</td>
		</tr>';
}

function aff_sep($name)
{
		echo '<tr>
		<td align=center colspan=2 class ="m3" >'.$name.'</td>
		</tr>';
}

$butt="Ajouter une fiche d'enquête 5 P ";

if ($util > 0)
{
$sql=("select * from fiche_5p where id = '$util'");

$res=my_query($sql);
$nb_ligne=mysql_num_rows($res);
$row = mysql_fetch_array($res);

$id = $row["id"];
$id_fiche_5p = $row[0];
$id_pilote = $row["id_pilote"];
$id_dtnc = $row["id_dtnc"];
$d_creation = datodf($row["d_creation"]);
$des = $row["des"];
$q_piece = $row["q_piece"];
$q_pb = $row["q_pb"];
$q_loc = $row["q_loc"];
$p_pb = $row["p_pb"];
$q_date = $row["q_date"];
$ou_pb = $row["ou_pb"];
$q_detect = $row["q_detect"];
$q_detect_date = $row["q_detect_date"];
$ou_pb_detect = $row["ou_pb_detect"];
$c_pb_detect = $row["c_pb_detect"];
$situation = $row["situation"];
$pq1 = $row["pq1"];
$pq1_verif = $row["pq1_verif"];
$pq2 = $row["pq2"];
$pq2_verif = $row["pq2_verif"];
$pq3 = $row["pq3"];
$pq3_verif = $row["pq3_verif"];
$pq4 = $row["pq4"];
$pq4_verif = $row["pq4_verif"];
$pq5 = $row["pq5"];
$pq5_verif = $row["pq5_verif"];
$verif1 = $row["verif1"];
$verif1_comment = $row["verif1_comment"];
$verif2 = $row["verif2"];
$verif2_comment = $row["verif2_comment"];
$verif3 = $row["verif3"];
$verif3_comment = $row["verif3_comment"];
$verif4 = $row["verif4"];
$verif4_comment = $row["verif4_comment"];
$eff_action_fab = $row["eff_action_fab"];
$eff_action_sem = $row["eff_action_sem"];
$d_cloture = $row["d_cloture"];
$id_nom = $row["id_nom"];
$observation = $row["observation"];
$maj = $row["maj"];
}
else
{
$d_creation=date("d/m/Y");	
}


$id_pilote = liste_db("select id , concat(nom,' ',prenom) from interne where (actif = 1 and login <> '')or id = '$id_pilote' order by nom , prenom asc",$id_pilote,"id_pilote");
$id_dtnc = '<input type="text" name="id_dtnc" size=20 maxlength=20 value="'.$id_dtnc.'">';
if ($util < 0) $d_creation='<input type="hidden" name="d_creation">'.$d_creation.'';


$des = '<textarea cols=100 rows=8 name="des" >'.$des.'</textarea>';

$q_piece = '<input type="text" name="q_piece" size=100 maxlength=100 value = "'.$q_piece.'">';
$q_pb = '<input type="text" name="q_pb" size=100 maxlength=100 value= "'.$q_pb.'">';
$q_loc = '<input type="text" name="q_loc" size=100 maxlength=100 value = "'.$q_loc.'">';
$p_pb = '<input type="text" name="p_pb" size=100 maxlength=100 value = "'.$p_pb.'">';
$q_date = '<input readonly onclick="return showCalendar(\'sel2\', \'%d/%m/%Y\');"  id=sel2  type="text" maxlength="10" name="q_date" size="12" value="'.datodf($q_date).'">'.calendar('sel2').'';
$ou_pb = '<input type="text" name="ou_pb" size=100 maxlength=100 value = "'.$ou_pb.'">';
$q_detect = '<input type="text" name="q_detect" size=100 maxlength=100 value = "'.$q_detect.'">';
$q_detect_date = '<input readonly onclick="return showCalendar(\'sel3\', \'%d/%m/%Y\');"  id=sel3 type="text" maxlength="10" name="q_detect_date" size="12" value="'.datodf($q_detect_date).'">'.calendar('sel3').'';
$ou_pb_detect = '<input type="text" name="ou_pb_detect" size=100 maxlength=100 value = "'.$ou_pb_detect.'">';
$c_pb_detect = '<input type="text" name="c_pb_detect" size=100 maxlength=100 value = "'.$c_pb_detect.'">';

$situation = '<textarea cols=100 rows=8 name=situation>'.$situation.'</textarea>';

$pq1 = '<input type="text" name="pq1" size=100 maxlength=100 value = "'.$pq1.'">';
$pq1_verif = '<input type="text" name="pq1_verif" size=100 maxlength=100 value = "'.$pq1_verif.'">';
$pq2 = '<input type="text" name="pq2" size=100 maxlength=100 value = "'.$pq2.'">';
$pq2_verif = '<input type="text" name="pq2_verif" size=100 maxlength=100 value = "'.$pq2_verif.'">';
$pq3 = '<input type="text" name="pq3" size=100 maxlength=100 value = "'.$pq3.'">';
$pq3_verif = '<input type="text" name="pq3_verif" size=100 maxlength=100 value = "'.$pq3_verif.'">';
$pq4 = '<input type="text" name="pq4" size=100 maxlength=100 value = "'.$pq4.'">';
$pq4_verif = '<input type="text" name="pq4_verif" size=100 maxlength=100 value = "'.$pq4_verif.'">';
$pq5 = '<input type="text" name="pq5" size=100 maxlength=100 value = "'.$pq5.'">';
$pq5_verif = '<input type="text" name="pq5_verif" size=100 maxlength=100 value = "'.$pq5_verif.'">';





if($util>0)
	{
		
		if(!isda($row["d_fichier"]))$table_fichier = '<input type="hidden" name="MAX_FILE_SIZE" value="1000000"><input type="file" name="fichier" >';
		
		if(isda($row["d_fichier"]))$table_fichier='<table cellSpacing=1 cellPadding=4 class=forumline align=center>
				<tr class = m3>
					<td>ID</td>
					<td>Fichier</td>
					<td>Date</td>
					<td>Suppr.</td>
				</tr>
				<tr align= "center" class= "cel1">
					<td>'.$row["id"].'</td>
					<td> <a class="b" href="/data/fiche_5p/F5P'.format_0($util,6).'.pdf" target="_blank"><img border=0 src="images/pdf.gif">&nbsp;'.$row["fichier"].'</a></td>
					<td>'.datodf($row["d_fichier"]).'</td>
					<td> <img style= "cursor:pointer;" title="Supprimer cette fiche action" onclick="document.location.href=\'fiche_5p_ajouter.php?del_fichier='.$util.'\'" src="images/delete.gif"></td> 
				</tr>
				</table>';
	}



if($verif1==1){$chk1="checked";}else if($verif1==0){$chk2="checked";}
$verif1 = '<input class = "cel2" type="radio" id="type_action1" name="verif1" value="1" '.$chk1.'>Oui&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_action2" name="verif1" value="0" '.$chk2.'>Non';
$verif1_comment = '<input type="text" name="verif1_comment" size=100 maxlength=100 value = "'.$verif1_comment.'">';

if($verif2==1){$chk3="checked";}else if($verif2==0){$chk4="checked";}
$verif2 = '<input class = "cel2" type="radio" id="type_action1" name="verif2" value="1" '.$chk3.'>Oui&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_action2" name="verif2" value="0" '.$chk4.'>Non';
$verif2_comment = '<input type="text" name="verif2_comment" size=100 maxlength=100 value = "'.$verif2_comment.'">';

if($verif3==1){$chk5="checked";}else if($verif3==0){$chk6="checked";}
$verif3 = '<input class = "cel2" type="radio" id="type_action1" name="verif3" value="1" '.$chk5.'>Oui&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_action2" name="verif3" value="0" '.$chk6.'>Non';
$verif3_comment = '<input type="text" name="verif3_comment" size=100 maxlength=100 value = "'.$verif3_comment.'">';

if($verif4==1){$chk7="checked";}else if($verif4==0){$chk8="checked";}
$verif4 = '<input class = "cel2" type="radio" id="type_action1" name="verif4" value="1" '.$chk7.'>Oui&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_action2" name="verif4" value="0" '.$chk8.'>Non';
$verif4_comment = '<input type="text" name="verif4_comment" size=100 maxlength=100 value = "'.$verif4_comment.'">';

if($eff_action_fab==1){$chek1="checked";}else if($eff_action_fab==2){$chek2="checked";}elseif($eff_action_fab==3){$chek3="checked";}
elseif($eff_action_fab==4){$chek4="checked";}elseif($eff_action_fab==5){$chek5="checked";}
$eff_action_fab = 'Sur 5 fabrications&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class = "cel2" type="radio" id="type_eff_fab1" name="eff_action_fab" value="1" '.$chek1.'>1&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_fab2" name="eff_action_fab" value="2" '.$chek2.'>2&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_fab3" name="eff_action_fab" value="3" '.$chek3.'>3&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_fab4" name="eff_action_fab" value="4" '.$chek4.'>4&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_fab5" name="eff_action_fab" value="5" '.$chek5.'>5';

if($eff_action_sem==1){$check1="checked";}else if($eff_action_sem==2){$check2="checked";}elseif($eff_action_sem==3){$check3="checked";}
elseif($eff_action_sem==4){$check4="checked";}elseif($eff_action_sem==5){$check5="checked";}
$eff_action_sem = 'Sur 5 semaines&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input class = "cel2" type="radio" id="type_eff_sem1" name="eff_action_sem" value="1" '.$check1.'>1&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_sem2" name="eff_action_sem" value="2" '.$check2.'>2&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_sem3" name="eff_action_sem" value="3" '.$check3.'>3&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_sem4" name="eff_action_sem" value="4" '.$check4.'>4&nbsp;&nbsp;&nbsp;
<input class = "cel2" type="radio" id="type_eff_sem5" name="eff_action_sem" value="5" '.$check5.'>5';

$d_cloture = '<input readonly onclick="return showCalendar(\'sel4\', \'%d/%m/%Y\');"  id=sel4 type="text" maxlength="10" name="d_cloture" size="12" value="'.datodf($d_cloture).'">'.calendar('sel4').'';
$id_nom =  liste_db("select id , concat(nom,' ',prenom) from interne where (actif = 1 and login <> '')or id = '$id_nom' order by nom , prenom asc",$id_nom,"id_nom");

$observation =  '<textarea cols=100 rows=8 name="observation" >'.$observation.'</textarea>';


if($_SESSION["fiche_5p_en_cour"] > 0){$txt="Fiche d'enquête 5 P N° ".format_0($_SESSION["fiche_5p_en_cour"],6);}else{$txt = "Ajouter une fiche d'enquête 5 P";}

$page = new page;
$page->head($txt);
$page->body("onload=cache()");
$page->entete($txt);
$page->add_button(1,0);
$page->add_button(2,1,parent(1182));
$page->add_button(3,0);
$page->add_button(0,2);
$page->add_button(4,1,"validation();");
$page->add_button(0,2);
//ESSAI FPDF
$page->add_button(43,1,"fiche_5p_imprimer.php?util=".$util,"Aperçu de la fiche","_blank");
//FIN ESSAI
$page->fin_entete();
$page->datescript();

?>
<script LANGUAGE="JavaScript">

fois=0;

function validation()
{

		if (document.formulaire.id_pilote.options[document.formulaire.id_pilote.selectedIndex].value == "0" )  
		{
			alert("Veuillez saisir le champ 'Pilote'.");
			document.formulaire.id_pilote.focus();
			return false;
		}
		
		if (document.formulaire.id_dtnc.value=="")
		{
			alert("Veuillez saisir le champ 'N°DTNC'.");
			document.formulaire.id_dtnc.focus();
			return false;
		}
		
		if (document.formulaire.q_piece.value=="")
		{
			alert("Veuillez saisir le champ 'Pièce affectée'.");
			document.formulaire.q_piece.focus();
			return false;
		}
		
		if (document.formulaire.q_pb.value=="")
		{
			alert("Veuillez saisir le champ 'Problème'.");
			document.formulaire.q_piece.focus();
			return false;
		}
		
	if(fois==0){fois++;document.formulaire.submit();}
}

	function popup1()
	{
	var hf1 = 100;
	var gf1 = 100;
	var param1 = "width=1000,height=600,scrollbars=yes,left=" + gf1 + ",top=" + hf1 ;
	window.open("select_fiche_action.php","Fiche action", param1);
	return false;
	}
	
</script>



<form name="formulaire"  method="post" action="fiche_5p_ajouter.php?parent_ori1=1" OnSubmit="return validation();" target="principal"  enctype="multipart/form-data">

<input  type="hidden" name="util" value="<? echo $util;?>">
<input  type="hidden" name="save" value="1">


<TABLE class=forumline cellSpacing=1 cellPadding=4 width="90%" align=center>
<?


aff_sep("REFERENCES");
aff_form("Pilote : ",$id_pilote); 
aff_form("N°DTNC : ",$id_dtnc);
aff_form("Date : ",$d_creation);
aff_sep("DESCRIPTION DU PROBLEME");
aff_form("Description",$des);
aff_sep("PROBLEME");
aff_form("Pièce affectée : ",$q_piece);
aff_form("Problème : ",$q_pb);
aff_form("Localisation du problème : ",$q_loc);
aff_form("Pourquoi est-ce un problème : ",$p_pb);
aff_form("Date de création du problème : ",$q_date);
aff_form("Lieu où le problème a été créé : ",$ou_pb);
aff_form("Qui a détecté le problème : ",$q_detect);
aff_form("Date détection du problème : ",$q_detect_date);
aff_form("Lieu où le problème a été détecté : ",$ou_pb_detect);
aff_form("Comment le problème a été détecté : ",$c_pb_detect);
aff_sep("SITUATION DU DEFAUT");
aff_form("Situation",$situation);
aff_sep("POURQUOI ?");
aff_form("1 pourquoi : ",$pq1);
aff_form("Comment cela a-t-il été vérifié ? ",$pq1_verif);
aff_form("2 pourquoi : ",$pq2);
aff_form("Comment cela a-t-il été vérifié ? ",$pq2_verif);
aff_form("3 pourquoi : ",$pq3);
aff_form("Comment cela a-t-il été vérifié ? ",$pq3_verif);
aff_form("4 pourquoi : ",$pq4);
aff_form("Comment cela a-t-il été vérifié ? ",$pq4_verif);
aff_form("5 pourquoi : ",$pq5);
aff_form("Comment cela a-t-il été vérifié ? ",$pq5_verif);
aff_sep("VERIFICATION");
aff_form("La solution est elle généralisable ? ",$verif1);
aff_form("Comment ? ",$verif1_comment);
aff_form("La documentation est elle à jour ? ",$verif2);
aff_form("Comment ? ",$verif2_comment);
aff_form("Les opérateurs sont ils au courant du problème ? ",$verif3);
aff_form("Comment ? ",$verif3_comment);
aff_form("Les opérateurs sont ils au courant des nouvelles procédures ? ",$verif4);
aff_form("Comment ? ",$verif4_comment);
aff_sep("CONFIRMATION");
aff_form("Confirmation de l'efficacité des actions : (cocher votre choix) ",$eff_action_fab);
aff_form("Confirmation de l'efficacité des actions : (cocher votre choix) ",$eff_action_sem);
aff_sep("CLOTURATION");
aff_form("Date de clôture : ",$d_cloture);
aff_form("Nom : ",$id_nom);
aff_sep("OBSERVATIONS");
aff_form("Observations",$observation);


if($util > 0)
{
aff_sep("UPLOAD");
aff_form("Fichiers",$table_fichier);
?>


</TABLE>	
<TABLE class=forumline cellSpacing=1 cellPadding=2 width="90%" align=center border=0>
	
	<tr class= "m3">
		<td width=90% colspan=11 >FICHES ACTIONS</td>
		<td width=5% ><img onclick="return popup1();" style="cursor:pointer;" title="Ajouter des fiches actions" src="images/add.gif"></td>
	</tr>
	<tr class= "m3">
		<td width=5% >N°</td>
		<td width=5% >Date</td>
		<td width=40% >Actions</td>
		<td width=5% >Type</td>
		<td width=15% >Réf produit</td>
		<td width=5% >Ecart</td>
		<td width=5% >Délai</td>
		<td width=18% >Pilote</td>
		<td width=18% >Responsable</td>
		<td width=10% >Constat exec.</td>
		<td width=12% >Vérif éff.</td>
		<td><img src="images/trash.gif"></td>
	</tr>

<?



$req="select f.id_fiche_action, f.id, date, action ,type_action, ref_produit, ecart, delai, pilote, responsable, cmepa_date,
verif_eff_date 
from fiche_action fa 
left join fiche_5p_action f on f.id_fiche_action = fa.id 
left join fiche_5p fi on fi.id = f.id_fiche_5p 
where f.id_fiche_5p = '$util'";

$ic=0;
$res=my_query($req);

$nb_ligne=mysql_num_rows($res);
if ($nb_ligne==0){echo "<br><center>Aucun enregistrement trouvé</center>";exit;}

while ($ligne=mysql_fetch_array($res))
	{
	if (($ic % 2)==0){$cid="class= \"cel2\"";}else {$cid="class= \"cel1\"";}
	
	?>
	<tr align=center>
	<td <? echo $cid;?>  ><a class="b" href="fiche_action_ajouter.php?parent_ori1=1&util=<? echo $ligne["id_fiche_action"]?>"><? echo $ligne["id_fiche_action"];?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo datodf($ligne["date"]);?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo $ligne["action"];?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo $j_pa_type_action[$ligne["type_action"]];?></td>
	<td <? echo $cid;?>  ><? echo $ligne["ref_produit"];?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo $j_pa_ecart[$ligne["ecart"]];?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo datodf($ligne["delai"]);?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo $j_interne[$ligne["pilote"]];?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo $j_interne[$ligne["responsable"]];?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo datodf($ligne["cmepa_date"]);?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo datodf($ligne["verif_eff_date"]);?>&nbsp;</td>
	<td <? echo $cid;?>  ><? echo '<img style="cursor:pointer;" title="Supprimer cette fiche action" onclick="document.location.href=\'fiche_5p_ajouter.php?del_fa='.$ligne["id"].'\'" src="images/delete.gif">'; ?>&nbsp;</td>
	</tr>
	<?
	$ic++;
	if ($ic == $ligne_page){break;}
	}

?>

	</table>
</form>

<?
}
echo pied_page();
?>
