<?
include("fonction.php");
include("fckeditor.php") ;

if ((!d_ok(102))){header("location: accueil.php");exit;}

$j_entite = dbtodata("select id , nom from entite; ");


function supprime_gamme($id)
{
if(nombre_de("select * from of where id_gamme = '$id' ") > 0)
	{
	echo "Cette gamme ne peut être supprimé car elle est utilisée !";
	}
else
	{
	if(nombre_de("select last from gamme where id = '$id' ")<>1)
		{
		echo "Suprimer les gammes en commençant par la dernière!";
		}
		else
		{
		$ref=nombre_de("select ref from gamme where id = '$id' ");
		del_visit(__FILE__,__LINE__,DL_1,"phase","where id_gamme = '$id'");
		del_visit(__FILE__,__LINE__,DL_1,"gamme","where id = '$id' LIMIT 1 ");
		my_query("update gamme set last = 1 where ref = '$ref' order by id desc limit 1 ");
		}
	}

}

if(($del_id > 0)and(d_ok(111)))
{
supprime_gamme($del_id);

}


if ($util>0)
{
	if (($prev > 0) or ($next > 0))
		{
		if ($prev > 0)$cl=" asc ";
		if ($next > 0)$cl=" desc ";
		
		$sql = "select p.id, p.ref from piece p  where 1 ".$_SESSION['piece_consulter_req']." or id = $util order by ".$_SESSION['piece_consulter_trier']." $cl "; ;
		$util_0=$util;
		if($res=my_query($sql))
		{
		while($row2 = mysql_fetch_array($res))
			{
			if ($util==$row2[0])
				{
				$util=$util_0;
				break;
				}
			$util_0=$row2[0];
			}
		}
	}
}



 
if ($save == 1)
{

  	$sql="SELECT * FROM piece where id = '".$_SESSION["gamme_ajouter_gamme"]."'";
	$res=my_query($sql);
	$row = mysql_fetch_array($res);
	$vf = new valid_form ;
	$vf->add("ref", $ref);				
	$vf->add("designation", $des);
	$vf->add("id_login", $id_login);
	$vf->add("id_avion", $id_avion);
	$vf->add("id_devise", $nom_devise);
	$vf->add("id_famille", $id_famille);
	$vf->add("id_cat", $id_cat);
	$vf->add("id_fab", $id_fab);
	$vf->add("id_affaire_type", $id_affaire_type);
	$vf->add("actif", $actif);
	$vf->add("commentaire", $commentaire);
	
	if ($util > 0)
		{
		$vf->update("piece"," where id = '$util';");
		
		$vf2 = new valid_form ;
		$vf2->add("ref", trim($ref));				
		$vf2->add("designation", $des);
		$vf2->add("id_avion", $id_avion);
		$vf2->add("id_famille", $id_famille);
		$vf2->add("id_cat", $id_cat);
		$vf2->add("id_fab", $id_fab);
		$vf2->add("id_affaire_type", $id_affaire_type);
		$vf2->update("gamme"," where id_piece = '$util';");
		
		$vf2 = new valid_form ;
		$vf2->add("ref", $ref);				
		//$vf2->add("designation", $des);
		$vf2->add("id_avion", $id_avion);
		$vf2->add("id_famille", $id_famille);
		$vf2->add("id_cat", $id_cat);
		$vf2->add("id_fab", $id_fab);
		$vf2->update("of"," where id_piece = '$util';");
		
		}
		else
		{
		$util=$vf->insert("piece");
		}
		
	$vf->log(__FILE__,__LINE__,DL_1);
		
}




if($util > 0){$_SESSION["piece_ajouter_id"]=$util;}else if($new > 0){$_SESSION["piece_ajouter_id"]=0;}
$util=$_SESSION["piece_ajouter_id"];


if ($util > 0)
{

$sql="SELECT * FROM piece where id = '$util'";
$res=my_query($sql);
$nb_ligne=mysql_num_rows($res);
$row = mysql_fetch_array($res);


$id = $row["id"];
$ref= $row["ref"];
$des= $row["designation"];
$commentaire= $row["commentaire"];
$id_avion= $row["id_avion"];
$nom_devise= $row["id_devise"];
$id_cat= $row["id_cat"];
$id_famille= $row["id_famille"];
$id_fab= $row["id_fab"];
$id_affaire= $row["id_affaire"];
$id_affaire_type= $row["id_affaire_type"];
$actif = $row["actif"];

$nb_gamme=nombre_de("select count(*)from gamme where id_piece=$util");


}

$id_avion = liste_db("select pa.id,pa.nom from piece_avion as pa order by pa.nom asc",$id_avion,"id_avion");//.'&nbsp;<span style="position:relative;top:7px;"><img src="images/cell_layout.png" onclick="DivStatus(\'cmd5\')" title="Ajouter un avion"></span><input  id="cmd5" class=\'cachediv\' type="text"  name="id_avion_s" size="20" value="">';
$nom_devise = liste_db("select d.id,d.nom from devise as d ",$nom_devise,"nom_devise");
$id_fab = liste_db("select f.id , f.nom  from gamme_fab as f order by f.nom asc",$id_fab,"id_fab");
$id_affaire_type = liste_d2($j_affaire_type,$id_affaire_type,"id_affaire_type");
$ref_input='<input type="text" maxlength="30" name="ref" size="30" value="'.$ref.'" id="ref" onChange="find_ref();"  > ' ;
$des ='<input type="text" maxlength="50" name="des" size="50" value="'.$des.'" id="des"> ' ;
$commentaire = '<textarea rows=4 cols=50 name="commentaire" >'.$commentaire.'</textarea>';


$table_debut="<TABLE  cellSpacing=1 cellPadding=3 width=100% align=center>
<tr>
<td valign='top' width=50%>";


$table_milieu="	</table>
</td>	
<td valign='top'>
	<TABLE class=forumline cellSpacing=1 align='middle' cellPadding=3 width=".$taille."  >
		<TR>
	<TD align='center' height='30' class='m3' colspan='2'>Fabrication</TD>
	</TR>";
	
$table_fin="</td>
</tr>
</table>";

$page = new page;
$page->head("Gestion des références " .$row["ref"]);
$page->body();
$page->entete("Gestion des références " .$row["ref"]);

if ($util>0){$page->add_button(1,1,'piece_ajouter.php?util='.$util.'&prev=1');}else{$page->add_button(1,0);}
$page->add_button(2,1,parent(102));
if ($util>0){$page->add_button(3,1,'piece_ajouter.php?util='.$util.'&next=1');}else{$page->add_button(3,0);}

$page->add_button(0,2);
if(d_ok(102))$page->add_button(4,1,"validation()","Enregistrer");
$page->add_button(0,2);
$page->add_button(0,2);
if(d_ok(111)and $util>0 and $nb_gamme==0)$page->add_button(5,1,"gamme_ajouter.php?new=1","Ajouter une gamme");
$page->add_button(0,2);
if(d_ok(132)and $util>0 and $id_affaire==0)$page->add_button(11,1,"affaire_ajouter.php?id_piece=$util&parent_id=102","Créer mon affaire");

$page->fin_entete();
$page->datescript();

?>
<script src="/js/ajax.js" type="text/javascript"></script>

<script LANGUAGE="JavaScript">

fois=0;

function validation()
{

//find_ref();

	if (document.getElementById("ref_ok").value == "0" )  {
		alert("Référence incorrecte !.");
		document.getElementById("ref").focus();
		return false;
	}
	
	if ((document.getElementById("ref").value == "" ) ||(document.getElementById("ref").value == "Indiquer votre référence" ) ) {
		alert("Référence incorrecte !.");
		document.getElementById("ref").focus();
		return false;
	}	
	
	if (document.getElementById("des").value == "" )  {
		alert("Désignation incorrecte !.");
		document.getElementById("des").focus();
		return false;
	}
	
	if (document.formulaire.id_affaire_type.options[document.formulaire.id_affaire_type.selectedIndex].value == "0" ){
		alert("Veuillez saisir le champ 'Type Affaire'.");
		document.formulaire.id_affaire_type.focus();
		return false;
	}
	
	
	if (document.formulaire.id_cat.options[document.formulaire.id_cat.selectedIndex].value == "0" ){
		alert("Veuillez saisir le champ 'Catégorie'.");
		document.formulaire.id_cat.focus();
		return false;
	}
	
	if (document.formulaire.id_famille.options[document.formulaire.id_famille.selectedIndex].value == "0" ){
		alert("Veuillez saisir le champ 'Famille'.");
		document.formulaire.id_famille.focus();
		return false;
	}
		
	if (document.formulaire.id_avion.options[document.formulaire.id_avion.selectedIndex].value == "0" ){
		alert("Veuillez saisir le champ 'Avion'.");
		document.formulaire.id_avion.focus();
		return false;
	}

if(fois==0){fois++;document.formulaire.submit();}
}


function find_ref()
{ 
	var req = null;
	req=get_xhr();
req.onreadystatechange = function()
	{
		if(req.readyState == 4)
		{
			if(req.status == 200)
			{
			alert(req.responseText);
				document.getElementById("ref_ok").value=req.responseText;
				document.getElementById("ref_img").innerHTML= '<img src="images/statut'+req.responseText+'.gif">';
			}
			else	
			{
				alert("Error: returned status code " + req.status + " " + req.statusText);
			}
		}
	};
var url ="req_ajax.php?id_req=8&ref_id=<?echo $id;?>&ref=" + document.getElementById("ref").value ;
//alert(url);
req.open("POST", url, true);
req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
req.send(null);
} 

	function DivStatus( divID )
		{
		Pdiv = document.getElementById( divID );
		Pdiv.className = ( Pdiv.className == 'cachediv' ) ? '' : 'cachediv';
		}


menu = new Array()
<?
$corr =  "menu_corr = new Array()\n\n";
$i=0;
$corr .= 'menu_corr[0] = '.$i.";\n";

echo "menu[$i] = new Array()\n\n";
	$j=0;
	echo 'menu['.$i.']['.$j.']=new Option("","")'."\n";
	$j++;
	$res2 = my_query("SELECT id , nom FROM gamme_cat order by nom ");
	while ($row2 = mysql_fetch_array($res2))
	{
		if($row2[0] == $id_famille) {$select_cat = '<script LANGUAGE = "JavaScript" > changecat(); document.formulaire.id_famille.options['.$j.'].selected = true;</script>';}
		echo 'menu['.$i.']['.$j.'] = new Option("'.$row2[1].'","'.$row2[0].'")'."\n";
		$j++;
	}
echo "\n\n\n";

$s .= '<OPTION '.$selected.' VALUE = "0"></OPTION>'."\n";

$i++;
$res = my_query("SELECT id , nom FROM gamme_cat order by nom");
while($row = mysql_fetch_array($res))
{
	$corr .= 'menu_corr['.$row[0].'] = '.$i.";\n";
	if($id_cat == $row[0]) {$selected = ' selected ';} else {$selected = '';}
	$s .= '<OPTION '.$selected.' VALUE="'.$row[0].'">'.$row[1].'</OPTION>'."\n";
	echo "menu[$i] = new Array()\n\n";
	$j = 0;
	echo 'menu['.$i.']['.$j.'] = new Option("","0")'."\n";
	$j++;
	$res2 = my_query("SELECT id, nom FROM gamme_famille WHERE id_cat = '".$row[0]."' order by nom ");
	while ($row2 = mysql_fetch_array($res2))
	{
		if($row2[0] == $id_famille) {$select_cat = '<script LANGUAGE="JavaScript">changecat();document.formulaire.id_famille.options['.$j.'].selected = true;</script>';}
		echo 'menu['.$i.']['.$j.'] = new Option("'.$row2[1].'","'.$row2[0].'")'."\n";
		$j++;
	}
	echo "\n\n\n";
	$i++;
}
echo $corr;	
?>

function changecat()
{
	numeroMenu = menu_corr[document.formulaire.id_cat.options[document.formulaire.id_cat.selectedIndex].value];
	f = document.formulaire.id_famille;
	for(i=f.options.length-1; i>0; i--)
	{
		f.options[i] = null
	}
	for (i=0; i<menu[numeroMenu].length; i++)
	{
		f.options[i] = new Option(menu[numeroMenu][i].text,menu[numeroMenu][i].value)
	}
	f.selectedIndex=0
}
</script>
		
		

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
<script langauge="JavaScript" src="js/overlib.js"></script>

<form name="formulaire"  method="post" enctype="multipart/form-data" action="piece_ajouter.php" target="principal">

<input  type="hidden" name="util" value="<? echo $util;?>">
<input  type="hidden" name="save" value="1">
<center>
	<TABLE class=forumline cellSpacing=1 align="middle" cellPadding=3 width=60%  >
	<TR>
	<TD align="center" height="30" class="m3" colspan="2">Pièce</TD>
	</TR>
	<tr>
	<td class ="cel1" width=25%>&nbsp;ID</td>
	<td class ="cel2" ><? echo $id;?></td>
	</tr>
	<tr>
	<td class ="cel1" width=25%>&nbsp;Réference</td>
	<td class ="cel2" height=<? echo $hauteur;?> ><? echo /*fait par stagiaire*/$ref_input;?><input type=hidden id='ref_ok'><span id='ref_img'></span></td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Designation</td>
	<td class ="cel2"  ><? echo $des;?></td>
	</tr>
	<tr>
	<td class ="cel1" width=25%>&nbsp;Fabrication</td>
	<td class ="cel2"  ><? echo $id_fab; ?></td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Type d'affaire</td>
	<td class ="cel2"  ><? echo $id_affaire_type;?></td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Catégorie</td>
	<td class ="cel2"  ><SELECT id=button  style="width:100;" NAME="id_cat"  SIZE=1 onChange="changecat()"><? echo $s;?></SELECT></td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Famille</td>
	<td class ="cel2"  ><SELECT  id=button style="width:100;" NAME="id_famille"><OPTION VALUE="0"></OPTION></SELECT><? echo $select_cat;?></td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Avion</td>
	<td class ="cel2"  ><? echo $id_avion;?></td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Active</td>
	<td class ="cel2" >
	<input class = "cel2" type="radio" name="actif" value="0" <? if ($actif==0){echo "checked=true";} ?>>Non&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
	<input class = "cel2" type="radio" name="actif" value="1" <? if ($actif==1){echo "checked=true";} ?>>Oui
	</td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Commentaire</td>
	<td class ="cel2"  ><? echo $commentaire;?></td>
	</tr>
	<tr>
	<td class ="cel1" >&nbsp;Devise</td>
	<td class ="cel2"  ><? echo $nom_devise;?></td>
	</tr>
	
	</table>
</form>

<br>

<?
if ($util>0)
{
?>

<table class=forumline cellSpacing=1 cellPadding=2 width="100%" align=center border=0>
<tr >
<td class= "m3" width=100% colspan=9 >GAMMES</td>
</tr>
<tr >
<td class= "m3" width=2% >ID</td>
<td class= "m3" width=10% height=23>Référence</td>
<td class= "m3" width=5% >Indice</td>
<td class= "m3" width=20% >Désignation</td>
<td class= "m3" width=8% >Révision</td>
<td class= "m3" width=30% >Historique Indice</td>

<? if($d_devis==1){ ?>
<td class= "m3" width=5% >Tps devis</td>
<? } if($d_obj==1){ ?>
<td class= "m3" width=5% >Tps objectif</td>
<? }?>
<td class= "m3" width=5% >Type</td>
<td class= "m3" width=5% >UAP</td>
<td class= "m3" width=5% >Actif</td>
</tr>

<?


$req="select * from gamme  where id_piece = $util order by indice asc ";
$res=my_query($req);

$nb_ligne=mysql_num_rows($res);
if ($nb_ligne==0){echo "<br>Aucun enregistrement trouvé<br>";exit;}

while ($ligne=mysql_fetch_array($res))
	{
	if (($ic % 2)==0){$cid="class= \"cel2\"";}else {$cid="class= \"cel1\"";}
	if($ligne["valid"]){$alt='Gamme validé pour la production';}else{$alt='Gamme non validé pour la production';}
	?>
	<tr>
	<td align=center <? echo $cid;?>  ><? echo $ligne["id"];?></td>
	<td align=center <? echo $cid;?>  ><a class="b" href="gamme_ajouter.php?util=<? echo $ligne["id"];?>"><? echo $ligne["ref"];?></a></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["indice"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["designation"];?></td>
	<td align=center <? echo $cid;?>  ><? echo datodf($ligne["d_revision"]);?></td>
	<td align=left <? echo $cid;?>  ><? echo to_txt($ligne["commentaire"]);?></td>
	<? if($d_devis==1){ ?>
	<td align=center <? echo $cid;?>  ><? echo $ligne["tps_d"];?></td>
	<? } if($d_obj==1){ ?>
	<td align=center <? echo $cid;?>  ><? echo $ligne["tps_o"];?></td>
	<? }?>
	<td align=center <? echo $cid;?>  ><? echo $j_affaire_type[$ligne["id_affaire_type"]];?></td>
	<td align=center <? echo $cid;?>  ><? echo $j_entite[$ligne["id_entite"]];?></td>
	<td align=center <? echo $cid;?>  ><img border=0 alt="<? echo $alt;?>" src="images/statut<? echo $ligne["valid"];?>.gif"></td>
	</tr>
	<?

	}
echo "</table></center>";

?>
<table class=forumline cellSpacing=1 cellPadding=2 width="100%" align=center border=0>
<tr >
<td class= "m3" width=100% colspan=9 >EQUIPEMENTS</td>
</tr>
<tr>
<td class= "m3" width=2% >ID</td>
<td class= "m3" width=10% >N°INV</td>
<td class= "m3" width=10% >Nom</td>
<td class= "m3" width=10% >Famille</td>
<td class= "m3" width=10% >Catégorie</td>
<td class= "m3" width=10% >Modèle</td>
<td class= "m3" width=10% >Affaire</td>
<td class= "m3" width=10% >Maj</td>
<td class= "m3" width=5% >Actif</td>
</tr>

<?

//SELECT * FROM equipement as e, equipement_piece as ep WHERE e.id=ep.id_equipement and ep.id_piece=2112
$req="select ec.nom as nom_cat, ef.nom as nom_famille, e.* from equipement as e
left join equipement_piece as ep on ep.id_equipement=e.id
left join equipement_cat as ec on ec.id=e.cat
left join equipement_famille as ef on ef.id=e.famille
where ep.id_piece = $util order by ep.id_piece asc ";
$res=my_query($req);

$nb_ligne=mysql_num_rows($res);
if ($nb_ligne==0){echo "<br>Aucun enregistrement trouvé<br>";exit;}

while ($ligne=mysql_fetch_array($res))
	{
	if (($ic % 2)==0){$cid="class= \"cel2\"";}else {$cid="class= \"cel1\"";}
	if($ligne["valid"]){$alt='Gamme validé pour la production';}else{$alt='Gamme non validé pour la production';}
	?>
	<tr>
	<td align=center <? echo $cid;?>  ><? echo $ligne["id"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["n_inv"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["nom"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["nom_famille"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["nom_cat"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["modele"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["nb_planif"];?></td>
	<td align=center <? echo $cid;?>  ><? echo $ligne["maj"];?></td>
	<td align=center <? echo $cid;?>  ><img border=0 alt="<? echo $alt;?>" src="images/statut<? echo $ligne["etat"];?>.gif"></td>
	</tr>
	<?

	}
echo "</table></center>";
}

echo pied_page();
?>
