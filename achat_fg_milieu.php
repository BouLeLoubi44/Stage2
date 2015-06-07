<?
include("fonction.php");

if($d_ok_achat == 1){$req_general = " and etat in (2,4) ";}else{$req_general = " and etat = 0 and id_login = $id_login ";}

if($tri <> ''){$_SESSION["achat_fg_milieu_tri"]=$tri;}
$tri=$_SESSION["achat_fg_milieu_tri"];

if($mode==9)
	{
	$_SESSION["achat_fg_recherche"]="";
	}
	
if(isset($r_recherche)){$_SESSION["achat_fg_recherche"]=$r_recherche;}
else if ($tri != "fournisseur"){$_SESSION["achat_fg_recherche"]="";}


$req=my_query("select id_service , count(*) from achat_ligne where status = 2 $req_general group by id_service");
while ($row=mysql_fetch_array($req))
{
$t_s1[$row['id_service']]=$row[1];
}

$req=my_query("select id_service , count(*) from achat_ligne where ((status <> 2 $req_general)or (chiffrage=1)) group by id_service");
while ($row=mysql_fetch_array($req))
{
$t_s2[$row['id_service']]=$row[1];
}

$req=my_query("select id_externe , date, dom_ag from externe_qualite where last = 1");
while ($row=mysql_fetch_array($req))
{
$t_q_date[$row['id_externe']]=$row['date'];
$t_q_dom_ag[$row['id_externe']]=$row['dom_ag'];
}
//<meta http-equiv="refresh" content="300;URL=achat_fg_milieu.php">
?>
<html><head>
<? echo $j_meta;?>

<style type="text/css">

td{FONT-SIZE: 9px; COLOR: #000000;FONT-FAMILY: Arial}

a:hover{color: #333366; text-decoration: underline;}
a:active{color: #333366; text-decoration: none;}
a{color: #333366; text-decoration: none; }

</style>
<script LANGUAGE="JavaScript">

parent.frames["fm_milieu"].document.close;
parent.frames["fm_milieu"].document.write("<html><head></head><body BACKGROUND=\"images/img_fmm.png\" topmargin=0 leftmargin=0 ></body></html>");
parent.frames["fm_milieu"].document.close();
</script>

</head><body class=body BACKGROUND="images/img_fgm.png"  topmargin=0 leftmargin=0 rightmargin=0 bottommargin=0 bgcolor="#57698d">

<table style="position:relative;left:15px;top:0px;" marginwidth=0 marginheight=0 cellSpacing=0 cellPadding=1 border=0>
  <tr >
  <td width=100 height=5   ><b><a title="Non affectées" href="achat_fd_haut.php?id_s=0" target="fd2">Non affectées</a></b></td>
  <td width=18 align=center   ><? echo nombre_de("select count(*) from achat_ligne where id_service = 0 and ((status = 2 $req_general )or (chiffrage=1))");?></td>
  <td width=18 align=center   ><? echo nombre_de("select count(*) from achat_ligne where id_service = 0 and ((status <> 2 $req_general) or (chiffrage=1))");?></td>
  <td width=18 align=center   ><img src="images/statut1.gif"></td>
  </tr>
<?
if($d_ok_achat == 1)
{
?>
  <tr >
  <td width=100 height=5   ><b><a title="Rejetées" href="achat_fd_haut.php?id_s=-1" target="fd2">Rejetées</a></b></td>
  <td width=18 align=center ><? echo nombre_de("select count(*) from achat_ligne where etat = 11 and (TO_DAYS(CURDATE()) - TO_DAYS(date))  < 20 ");?></td>
  <td width=18 align=center >0</td>
  <td width=18 align=center ><img src="images/statut1.gif"></td>
  </tr>
<?
} 
?>
  <tr >
  <td width=100 height=5   ><b><a title="Stock" href="achat_fd_haut.php?id_s=-2" target="fd2">Stock</a></b></td>
  <td width=18 align=center   ><? echo nombre_de("select count(*) from achat_ligne where id_service = -2 and status = 2 $req_general ");?></td>
  <td width=18 align=center   ><? echo nombre_de("select count(*) from achat_ligne where id_service = -2 and status <> 2 $req_general ");?></td>
  <td width=18 align=center   ><img src="images/statut1.gif"></td>
  </tr>
<?



if($tri == "top"){$req="select es.id ,es.id_externe, es.societe , es.site , es.service , es.nom  , es.cmd_valid , es.bof , es.bof_raison from externe_service as es left join achat_ligne as al on es.id = al.id_service  where es.cmd = 1 group by es.id order by es.nom asc  ";}
else if($tri == "euro"){$req="select es.id ,es.id_externe , es.societe , es.site , es.service , es.bof , es.bof_raison , sum(al.m_ht) as tot , es.nom  , es.cmd_valid  from externe_service as es left join achat_ligne as al on es.id = al.id_service where es.cmd = 1 $req_general group by es.id order by tot desc ,es.nom asc ";}
else if($tri == "delai"){$req="select es.id ,es.id_externe , es.societe , es.site , es.service , es.bof , es.bof_raison , min(al.d_besoin) as min_date , es.nom  , es.cmd_valid from externe_service as es left join achat_ligne as al on es.id = al.id_service where es.cmd = 1 and al.d_besoin <> '0000-00-00' $req_general group by es.id order by min_date, es.nom asc  ";}
else if($tri == "top1"){$req="select es.id ,es.id_externe , es.societe , es.site , es.service , es.bof , es.bof_raison , count(al.id) as tot , es.nom  , es.cmd_valid from externe_service as es left join achat_ligne as al on es.id = al.id_service where es.cmd = 1 and al.status = 2 $req_general group by es.id order by es.nom asc  ";}
else if($tri == "top2"){$req="select es.id ,es.id_externe , es.societe , es.site , es.service , es.bof , es.bof_raison , count(al.id) as tot , es.nom  , es.cmd_valid from externe_service as es left join achat_ligne as al on es.id = al.id_service where es.cmd = 1 and ((al.status <> 2 $req_general)or (chiffrage=1)) group by es.id order by es.nom asc  ";}
else if($tri == "fournisseur"){$req="select es.id ,es.id_externe , es.societe , es.site , es.service , es.bof , es.bof_raison , count(al.id) as tot , es.nom  , es.cmd_valid from externe_service as es left join achat_ligne as al on es.id = al.id_service where es.cmd = 1 and es.nom LIKE '%".$_SESSION["achat_fg_recherche"]."%'  group by es.id order by es.nom asc  ";}
else{$req="select es.id  ,es.id_externe, es.societe , es.site , es.service , es.nom  , es.cmd_valid , es.bof , es.bof_raison from externe_service as es where es.cmd = 1 order by es.nom asc  ";}


$time_start = getmicrotime();
 
$ic=0;
$res=my_query($req);
$ligne_page=$lpp  ;                //ligne par page
$p_pf=20;  			     //page par feuille

while ($ligne=mysql_fetch_array($res))
{
	if(($ligne["bof"]==0)and($d_ok_achat <> 1))
	{
	if($ligne["bof_raison"]<>''){$div2 = '- Désactivé car '.$ligne["bof_raison"];}else{$div2='';}
	$div=' title="'.$ligne["nom"].' '.$div2.'"';
	$lien=  substr($ligne["nom"],0,14);
	}
	else
	{
	$div="";
	$lien= '<a title="'.$ligne["nom"].' - '.$ligne["site"].' - '.$ligne["service"].'" href="achat_fd_haut.php?id_s='.$ligne["id"].'" target="fd2">'.substr($ligne["nom"],0,14).'</a>';
	}
	if($ligne["cmd_valid"]==0){$txt='En cours d\'évaluation';}
	else if($ligne["cmd_valid"]==1){$txt='Fournisseur Approuvé le '.datodf($t_q_date[$ligne["id_externe"]])." - ".$t_q_dom_ag[$ligne["id_externe"]];}
	else if($ligne["cmd_valid"]==2){$txt='A surveiller';}
	else if($ligne["cmd_valid"]==3){$txt='Ce fournisseur est desapprouvé par le service qualité depuis le '.datodf($t_q_date[$ligne["id_externe"]]);$lien=  substr($ligne["nom"],0,14);}
	else if($ligne["cmd_valid"]==4){$txt='Fournisseur ponctuel';}
	
	if($ligne["cmd_valid"]==0){$cmd_valid_img=5;}
	else if($ligne["cmd_valid"]==1){$cmd_valid_img=1;}
	else if($ligne["cmd_valid"]==2){$cmd_valid_img=0;}
	else if($ligne["cmd_valid"]==3){$cmd_valid_img=4;}
	else if($ligne["cmd_valid"]==4){$cmd_valid_img=2;}
	
	
	?>
	<tr >
	<td width=100 height=5  <? echo $div;?> ><? echo $lien;?></td>
	<td width=18 align=center   ><? echo $t_s1[$ligne["id"]];?></td>
	<td width=18 align=center   ><? echo $t_s2[$ligne["id"]];?></td>
	<td width=18 align=center   ><img title="<? echo $txt;?>" src="images/statut<? echo $cmd_valid_img; ?>.gif"></td>
	</tr>
	<?

}

echo "</table>";
echo pied_page();
?>
