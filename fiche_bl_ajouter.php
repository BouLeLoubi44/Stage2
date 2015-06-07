<?
include("fonction.php");
if ((!d_ok(1118))){header("location: accueil.php");exit;}

if($bl_to_achat>0)
	{
	$i=0;
	//Création de la ligne [achat_ligne]
	$sql = 'select bll.*, e.id_unite as e_id_unite, e.pu as e_pu, a.r_achat , bl.adresse_liv, e.n_inv
	from fiche_bl_liste bll
	left join metrologie_equipement e on bll.id_equipement = e.id
	left join fiche_bl bl on bll.id_bl = bl.id
	left join affaire a on bl.id_affaire = a.id
	where bl.id='.$bl_to_achat.' and bll.id_achat_ligne = 0';
	
	$rs=my_query($sql);
	while($l=mysql_fetch_array($rs))
		{
		//bidouille rachid fq
		if($l['adresse_liv']==742)$l['adresse_liv']=24;
		if($l['adresse_liv']==743)$l['adresse_liv']=24;
		if($l['adresse_liv']==1837)$l['adresse_liv']=1836;

		$id_externe = nombre_de("select id_externe from externe_service where id = '".$l['adresse_liv']."'");
		if($l['article']=="FDV") $l['e_pu']=1;
		$vf = new valid_form ;
		$vf->add("id_affaire", $v_metrologie_affaire);
		$vf->add("id_of", $v_metrologie_of);
		$vf->add("id_destinataire", $v_metrologie_resp);
		$vf->add("id_externe", $id_externe);
		$vf->add("id_service", $l['adresse_liv']);
		$vf->add("id_cmd", $id_cmd);
		$vf->add("id_login", $id_login);
		$vf->add("date", date("Y-m-d"));
		$vf->add("ref", $l['article']);
		$vf->add("des", $l['des']);
		$vf->add("qte", $l['qte']);
		$vf->add("p_u", $l['e_pu']);
		$vf->add("unite", $l['e_id_unite']);
		$vf->add("old_qte", $l['qte']);
		$vf->add("old_pu", $l['e_pu']);
		$vf->add("old_unite_r", $l['e_id_unite']);
		$vf->add("m_ht", $l['qte']*$l['e_pu']);
		$vf->add("m_ttc", $l['qte']*$l['e_pu']*1.196);
		$vf->add("id_r_achat", $l['r_achat']);
		$vf->add("cat", $v_metrologie_cat);
		$vf->add("etat", 0);
		$vf->add("status", 2);
		$vf->add("ref", $l['n_inv']);
		$vf->add("d_besoin", date("Y-m-d",datotimestamp(date("Y-m-d"),7)));
		$vf->add("d_liv_prev", date("Y-m-d",datotimestamp(date("Y-m-d"),7)));
		$vf->add("code_tva", 4);
		$vf->add("gare", $v_metrologie_gare);
		$vf->add("id_bl_ligne", $l['id']);
		$id_new_ligne=$vf->insert("achat_ligne");
		$vf->log(__FILE__,__LINE__,DL_1);
		 
		 
		$vf = new valid_form ;
		$vf->add("id_achat_ligne",$id_new_ligne);
		$vf->update("fiche_bl_liste"," where id = ".$l["id"],"",1);
		$vf->log(__FILE__,__LINE__,DL_2);
		$i++;
		}

	$mess='<center><h3>'.$i.' lignes d\'achat ont été créées. </h3></center>';
	}



//Métrologie
if(isset($metrologie_bl))
	{
		
	$vf = new valid_form ;
	$vf->add("id_affaire", "13389");
	$vf->add("date", date("Y-m-d"));
	$vf->add("d_exp", date("Y-m-d"));
	$vf->add("exp_nom",$id_login );
	$vf->add("id_redacteur", $id_login);
	$vf->add("is_metrologie", 1);
	$_SESSION["fiche_bl_en_cour"]=$vf->insert("fiche_bl");
	$vf->log(__FILE__,__LINE__,DL_2);

	$r=my_query("select e.*,n.nom from metrologie_equipement e left join metrologie_equipement_nom n on e.id_nom=n.id where e.is_bl =1");
	while($l=mysql_fetch_array($r))
		{
		$vf = new valid_form ;
		$vf->add("id_bl", $_SESSION["fiche_bl_en_cour"]);
		$vf->add("article", $l['n_inv']);
		$vf->add("des", $l['nom']);
		$vf->add("qte", $l['qte']);
		$vf->add("partiel", 0);
		$vf->add("n_cde_c", "13389");
		$vf->add("nos_ref", "");
		$vf->add("id_equipement", $l['id']);
		$vf->insert("fiche_bl_liste");
		$vf->log(__FILE__,__LINE__,DL_2);
		}
		
	$vf = new valid_form ;
	$vf->add("id_bl", $_SESSION["fiche_bl_en_cour"]);
	$vf->add("article", "FDV");
	$vf->add("des", "FICHE DE VIE");
	$vf->add("qte", 1);
	$vf->add("partiel", 0);
	$vf->add("n_cde_c", "13389");
	$vf->add("nos_ref", "");
	$vf->add("id_equipement", "");
	$vf->insert("fiche_bl_liste");
	$vf->log(__FILE__,__LINE__,DL_2);
		
	$vf = new valid_form ;
	$vf->add("is_bl",0);
	$vf->update("metrologie_equipement"," where is_bl = 1","",1);
	$vf->log(__FILE__,__LINE__,DL_2);

	}
	
	//création de bl
if(isset($previsionnel_bl))
	{
		
	$previsionnel_bl=explode(';',$previsionnel_bl);
	
	$r1=my_query("select * from affaire where id = ".$id_affaire."");
	
	while($l1=mysql_fetch_array($r1))
		{
		$vf = new valid_form ;
		$vf->add("id_affaire", $id_affaire);
		$vf->add("date", date("Y-m-d"));
		$vf->add("d_exp", date("Y-m-d"));
		$vf->add("destinataire",$l1['c_technique'] );
		$vf->add("adresse_liv", $l1['client_id']);
		$vf->add("id_redacteur", $id_login);
		$vf->add("exp_nom",$id_login );
		$_SESSION["fiche_bl_en_cour"]=$vf->insert("fiche_bl");
		$vf->log(__FILE__,__LINE__,DL_2);
		}
		
	$r2=my_query("select * from affaire_prev where id in (".tabtosql($previsionnel_bl).")");
	
	while($l2=mysql_fetch_array($r2))
		{
		$vf = new valid_form ;
		$vf->add("id_bl", $_SESSION["fiche_bl_en_cour"]);
		$vf->add("article", $l2['n_cde_c']);
		$vf->add("des", $l2['des']);
		$vf->add("qte", $l2['qte']);
		$vf->add("partiel", 0);
		$vf->add("n_cde_c", $l2['n_cde_c']);
		$vf->add("nos_ref", $id_affaire);
		$vf->add("id_equipement", $l2['id']);
		$vf->insert("fiche_bl_liste");
		$vf->log(__FILE__,__LINE__,DL_2);
		}
	}

if ($save == 1)
	{

	$vf = new valid_form ;
	$vf->add("id_affaire", $id_affaire);
	$vf->add("date", date("Y-m-d"));
	$vf->add("d_exp", dftoda($d_exp));
	$vf->add("transporteur", $transporteur);
	$vf->add("nb_colis", $nb_colis);
	$vf->add("poid", $poid);
	$vf->add("destinataire", $destinataire);
	$vf->add("adresse_liv", $adresse_liv);
	$vf->add("adresse_liv_txt", $adresse_l);
	$vf->add("doc", $doc);
	$vf->add("blcc", $blcc);


	$bloquer=0;
	if ($_SESSION["fiche_bl_en_cour"] > 0)
		{
		$bloquer=nombre_de("select clos from fiche_bl where id = '".$_SESSION["fiche_bl_en_cour"]."' limit 1");
		if($bloquer == 0)$vf->update("fiche_bl"," where id = '".$_SESSION["fiche_bl_en_cour"]."';");
		}
		else
		{
		$vf->add("exp_nom",$id_login );
		$vf->add("id_redacteur", $id_login);
		if(isset($is_metrologie)) $vf->add("is_metrologie", $is_metrologie);
		$_SESSION["fiche_bl_en_cour"]=$vf->insert("fiche_bl");
		}
	$vf->log(__FILE__,__LINE__,DL_2);

	if($bloquer==0)
		{		
		for ($i=0;$i < count($des);$i++)
			{
			if (($article[$i] =="")and($des[$i] ==""))
				{
				if ($id_bl_liste[$i] > 0)
					{
					del_visit(__FILE__,__LINE__,DL_1,"fiche_bl_liste","where id = '$id_bl_liste[$i]';");
					}
				continue;
				}
			//if($article[$i] ==""){$qte[$i]=0;}
			$vf = new valid_form ;
			$vf->add("id_bl", $_SESSION["fiche_bl_en_cour"]);
			$vf->add("article", $article[$i]);
			$vf->add("des", $des[$i]);
			$vf->add("qte", $qte[$i]);
			$vf->add("partiel", $partiel[$i]);
			$vf->add("n_cde_c", $n_cde_c[$i]);
			$vf->add("of_client", $of_client[$i]);
			$vf->add("nos_ref", $nos_ref[$i]);
			if(isset($is_metrologie)) $vf->add("id_equipement", $id_equipement[$i]);
			
			if ($id_bl_liste[$i] > 0)
				{
				$vf->update("fiche_bl_liste"," where id = '$id_bl_liste[$i]'");
				}
				else
				{
				$vf->insert("fiche_bl_liste");
				}
			$vf->log(__FILE__,__LINE__,DL_2);
			}
		}
	}



if($decloturer>0)
	{
	$vf = new valid_form ;
	$vf->add("clos", 0);
	$vf->add("mail_send", "0000-00-00");
	$vf->update("fiche_bl"," where id = $decloturer");
	$vf->log(__FILE__,__LINE__,DL_2);
	echo "Document décloturé";
	}

if($util > 0) $_SESSION["fiche_bl_en_cour"] = $util;
else if($new > 0) $_SESSION["fiche_bl_en_cour"] = 0;
$util = $_SESSION["fiche_bl_en_cour"];



$butt="Ajouter un Bon de Livraison / Bon de livraison - Déclaration de conformité";

if ($util > 0)
{

$sql="SELECT * FROM fiche_bl where id = '$util'";
$res=my_query($sql);
$nb_ligne=mysql_num_rows($res);
$row = mysql_fetch_array($res);

if ($duplique==1)
	{
	$util_old=$util;
	$_SESSION["fiche_bl_en_cour"]=0;
	$clos=0;
	}
	else
	{
	$_SESSION["fiche_bl_en_cour"]=$util;
	$id = $row["id"];
	$butt="$bl_t N° ".format_0($id,6);
	$clos = $row["clos"];
	$util_old=0;
	}
	
$id_affaire = $row["id_affaire"];
$id_redacteur = $row["id_redacteur"];
$d_exp = datodf($row["d_exp"]);
$transporteur = $row["transporteur"];
$nb_colis = $row["nb_colis"];
$poid = $row["poid"];
$destinataire = $row["destinataire"];
$adresse_liv = $row["adresse_liv"];
$adresse_l = $row["adresse_liv_txt"];
$exp_nom = $row["exp_nom"];
$doc = $row["doc"];
$blcc = $row["blcc"];
$is_metrologie = $row["is_metrologie"];


if($blcc > 0){$bl_t = "Bon de livraison - Déclaration de conformité";}else{$bl_t = "Bon de livraison";}
if($adresse_l=="")$adresse_l=adresse($adresse_liv,"\n",1);
}
else
{
$nb_colis=1;
$transporteur  = 295;
if($_SESSION['fiche_bl_pere']==133)$id_affaire=$_SESSION['affaire_en_cour'];
$exp_nom = $id_login;
$blcc=0;
}

if(nombre_de("select id from affaire where id = '$id_affaire' limit 1")>0){$affaire_ok=1;}else if ($_SESSION["fiche_bl_en_cour"] > 0)echo "<h3>Numéro d'affaire incorrecte !</h3>";

$d_exp ='<input readonly onclick="return showCalendar(\'sel1\', \'%d/%m/%Y\');"  id=sel1  type="text" maxlength="10" name="d_exp" size="12" value="'.$d_exp.'">';
$id_affaire ='<input type="text" name="id_affaire" size=10 maxlength=10 value="'.$id_affaire.'" >';

$transporteur =liste_db("select id , societe  from externe_service  where activite = 2 order by societe asc",$transporteur,"transporteur");
$destinataire ='<input id="id_destinataire" type="hidden" name="id_destinataire" value="" ><input id="destinataire" type="text" name="destinataire" size=30 maxlength=30 value="'.$destinataire.'" >';
$nb_colis ='<input type="text" name="nb_colis" size=10 maxlength=10 value="'.$nb_colis.'" >';
$poid ='<input type="text" name="poid" size=10 maxlength=10 value="'.$poid.'" >';

$adresse_liv ='<input id="adresse_liv" type="hidden" name="adresse_liv" value="'.$adresse_liv.'" ><textarea id="adresse_l" rows=4 cols=64 name="adresse_l" >'.$adresse_l.'</textarea>';

$exp_nom=idtonom($exp_nom,0,0,1);

$doc ='<textarea id="doc" rows=8 cols=100 name="doc" >'.$doc.'</textarea>';

if($_SESSION["fiche_bl_en_cour"] > 0){$txt="Bon de livraison N° ".$_SESSION["fiche_bl_en_cour"];}else{$txt = "Ajouter un bon de livraison";}

if(!($parent<>""))$parent=parent(1118);

 
$page = new page;
$page->head($txt);
$page->body("");
$page->entete($txt);
$page->add_button(1,0);
$page->add_button(2,1,$parent);
$page->add_button(3,0);
$page->add_button(0,2);
if(!($clos > 0))$page->add_button(4,1,"validation();");
$page->add_button(0,2);
if (($_SESSION["fiche_bl_en_cour"] > 0)and($affaire_ok==1))
{
if(!($clos > 0))$page->add_button(26,1,"fiche_bl_imprimer.php","Aperçu de la fiche","_blank");
$page->add_button(0,2);
if(d_ok(1120))$page->add_button($clos>0?27:25,1,"fiche_bl_imprimer.php?definitive=1","Impression définitive","_blank");
}

if (($_SESSION["fiche_bl_en_cour"] > 0)and(($id_login==$id_redacteur) or ($id_util==1))and($clos==1))
{
$page->add_button(0,2);
if(d_ok(1118))$page->add_button(38,1,"fiche_bl_ajouter.php?decloturer=".$_SESSION["fiche_bl_en_cour"],"Décloturer ce BL");
}

if (($_SESSION["fiche_bl_en_cour"] > 0)and($id_util==1))
{
$page->add_button(0,2);
if(d_ok(1116))$page->add_button(6,1,"fiche_bl_consulter.php?del_id=".$_SESSION["fiche_bl_en_cour"],"Supprimer ce BL");
}


if($is_metrologie && $clos>0)
	{
	$page->add_button(0,2);
	$page->add_button(42,1,"fiche_bl_ajouter.php?bl_to_achat=".$_SESSION["fiche_bl_en_cour"]."&parent_id=1118", "Création d'une commande via le BL");
	}
$page->fin_entete();
$page->datescript();
echo $mess;
?>
<script src="js/ajax.js" type="text/javascript"></script>

<script LANGUAGE="JavaScript">

fois=0;

function validation()
{
		if (!(document.formulaire.d_exp.value  != "")) {
			alert("Veuillez saisir le champ 'Date'.");
			document.formulaire.d_exp.focus();
			return false;
		}
		if (document.formulaire.util.value != document.formulaire.util_old.value)
		{
			document.formulaire.util.value= 0;
		}
		
		if (document.formulaire.destinataire.value == "") {
			alert("Veuillez saisir le champ 'Destinataire'.");
			document.formulaire.destinataire.focus();
			return false;
		}
		if (document.formulaire.adresse_l.value  == "") {
			alert("Veuillez saisir le champ 'Adresse'.");
			document.formulaire.adresse_l.focus();
			return false;
		}
		if (!(document.formulaire.id_affaire.value  > 0)) {
			alert("Veuillez saisir le champ 'Affaire'.");
			document.formulaire.id_affaire.focus();
			return false;
		}
if(fois==0){fois++;document.formulaire.submit();}
}



function bl_auto(of , ligne)
	{
		if(of.length== 6 && !isNaN(of))
			{	
			var req = null;
			req=get_xhr();
			req.onreadystatechange = function()
			{
				if(req.readyState == 4)
					{
					if(req.status == 200)
						{
						tab=xmltotab1(req.responseXML);
						if(tab[1]==1)
							{
							if(document.getElementById("des"+ligne).value == ""){document.getElementById("des"+ligne).value=tab[3];}
							if(document.getElementById("qte"+ligne).value == ""){document.getElementById("qte"+ligne).value=tab[5];}
							if(document.getElementById("n_cde_c"+ligne).value == ""){document.getElementById("n_cde_c"+ligne).value=tab[7];}
							if(document.getElementById("of_client"+ligne).value == ""){document.getElementById("of_client"+ligne).value=tab[11];}
							if(document.getElementById("article"+ligne).value == ""){document.getElementById("article"+ligne).value=tab[9];}
							}
						}
					else
						{				
						alert('Probleme avec la requete !');
						}
					};
			}
			var url ="req_ajax.php?id_req=29&of="+of;
			req.open("POST", url, true);
			req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			req.send(null);
			}
	}

</script>
<?

if(($new == 1)and($bltype <> 1)){$blcc=1;}

echo $erreur;
if($clos == 1)echo '<center><h3>Ce document à été cloturé.</h3></center>';
?>


<form name="formulaire"  method="post" action="fiche_bl_ajouter.php?parent_id=1170" target="principal">

<input  type="hidden" name="util" value="<? echo $_SESSION["fiche_bl_en_cour"];?>">
<input  type="hidden" name="util_old" value="<? echo $util_old;?>">
<input  type="hidden" name="save" value="1">

<TABLE TABLE class=forumline cellSpacing=1 cellPadding=4 width="90%" align=center>
<TR>
<TD align="center" class="m3" colspan="2"><? echo $butt;?></TD>
</TR>
<tr>
<td class ="cel1" >&nbsp;Date d'expedition</td>
<td class ="cel2" >&nbsp;<? echo $d_exp;?></td>
</tr>
<tr>
<td class ="cel1" >&nbsp;N° affaire interne</td>
<td class ="cel2" >&nbsp;<? echo $id_affaire; ?> </td>
</tr>
<tr>
<td class ="cel1" >&nbsp;Transporteur</td>
<td class ="cel2" >&nbsp;<? echo $transporteur; ?> </td>
</tr>
<tr>
<td class ="cel1" >&nbsp;Nombre de colis</td>
<td class ="cel2" >&nbsp;<? echo $nb_colis; ?></td>
</tr>
<tr>
<td class ="cel1" >&nbsp;Poids</td>
<td class ="cel2" >&nbsp;<? echo $poid; ?> KG</td>
</tr>
<tr>
<td class ="cel1" >&nbsp;Destinataire</td>
<td class ="cel2" >&nbsp;<? echo $destinataire; ?></td>
</tr>
<tr>
<td class ="cel1" >&nbsp;Adresse de livraison</td>
<td class ="cel2" >&nbsp;<? echo $adresse_liv.popup2("select_adresse.php?opener=1&id_case=adresse_liv&id_case_txt=adresse_l&fact=select_adresse",'...',1000,700,'select_adresse');// ?></td>
</tr>
<tr>
<td class ="cel1" >&nbsp;Expediteur </td>
<td class ="cel2" >&nbsp;<? echo $exp_nom; ?></td>
</tr>
<tr>
<td class ="cel1" >&nbsp;Déclaration de conformité </td>
<td class ="cel2" >
<input class=cel2 type="radio" name="blcc" value="0" <? if ($blcc==0){echo "checked=true";} ?>>Non&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input class=cel2 type="radio" name="blcc" value="1" <? if ($blcc==1){echo "checked=true";} ?>>Oui
</td>
</tr>
</table>
<br>
<center>Mettre "0" dans le champ Article pour laisser une ligne vierge lors de l'impression.</center>
<br>

<TABLE class=forumline  cellSpacing=1 cellPadding=4 width="90%" align=center>
<TR class="m3">
<td  >Article / Ref</td>
<td  >Désignation</td>
<td  >Qté</td>
<td  >Qté Totale</td>
<td  >N° Cde Client</td>
<td  >N° OF Client</td>
<td  >Nos réf.</td>
</tr>

<?
//select_adresse.php?id_case=adresse_liv&id_case_txt=adresse_l")
//select_contact1.php?mode=9&id_case=id_destinataire&id_case_txt=destinataire&id_case_adr=adresse_liv&id_case_adr_txt=adresse_l&get_adresse=1")

$i=0;
$sql="SELECT * FROM fiche_bl_liste where id_bl = '".$util."'  order by id asc";
$res=my_query($sql);
$nb_ligne = mysql_num_rows($res);

while($row = mysql_fetch_array($res))
	{
	if($row["partiel"]==0){$c0 = "checked=true";$c1="";}else{$c1 = "checked=true";$c0="";}
	if($_SESSION["fiche_bl_en_cour"]>0){$id_bl_ligne=$row["id"];}else{$id_bl_ligne=0;}
	echo '
	<tr align=center>
	<td class ="cel2" ><input type=hidden name="id_bl_liste['.$i.']" value="'.$id_bl_ligne.'"><input type="text" name="article['.$i.']" size=15 maxlength=20 value="'.$row["article"].'"></td>
	<td class ="cel1" ><input type="text" name="des['.$i.']" size=60 maxlength=65 value="'.$row["des"].'"></td>
	<td class ="cel2" ><input type="text" name="qte['.$i.']" size=5 maxlength=5 value="'.$row["qte"].'"></td>
	<td class ="cel1" >
	<input class=cel2 type="radio" name="partiel['.$i.']" value="0" '.$c0.'>Oui&nbsp;
	<input class=cel2 type="radio" name="partiel['.$i.']" value="1" '.$c1.'>Non</td>
	<td class ="cel1" ><input type="text" name="n_cde_c['.$i.']" size=10 maxlength=10 value="'.$row["n_cde_c"].'"></td>
	<td class ="cel1" ><input type="text" name="of_client['.$i.']" size=10 maxlength=10 value="'.$row["of_client"].'"></td>
	<td class ="cel1" ><input type="text" name="nos_ref['.$i.']" size=10 maxlength=10 value="'.$row["nos_ref"].'" ></td>
	</tr>';
	$i++;
	}
?>
<?


if(($new == 1)and($bltype <> 1))
{
$i=0;
$sql="SELECT * FROM fiche_bl_pre where avion like '$bltype' ";
$res=my_query($sql);
	while($row = mysql_fetch_array($res))
		{
		$c0 = "checked=true";$c1="";
		echo '
		<tr align=center>
		<td class ="cel2" ><input type=hidden name="id_bl_liste['.$i.']" value=""><input type="text" name="article['.$i.']" size=15 maxlength=15 value=""></td>
		<td class ="cel1" ><input type="text" name="des['.$i.']" size=60 maxlength=65 value="'.$row["2"].'"></td>
		<td class ="cel2" ><input type="text" name="qte['.$i.']" size=5 maxlength=5 value="'.$row[3].'"></td>
		<td class ="cel1" >
		<input class=cel2 type="radio" name="partiel['.$i.']" value="0" '.$c0.'>Oui&nbsp;
		<input class=cel2 type="radio" name="partiel['.$i.']" value="1" '.$c1.'>Non</td>
		<td class ="cel1" ><input type="text" name="n_cde_c['.$i.']" size=10 maxlength=10 value=""></td>
		<td class ="cel1" ><input type="text" name="of_client['.$i.']" size=10 maxlength=10 value=""></td>
		<td class ="cel1" ><input type="text" name="nos_ref['.$i.']" size=10 maxlength=10 value="" ></td>
		</tr>';
		$i++;
		}
}

$nb=30;
if ($nb_ligne>=20) {$nb=$nb_ligne+20;}
for($j=$i;$j<$nb;$j++)
{
	echo '
	<tr align=center>
	<td class ="cel2" ><input type=hidden name="id_bl_liste['.$j.']" value="0"><input type="text" id="article'.$j.'" name="article['.$j.']" size=15 maxlength=15 ></td>
	<td class ="cel1" ><input type="text" id="des'.$j.'" name="des['.$j.']" size=60 maxlength=65 ></td>
	<td class ="cel2" ><input type="text" id="qte'.$j.'" name="qte['.$j.']" size=5 maxlength=5 ></td>
	<td class ="cel1" >
	<input class=cel2 type="radio" name="partiel['.$j.']" value="0" checked>Oui &nbsp;
	<input class=cel2 type="radio" name="partiel['.$j.']" value="1" >Non</td>
	<td class ="cel1" ><input type="text" id="n_cde_c'.$j.'" name="n_cde_c['.$j.']" size=10 maxlength=10 ></td>
	<td class ="cel1" ><input type="text" id="of_client'.$j.'" name="of_client['.$j.']" size=10 maxlength=10 ></td>
	<td class ="cel1" ><input type="text" id="nos_ref'.$j.'" name="nos_ref['.$j.']" size=10 maxlength=10 onchange="bl_auto(this.value,'.$j.')" ></td>
	</tr>';

}
?>

</table>

<br>
<TABLE class=forumline  cellSpacing=1 cellPadding=4 width="90%" align=center>
<tr>
<td class ="cel1" width="25%">&nbsp;Documents associés / Remarques<br>7 lignes maximum</td>
<td class ="cel2" ><? echo $doc; ?></td>
</tr>
</TABLE>
</form>
<?
echo pied_page();
?>
