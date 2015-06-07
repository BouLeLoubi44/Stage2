<?php
include("fonction.php");
if (!d_ok(139)){header("location: accueil.php");exit;}
$_SESSION['en_cour']="affaire_suivi_indicateurs.php";

if ($parent_id > 0)
	{
	$_SESSION[$_SESSION['en_cour']]=id2url($parent_id);
	}
	else if(($parent_ori == 1)or($_SESSION[$_SESSION['en_cour']]==""))
		{
		$_SESSION[$_SESSION['en_cour']]=parent(139);
		}

if ($mode == 1)
	{
	$_SESSION['affaire_suivi_indicateurs_r_semaine'] = $r_semaine;
	$_SESSION['affaire_suivi_indicateurs_r_annee'] = $r_annee;
	$_SESSION['affaire_suivi_indicateurs_r_nb_semaine'] = $r_nb_semaine;
	$_SESSION['affaire_suivi_indicateurs_r_cause_c'] = $r_cause_c;
	}
	
if ($mode == 9 )
	{
	//$_SESSION['affaire_suivi_indicateurs_r_annee']=date('Y',datotimestamp(date('Y-W-d'),0,-12));
	//$_SESSION['affaire_suivi_indicateurs_r_semaine']=date('W',datotimestamp(date('Y-W-d'),0,-12));
	//$_SESSION['affaire_suivi_indicateurs_r_nb_semaine']=53;
	$r_cause_c='';
	$r_annee = date("Y");
	$r_semaine = date("W");
	$r_nb_semaine = 53;
	}
	
$r_semaine=$_SESSION['affaire_suivi_indicateurs_r_semaine'];
$r_annee=$_SESSION['affaire_suivi_indicateurs_r_annee'];
$r_nb_semaine=$_SESSION['affaire_suivi_indicateurs_r_nb_semaine'];
$r_cause_c   = $_SESSION['affaire_suivi_indicateurs_r_cause_c'];

$da = $r_annee."-".format_0($r_semaine,2)."-01";

$page = new page;
$page->head("Indicateurs Suivi Affaires");
$page->body();
$page->entete("Indicateurs Suivi Affaires");
$page->add_button(1,0);
$page->add_button(2,1,$_SESSION[$_SESSION['en_cour']]);
$page->add_button(3,0);
$page->fin_entete();
$page->datescript();

$affaire_suivi_indicateurs_req="";

$req = "
	 SELECT year(date)as annee, yearweek(date,3) as s, etat, COUNT(*)as nb, cause_creation, client
	 FROM affaire
	 WHERE type = 2 and year(date) = 2014 
	 GROUP BY annee, s, etat, cause_creation, client
       ";

$res=my_query($req);

while ($row=mysql_fetch_array($res))
	{
		$total_consultation_cause_c_s[$row["cause_creation"]][$row["s"]]+=$row["nb"]; //total des consultations semaines
		
		if (($row["etat"]>1) && ($row["etat"]<6))
			{
			$devis_envoi_cause_c_s[$row["cause_creation"]][$row["s"]]+=$row["nb"]; //total des devis envoyés semaines
			}
		if (($row["etat"]>2) && ($row["etat"]<5))
			{
			$nb_commande_cause_c_s[$row["cause_creation"]][$row["s"]]+=$row["nb"]; //total des commandes semaines
			}
	}

//informations du tableau
$s1=$r_semaine;
$a1=$r_annee;
$s2=($r_semaine+$r_nb_semaine);
$a2=$r_annee;
$ts=intersem($s1,$a1,$s2,$a2,"%G%V");
?>
<!--FILTRES -->
<FORM method="post" style="position:relative;z-index:1;" name="f1" action="affaire_suivi_indicateurs.php"  target="principal">
	<INPUT type=hidden name="mode" value="1">
	<TABLE  class=forumline cellSpacing=1 cellPadding=2 width="98%" align=center border=0>
		<TR class="m3">
			<TD class="m3"> 
				Cause création &nbsp; 
				<?php echo liste_d2($j_affaire_cause_creation,$r_cause_c,"r_cause_c");?>
				&nbsp;&nbsp;&nbsp; 
			<TD class="m3">
				&nbsp;Semaine :&nbsp;
				<SELECT id="r_semaine" size="1" name="r_semaine" >
					<?php
					for ($i=1;$i < 54; $i++)
						{
						if ($i == $r_semaine){$s=" selected ";}else{$s="";}
						echo '<option value="'.$i.'" '.$s.'>'.$i.'</option>'."\n";
						}
					?>
				</SELECT>
				<SELECT id="r_annee" name="r_annee" size="1"  >
				<?php
				for ($i =date("Y");$i > 2000; $i--) {if ($r_annee == $i){$s= ' selected ';}else{$s='';};echo "\t<option value=$i $s >$i</option>\n";}
				?>
				</SELECT>
				&nbsp; sur &nbsp;
				<SELECT id="r_nb_semaine" size="1" name="r_nb_semaine">
					<?
					for ($i=1;$i < 25; $i++)
						{
						if ($i == $r_nb_semaine){$s=" selected ";}else{$s="";}
						echo '<option value="'.$i.'" '.$s.'>'.$i.'</option>'."\n";
						}
					?>
				</SELECT>
				&nbsp; semaines
			</TD>
			<TD class="m3" rowspan="2"> 
				<INPUT type=submit id=button3 value="Go"> </BR> 
				<INPUT id=button3 type=button value="Clear" onclick="document.location.href='affaire_suivi_indicateurs.php?mode=9';"> 
			</TD>
		</TR>
	</TABLE>
</FORM>


<!--                                            CONSULTATIONS                                              -->
<TABLE class=forumline cellSpacing=1 cellPadding=2 width="80%" align=center border=0>
		<TR>
			<?php 	$r_annee2 = $a1;
				if ($s2>52)
					{
					$s2 = 0 + ($r_semaine+$r_nb_semaine-52);
					$r_annee2 = ($a1 +1);
					} 
			?>
				<TD class= "m3" height=30> <?php echo $j_affaire_cause_creation[$r_cause_c]."/      Semaine ".$r_semaine." Année ".$a1." ===> Semaine ".$s2." Année ".$r_annee2;?> </TD>
				<TD class= "m3" height=30>Consultations reçues</TD>
				<TD class= "m3" height=30>Nombre de devis envoyés</TD>
				<TD class= "m3" height=30>Nombre de commandes</TD>
		</TR>
		
<?php 
$ic=0;
foreach($ts as $k => $i)
	{
	if (($ic % 2)==0){$cid="class= \"cel2\" align=center";}else {$cid="class= \"cel1\" align=center";}
	$r_annee2 = $a1;
	if ($ic==$r_nb_semaine){continue;}
	$ic++;
	echo	"<TR ".$cid.">
		<TD  height=30> Semaine ".(substr("$i",4,2)+0)." (Année ".(substr("$i",0,4)).")"."</TD>
		<TD>".($total_consultation_cause_c_s[$r_cause_c][$i])." </TD>
		<TD>".($devis_envoi_cause_c_s[$r_cause_c][$i])."</TD>
		<TD>".($nb_commande_cause_c_s[$r_cause_c][$i])." </TD>
		</TR>";
	$total_consultation_cause_c+=$total_consultation_cause_c_s[$r_cause_c][$i];
	$devis_envoi_cause_c+=$devis_envoi_cause_c_s[$r_cause_c][$i];
	$nb_commande_cause_c+=$nb_commande_cause_c_s[$r_cause_c][$i];
	}
	
echo	"<TR class= 'm3'  height=30>
	<TD> Total </TD>
	<TD>".($total_consultation_cause_c)."</TD>
	<TD>".($devis_envoi_cause_c)."</TD>
	<TD>".($nb_commande_cause_c)."</TD>
	</TR>";  
?>
</TABLE>

</BR> </BR> </BR> </BR> </BR> </BR> </BR> </BR> </BR> 

<!--graphs-->
<script type="text/javascript" language="javascript" src="./js/AnyChart.js"></script>

<?php

//Graphique 1
$datax = "";
foreach($ts as $key => $s)
	{
	$a=(substr("$s",0,4)+0);
	$s=(substr("$s",4,2)+0); 
	$datax[$a.format_0($s,2)] = 'Sem.'.$s;
	$datay1[$a.format_0($s,2)]=0;
	$datay2[$a.format_0($s,2)]=0;
	$datay3[$a.format_0($s,2)]=0;
	}	

//Ajout des données [Conusltations, devis, commandes]
$res2=my_query($req);
while ($l=mysql_fetch_array($res2))
	{
	$datay1=$total_consultation_cause_c_s[$r_cause_c];
	$datay2=$devis_envoi_cause_c_s[$r_cause_c];
	$datay3=$nb_commande_cause_c_s[$r_cause_c];
	}
	
ksort($datay1);
ksort($datay2);
ksort($datay3);


$maxY=max($datay1);
if(max($datay2)>$maxY) {$maxY=max($datay2);}
if(max($datay3)>$maxY) {$maxY=max($datay3);}
$maxY = $maxY*1.2;


$xml = '
<anychart>
	
	<settings>
		<animation enabled="True"/>
	</settings>
	
	<charts>
	
		<chart plot_type="CategorizedVertical">
		
			<data_plot_settings default_series_type="Bar">

				<bar_series point_padding="0.2" group_padding="0.3" style="AquaLight">

					<bar_style>
						<fill opacity="1"/>
						
						<states>
							<hover color="White"/>
						</states>
					</bar_style>
				
					<tooltip_settings enabled="True">
					
						<background>
							<border color="DarkColor(%Color)"/>
						</background>
					
						<format>
							{%YValue}{numDecimals:0,thousandsSeparator: }
						</format>

					</tooltip_settings>

					<label_settings enabled="true">
						<background enabled="false"/>
						<font color="DarkColor(%Color)"/>
						<format>{%YValue}{numDecimals:0,thousandsSeparator: }</format>
						<effects>
							<drop_shadow enabled="true" opacity="1"/>
						</effects>
					</label_settings>
				
					
				</bar_series>

				<line_series>

					<tooltip_settings enabled="true">

					<format>
					{%Value}{numDecimals:0,thousandsSeparator: }%
					</format>
					
					</tooltip_settings>

					<line_style>
						<line thickness="2"/>
					</line_style>

				</line_series>
					
			</data_plot_settings>

			<chart_settings>
			
				<title enabled="true">
					<text>Indicateurs COMOP</text>
				</title>

				<subtitle enabled="true">
					<text>(consultations, devis envoyés, commandes)</text>
    					<font size="10"></font>
					<background enabled="false"></background>
				</subtitle>

				<legend enabled="true" elements_layout="Horizontal" position="bottom" align="center" >
					<title enabled="false" />
				</legend>

				<axes>

					<x_axis>
						<title enabled="false" />
						<labels rotation="45" />
					</x_axis>
				
					<y_axis>
						<title enabled="false" />
						<labels>
							<format>{%Value}{numDecimals:0,thousandsSeparator: }</format>
						</labels>
						<scale maximum="'.$maxY.'" />
					</y_axis>
					
					<extra>
					
					<y_axis name="y2">
						<minor_grid enabled="false"/>
						<major_grid enabled="false"/>
					
						<labels>
							<format>{%Value}{numDecimals:0,thousandsSeparator: }%</format>
						</labels>
						
						<title enabled="false" />

						<scale minimum="0" maximum="100" />
						
					</y_axis>
					
					</extra>
					
				</axes>
				
			</chart_settings>
		
			<data>
			
				<series name="Consultations reçues">
				';
				foreach ($datay1 as $key=>$data) $xml .= '<point name="'.$datax[$key].'" y="'.$data.'" />';
				$xml .= '
				</series>

				<series name="Nombre de devis envoyés">
				';
				foreach ($datay2 as $key=>$data) $xml .= '<point name="'.$datax[$key].'" y="'.$data.'" />';
				$xml .= '
				</series>

				<series name="Nombre de commandes">
				';
				foreach ($datay3 as $key=>$data) $xml .= '<point name="'.$datax[$key].'" y="'.$data.'" />';
				$xml .= '
				</series>
				
			</data>
		</chart>
	</charts>
</anychart>

';

echo anychart($xml);

?>

<?php
echo pied_page();
?>
