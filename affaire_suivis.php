<?
include("fonction.php");
if (!d_ok(138)){header("location: accueil.php");exit;}
$_SESSION['en_cour']="affaire_suivis.php";

$j_affaire_resp[0]='';
$j_affaire_resp[1]='Chargé d\'affaire';
$j_affaire_resp[2]='Préparateur';
$j_affaire_resp[3]='R. achats';

$j_affaire_respa[0]='';
$j_affaire_respa[1]='a.r_projet';
$j_affaire_respa[2]='a.r_real';
$j_affaire_respa[3]='a.r_achat';

$page = new page;
$page->head("Suivis des affaires");
$page->body();
$page->entete("Suivis des affaires");
$page->add_button(1,0);
$page->add_button(2,1,parent(138));
$page->add_button(3,0);
$page->add_button(51,1,"affaire_suivi_indicateurs.php","Indicateurs COMOP");
$page->fin_entete();
$page->datescript();

if (isset($r_tout))$r_tout=1;
if ($mode == 9 || !isset($_SESSION['affaire_suivis_aff']))
{
$r_aff='';
$r_type='';
$r_client='';
$r_des='';
$r_tout=0;
$r_offre='';
$r_devis='';
$r_etat=1;
$r_refcde='';
$r_resp=0;
$r_resp2=0;
$mode=1;
$r_pere='';
//-----------------------------------------------------------
$r_date='';
$r_date2='';
//-----------------------------------------------------------
}

if ($mode == 1)
{
$_SESSION['affaire_suivis_aff'] = $r_aff;
$_SESSION['affaire_suivis_type'] = $r_type;
$_SESSION['affaire_suivis_client'] = $r_client;
$_SESSION['affaire_suivis_pere'] = $r_pere;
//$_SESSION['affaire_suivis_des'] = $r_des;
$_SESSION['affaire_suivis_tout'] = $r_tout;
//$_SESSION['affaire_suivis_refcde'] = $r_refcde;
//$_SESSION['affaire_suivis_offre'] = $r_offre;
//$_SESSION['affaire_suivis_devis'] = $r_devis;
$_SESSION['affaire_suivis_etat'] = $r_etat;
$_SESSION['affaire_suivis_resp'] = $r_resp;
$_SESSION['affaire_suivis_resp2'] = $r_resp2;
//-----------------------------------------------------------
$_SESSION['affaire_suivis_date1'] = $r_date;
$_SESSION['affaire_suivis_date2'] = $r_date2;
//-----------------------------------------------------------
}
if($p_en > 0)$_SESSION["affaire_consulter_p_en"]=$p_en;
if($mode > 0){$p_en=1;}else{$p_en=$_SESSION["affaire_consulter_p_en"];}
if(!($p_en>0))$p_en=1;
$_SESSION["affaire_consulter_p_en"]=$p_en;


$affaire_afficher_req="";

$r_aff=$_SESSION['affaire_suivis_aff'];
$r_type=$_SESSION['affaire_suivis_type'];
$r_client=$_SESSION['affaire_suivis_client'];
$r_des=$_SESSION['affaire_suivis_des'];
$r_tout= $_SESSION['affaire_suivis_tout'];
//$r_refcde=$_SESSION['affaire_suivis_refcde'];
//$r_offre=$_SESSION['affaire_suivis_offre'];
//$r_devis=$_SESSION['affaire_suivis_devis'];
$r_etat=$_SESSION['affaire_suivis_etat'];
$r_resp=$_SESSION['affaire_suivis_resp'];
$r_resp2=$_SESSION['affaire_suivis_resp2'];
$r_aff=$_SESSION['affaire_suivis_aff'];
$r_pere=$_SESSION['affaire_suivis_pere'];
//-----------------------------------------------------------
$r_date   = $_SESSION['affaire_suivis_date1'];
$r_date2   = $_SESSION['affaire_suivis_date2'];
//-----------------------------------------------------------


if ($r_aff > 0)$affaire_afficher_req .= " and a.id = '$r_aff' ";
//if ($r_type > 0)$affaire_afficher_req .= " and a.type = '$r_type' ";
if (is_array($r_type))$affaire_afficher_req .= " and a.type in (".tabtosql($r_type).") ";
if ($r_client <> '')$affaire_afficher_req .= " and a.client like '%$r_client%' ";
if ($r_pere <> '')$affaire_afficher_req .= " and a.pere like '%$r_pere%' ";
if ($r_des <> '')$affaire_afficher_req .= " and ((a.designation1 like '%$r_des%' )or (a.designation2 like '%$r_des%')) ";
//if ($r_refcde <> '')$affaire_afficher_req .= " and a.ref_cde like '%$r_refcde%' ";
//if ($r_offre <> '')$affaire_afficher_req .= " and a.ref_ao like '%$r_offre%' ";
//if ($r_devis <> ''){if ($r_devis == 2){$s=" not ";}else{$s="";};$affaire_afficher_req .= " and ref_devis $s like '' ";}
//if ($r_etat <> '')$affaire_afficher_req .= " and a.etat = '$r_etat' ";
if (is_array($r_etat))$affaire_afficher_req .= " and a.etat in (".tabtosql($r_etat).") ";
if (($r_resp > 0)and($r_resp2 > 0))$affaire_afficher_req .= " and ".$j_affaire_respa[$r_resp]." =  $r_resp2 ";
//--------------------------------------------------------------------------------------------------------------------
//Date à date
if((isdf($r_date))and (isdf($r_date2))) {$affaire_afficher_req.=" AND a.date BETWEEN '".dftoda($r_date)."' AND '".dftoda($r_date2)." ' ";}
elseif(isdf($r_date)) {$affaire_afficher_req.=" AND a.date = '".dftoda($r_date)." ' ";}

//--------------------------------------------------------------------------------------------------------------------


$_SESSION["affaire_afficher_req"]=$affaire_afficher_req;

if($_SESSION['affaire_suivis_trier']==''){$_SESSION['affaire_suivis_trier']='id ';}
if(isset($trier)){$_SESSION['affaire_suivis_trier']=$trier;}

if($_SESSION['affaire_suivis_ordre']==''){$_SESSION['affaire_suivis_ordre']='desc ';}
if(isset($ordre)){$_SESSION['affaire_suivis_ordre']=$ordre;}

$tf= new tri;
$tf->tri_ec=$_SESSION['affaire_suivis_trier'];
$tf->ordre_ec=$_SESSION['affaire_suivis_ordre'];
$tf->page_ec=$_SESSION['en_cour'];

$req = "
select a.*, concat(i1.nom,' ',i1.prenom) as preparateur, concat(i2.nom,' ',i2.prenom) as charge_aff
from affaire a
left join interne i1 on a.r_real=i1.id
left join interne i2 on a.r_projet=i2.id
where 1 $affaire_afficher_req  order by ".$_SESSION['affaire_suivis_trier']." ".$_SESSION['affaire_suivis_ordre']." , id desc;
";

$rs=my_query($req);

$ligne_page=$lpp ;
$p_pf=20;

function idtoligne($id,$res)
{
$i=1;
  while ($ligne=mysql_fetch_array($res))
  {
  if ($id == $ligne["id"]){$x= $i;break;}
  $i++;
  }
  if (!isset($x)){$x=0;}
  return $x;
}

if ($af_enc > 0)
{
 $lx=idtoligne($af_enc,$res3);
 if ($lx <> 0)
     {
     header("location:affaire.php?p_en=".ceil($lx / $ligne_page)."&id_affaire=".$af_enc);
     exit;
     }
}

?>

<center>

<!--filtre-->
<form method="post" style="position:relative;z-index:1;" name="f1" action="affaire_suivis.php"  target="principal">
<input type=hidden name="mode" value="1">
<table  class=forumline cellSpacing=1 cellPadding=2 width="98%" align=center border=0>
<tr class=m3>
<td class=m3>
Affaire:
<input size=6  maxlength=5 type=text name="r_aff" value="<?echo $r_aff;?>" onchange="f1.submit();">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--																						-->
Date &nbsp;
<input onclick="return showCalendar('sel1','%d/%m/%Y');" id=sel1 class="button2" size=11 type="text"  name="r_date" value="<? echo $r_date; ?>"><? echo calendar('sel1');?>
&nbsp; &nbsp; à &nbsp; &nbsp;
<input onclick="return showCalendar('sel2','%d/%m/%Y');" id=sel2 class="button2" size=11 type="text"  name="r_date2" value="<? echo $r_date2; ?>"><? echo calendar('sel2');?>
&nbsp; &nbsp;&nbsp; &nbsp;
<!--																						-->

Client:
<input size=15  maxlength=20 type=text name="r_client" value="<?echo $r_client;?>" onchange="f1.submit();">
&nbsp; &nbsp; &nbsp;

Désignation:
<input size=15  maxlength=20 type=text name="r_des" value="<?echo $r_des;?>" onchange="f1.submit();">
&nbsp; &nbsp; &nbsp;

<? echo liste_ms($j_affaire_etat,$r_etat,"r_etat","Etat"); ?>
&nbsp; &nbsp; &nbsp;

<? echo liste_ms($j_affaire_type,$r_type,"r_type","Type"); ?>
&nbsp; &nbsp; &nbsp;


</td>
<td rowspan="2"><input type=submit id=button3 value="Go"><br /><input id=button3 type=button value="Clear" onclick="document.location.href='affaire_suivis.php?mode=9';"></td>
</tr>
<tr class=m3>
<td>

Responsable :
<? echo liste_d2($j_affaire_resp,$r_resp,"r_resp"); ?>
&nbsp;
<? echo liste_db("select id , concat(nom,' ',prenom) from interne where r_affaire = 1 or r_projet = 1 order by nom asc",$r_resp2,"r_resp2" ,'onchange="formulaire1.submit();"','<option value="0"></option>');?>
&nbsp; &nbsp; &nbsp;
<label><input class=m3 type=checkbox name="r_tout" <? if ($r_tout == 1)echo 'checked';?> > Sur 1 page</label> &nbsp; &nbsp;

</td>
</tr>
</table>
</form>

<!--tableau-->
<table  class=forumline cellSpacing=1 cellPadding=2 width="100%" align=center border=0>
<tr>
	<td class= "m3" width=4% height=20><? $tf->aff("id","Affaire");?></td>
	<td class= "m3" width=4% heigh=20><? $tf->aff("a.date","Date Création");?></td>
	<td class= "m3" width=8% >Client</td>
	<td class= "m3" width=15%><? $tf->aff("a.designation1","Designation");?></td>
	<td class= "m3" width=15%><? $tf->aff("a.designation2","Designation complémentaire");?></td>
	<td class= "m3" width=8%><? $tf->aff("charge_aff","Chargé d'affaire");?></td>
	<td class= "m3" width=8%><? $tf->aff("preparateur","Préparateur");?></td>
	<td class= "m3" width=5%><? $tf->aff("a.montant_devis","Montant devis");?></td>
	<td class= "m3" width=5%><? $tf->aff("a.montant_cde","Marché");?></td>
	<td class= "m3" width=2%><? $tf->aff("a.risque","Risque");?></td>
	<td class= "m3" width=5%><? $tf->aff("a.delai_rep","Delai de réponse devis");?></td>
	<td class= "m3" width=5%><? $tf->aff("d_reponse_ao","Date Réponse AO");?></td>
	<td class= "m3" width=5%><? $tf->aff("a.delai_cde_i","Délai livraison");?></td>
	<td class= "m3" width=12%><? $tf->aff("a.observation","Observation");?></td>
	<td class= "m3" width=3%><? $tf->aff("a.etat","Etat");?></td>
        <td class= "m3" width=3%><? $tf->aff("a.d_soldee","Cloture");?></td>
        <td class= "m3" width=4% heigh=20><? $tf->aff("pere","Affaire mère");?></td>
<!---->												<!---->
	<td class= "m3" width=4% heigh=20><? $tf->aff("a.date_cde","Date Commande");?></td>
	<td class= "m3" width=4% heigh=20><? $tf->aff("a.d_relance","Date Relance");?></td>
	<td class= "m3" width=4% heigh=20><? $tf->aff("a.cause_creation","Cause Création");?></td>	
	<td class= "m3" width=4% heigh=20><? $tf->aff("a.cause_perte","Cause Perte");?></td>
	<td class= "m3" width=4% heigh=20><? $tf->aff("a.liv_commentaire","Commentaires");?></td>
<!---->												<!---->

</tr>

<?

$nb_ligne=mysql_num_rows($rs);
if ($nb_ligne==0){echo "<br>Aucun enregistrement trouvé<br>";exit;}
$prem_ligne=((($p_en - 1) * $ligne_page));

mysql_data_seek($rs,0);
mysql_data_seek($rs,$prem_ligne);

$ic=0;
while ($ligne=mysql_fetch_array($rs))
{
  if (($ic % 2)==0){$cid="class= \"cel2\"";}else {$cid="class= \"cel1\"";}
  if ($ligne["etat"]==0){$status='2';}
  else if ($ligne["etat"]==2){$status='3';}
  else if ($ligne["etat"]==1){$status='1';}
  else if ($ligne["etat"]==3){$status='0';}
if (($id_util==7) or ($id_util==10))
  {$lien='<a class="b" href=" of_consulter.php?parent_ori=1&mode=1&id_affaire='.$ligne["id"].'">'.format_0($ligne["id"],5).'</a>';}
else
  {$lien='<a class="b" href=" affaire.php?parent=affaire_suivis.php&id_affaire='.$ligne["id"].'">'.format_0($ligne["id"],5).'</a>';}

  if ($ligne["etat"]==0){$status='6';}
  else if ($ligne["etat"]==1){$status='2';}
  else if ($ligne["etat"]==2){$status='5';}
  else if ($ligne["etat"]==3){$status='1';}
  else if ($ligne["etat"]==4){$status='3';}
  else if ($ligne["etat"]==5){$status='0';}
  else if ($ligne["etat"]==6){$status='4';}
?>
  <tr >
  <td align=center <? echo $cid;?> ><?echo $lien;?></td>
  <td align=center <? echo $cid;?> ><?echo datodf($ligne["date"]);?></td>
  <td <? echo $cid;?> ><? echo $ligne["client"];?> </td>
  <td <? echo $cid;?> ><? echo $ligne["designation1"];?> </td>
  <td <? echo $cid;?> ><? echo $ligne["designation2"];?> </td>
  <td <? echo $cid;?> ><? echo $ligne["charge_aff"];?> </td>
  <td <? echo $cid;?> ><? echo $ligne["preparateur"];?> </td>
  <td align=right  <? echo $cid;?> ><? echo nformat($ligne["montant_devis"],'',1,0);?> </td>
  <td align=right  <? echo $cid;?> ><? echo nformat($ligne["montant_cde"],'',1,0);?> </td>
  <td align=center <? echo $cid;?> ><? echo $ligne["risque"];?> </td>
  <td align=center <? echo $cid;?> ><? echo datodf($ligne["delai_rep"]);?> </td>
  <td align=center <? echo $cid;?> ><? echo datodf($ligne["d_reponse_ao"]);?> </td>
  <td align=center <? echo $cid;?> ><? echo datodf($ligne["delai_cde_i"]);?> </td>
  <td align=left  <? echo $cid;?> ><? echo $ligne["observation"];?> </td>
  <td align=center <? echo $cid;?> ><img width=12 title="<? echo $j_affaire_etat[$ligne["etat"]];?>" height=12 src="images/statut<? echo $status;?>.gif"> </td>
  <td align=center <? echo $cid;?> ><? echo datodf($ligne["d_soldee"]);?> </td>
  <td align=center <? echo $cid;?> ><? echo $ligne["pere"];?> </td>
<!---->												<!---->  
  <td align=center <? echo $cid;?> ><?echo datodf($ligne["date_cde"]);?></td>
  <td align=center <? echo $cid;?> ><?echo datodf($ligne["d_relance"]);?></td>
  <td align=center <? echo $cid;?> ><?echo $j_affaire_cause_creation[$ligne["cause_creation"]];?></td> 
  <td align=center <? echo $cid;?> ><?echo $j_affaire_cause_perte[$ligne["cause_perte"]];?></td> 
  <td align=center <? echo $cid;?> ><?echo $ligne["liv_commentaire"];?></td>
<!---->												<!---->
  
  </tr>
  <?
  $ic++;
  if (($r_tout == 0 )and($ic == $ligne_page)){break;}

}

echo "</table>";

if ($r_tout == 0 )echo bar("affaire_suivis.php","",$p_en,$nb_ligne,$ligne_page);
echo pied_page();
?>
