<?php
//Batch Info/Variables (divides total ore mined by the amount per batch) (OreName.Value is OreMinedValue from Runs table)

  //Ore
		//Veldspar (Batch Size: 333)
$Veld = $DB->getCol("SELECT Veldspar FROM runs WHERE id='$ID' LIMIT 1");
$Veldspar = floor(($Veld[0] / 333));

$CVeld = $DB->getCol("SELECT ConcentratedVeldspar FROM runs WHERE id='$ID' LIMIT 1");
$ConcentratedVeldspar = (floor(($CVeld[0] / 333)) * 1.05);

$DVeld = $DB->getCol("SELECT DenseVeldspar FROM runs WHERE id='$ID' LIMIT 1");
$DenseVeldspar = (floor(($DVeld[0] / 333)) * 1.10);

		//Scordite (Batch Size: 333)
$Scord = $DB->getCol("SELECT Scordite FROM runs WHERE id='$ID' LIMIT 1");
$Scordite = floor(($Scord[0] / 333));

$CScord = $DB->getCol("SELECT CondensedScordite FROM runs WHERE id='$ID' LIMIT 1");
$CondensedScordite = (floor(($CScord[0] / 333)) * 1.05);

$MScord = $DB->getCol("SELECT MassiveScordite FROM runs WHERE id='$ID' LIMIT 1");
$MassiveScordite = (floor(($MScord[0] / 333)) * 1.10);

		//Pyroxeres (Batch Size: 333)
$Pyrox = $DB->getCol("SELECT Pyroxeres FROM runs WHERE id='$ID' LIMIT 1");
$Pyroxeres = floor(($Pyrox[0] / 333));

$SPyrox = $DB->getCol("SELECT SolidPyroxeres FROM runs WHERE id='$ID' LIMIT 1");
$SolidPyroxeres = (floor(($SPyrox[0] / 333)) * 1.05);

$VPyrox = $DB->getCol("SELECT ViscousPyroxeres FROM runs WHERE id='$ID' LIMIT 1");
$ViscousPyroxeres = (floor(($VPyrox[0] / 333)) * 1.10);

		//Plagioclase (Batch Size: 333)
$Plag = $DB->getCol("SELECT Plagioclase FROM runs WHERE id='$ID' LIMIT 1");
$Plagioclase = floor(($Plag[0] / 333));

$APlag = $DB->getCol("SELECT AzurePlagioclase FROM runs WHERE id='$ID' LIMIT 1");
$AzurePlagioclase = (floor(($APlag[0] / 333)) * 1.05);

$RPlag = $DB->getCol("SELECT RichPlagioclase FROM runs WHERE id='$ID' LIMIT 1");
$RichPlagioclase = (floor(($RPlag[0] / 333)) * 1.10);

		//Omber (Batch Size: 500)
$Omb = $DB->getCol("SELECT Omber FROM runs WHERE id='$ID' LIMIT 1");
$Omber = floor(($Omb[0] / 500));

$SOmb = $DB->getCol("SELECT SilveryOmber FROM runs WHERE id='$ID' LIMIT 1");
$SilveryOmber = (floor(($SOmb[0] / 500)) * 1.05);

$GOmb = $DB->getCol("SELECT GoldenOmber FROM runs WHERE id='$ID' LIMIT 1");
$GoldenOmber = (floor(($GOmb[0] / 500)) * 1.10);

		//Kernite (Batch Size: 400)
$Kern = $DB->getCol("SELECT Kernite FROM runs WHERE id='$ID' LIMIT 1");
$Kernite = floor(($Kern[0] / 400));

$LKern = $DB->getCol("SELECT LuminousKernite FROM runs WHERE id='$ID' LIMIT 1");
$LuminousKernite = (floor(($LKern[0] / 400)) * 1.05);

$FKern = $DB->getCol("SELECT FieryKernite FROM runs WHERE id='$ID' LIMIT 1");
$FieryKernite = (floor(($FKern[0] / 400)) * 1.10);
		
		//Jaspet (Batch Size: 500)
$Jasp = $DB->getCol("SELECT Jaspet FROM runs WHERE id='$ID' LIMIT 1");
$Jaspet = floor(($Jasp[0] / 500));

$PuJasp = $DB->getCol("SELECT PureJaspet FROM runs WHERE id='$ID' LIMIT 1");
$PureJaspet = (floor(($PuJasp[0] / 500)) * 1.05);

$PrJasp = $DB->getCol("SELECT PristineJaspet FROM runs WHERE id='$ID' LIMIT 1");
$PristineJaspet = (floor(($PrJasp[0] / 500)) * 1.10);		
		
		//Hemorphite (Batch Size: 500)
$Hem = $DB->getCol("SELECT Hemorphite FROM runs WHERE id='$ID' LIMIT 1");
$Hemorphite = floor(($Hem[0] / 500));

$VHem = $DB->getCol("SELECT VividHemorphite FROM runs WHERE id='$ID' LIMIT 1");
$VividHemorphite = (floor(($VHem[0] / 500)) * 1.05);

$RHem = $DB->getCol("SELECT RadiantHemorphite FROM runs WHERE id='$ID' LIMIT 1");
$RadiantHemorphite = (floor(($RHem[0] / 500)) * 1.10);	

		//Hedbergite (Batch Size: 500)
$Hed = $DB->getCol("SELECT Hedbergite FROM runs WHERE id='$ID' LIMIT 1");
$Hedbergite = floor(($Hed[0] / 500));

$VHed = $DB->getCol("SELECT VitricHedbergite FROM runs WHERE id='$ID' LIMIT 1");
$VitricHedbergite = (floor(($VHed[0] / 500)) * 1.05);

$GHed = $DB->getCol("SELECT GlazedHedbergite FROM runs WHERE id='$ID' LIMIT 1");
$GlazedHedbergite = (floor(($GHed[0] / 500)) * 1.10);		

		//Gneiss (Batch Size: 400)
$Gne = $DB->getCol("SELECT Gneiss FROM runs WHERE id='$ID' LIMIT 1");
$Gneiss = floor(($Gne[0] / 400));

$IGne = $DB->getCol("SELECT IridescentGneiss FROM runs WHERE id='$ID' LIMIT 1");
$IridescentGneiss = (floor(($IGne[0] / 400)) * 1.05);

$PGne = $DB->getCol("SELECT PrismaticGneiss FROM runs WHERE id='$ID' LIMIT 1");
$PrismaticGneiss = (floor(($PGne[0] / 400)) * 1.10);

		//Ochre (Batch Size: 400)
$DOch = $DB->getCol("SELECT DarkOchre FROM runs WHERE id='$ID' LIMIT 1");
$DarkOchre = floor(($DOch[0] / 400));

$OnOch = $DB->getCol("SELECT OnyxOchre FROM runs WHERE id='$ID' LIMIT 1");
$OnyxOchre = (floor(($OnOch[0] / 400)) * 1.05);

$ObOch = $DB->getCol("SELECT ObsidianOchre FROM runs WHERE id='$ID' LIMIT 1");
$ObsidianOchre = (floor(($ObOch[0] / 400)) * 1.10);

		//Crokite (Batch Size: 250)
$Crok = $DB->getCol("SELECT Crokite FROM runs WHERE id='$ID' LIMIT 1");
$Crokite = floor(($Crok[0] / 250));

$SCrok = $DB->getCol("SELECT SharpCrokite FROM runs WHERE id='$ID' LIMIT 1");
$SharpCrokite = (floor(($SCrok[0] / 250)) * 1.05);

$CCrok = $DB->getCol("SELECT CrystallineCrokite FROM runs WHERE id='$ID' LIMIT 1");
$CrystallineCrokite = (floor(($CCrok[0] / 250)) * 1.10);

		//Spodumain (Batch Size: 250)
$Spod = $DB->getCol("SELECT Spodumain FROM runs WHERE id='$ID' LIMIT 1");
$Spodumain = floor(($Spod[0] / 250));

$BSpod = $DB->getCol("SELECT BrightSpodumain FROM runs WHERE id='$ID' LIMIT 1");
$BrightSpodumain = (floor(($BSpod[0] / 250)) * 1.05);

$GSpod = $DB->getCol("SELECT GleamingSpodumain FROM runs WHERE id='$ID' LIMIT 1");
$GleamingSpodumain = (floor(($GSpod[0] / 250)) * 1.10);

		//Bistot (Batch Size: 200)
$Bis = $DB->getCol("SELECT Bistot FROM runs WHERE id='$ID' LIMIT 1");
$Bistot = floor(($Bis[0] / 200));

$TBis = $DB->getCol("SELECT TriclinicBistot FROM runs WHERE id='$ID' LIMIT 1");
$TriclinicBistot = (floor(($TBis[0] / 200)) * 1.05);

$MBis = $DB->getCol("SELECT MonoclinicBistot FROM runs WHERE id='$ID' LIMIT 1");
$MonoclinicBistot = (floor(($MBis[0] / 200)) * 1.10);

		//Arkonor (Batch Size: 200)
$Ark = $DB->getCol("SELECT Arkonor FROM runs WHERE id='$ID' LIMIT 1");
$Arkonor = floor(($Ark[0] / 200));

$CArk = $DB->getCol("SELECT CrimsonArkonor FROM runs WHERE id='$ID' LIMIT 1");
$CrimsonArkonor = (floor(($CArk[0] / 200)) * 1.05);

$PArk = $DB->getCol("SELECT PrimeArkonor FROM runs WHERE id='$ID' LIMIT 1");
$PrimeArkonor = (floor(($PArk[0] / 200)) * 1.10);

		//Mercoxit (Batch Size: 250)
$Merc = $DB->getCol("SELECT Mercoxit FROM runs WHERE id='$ID' LIMIT 1");
$Mercoxit = floor(($Merc[0] / 250));

$MMerc = $DB->getCol("SELECT MagmaMercoxit FROM runs WHERE id='$ID' LIMIT 1");
$MagmaMercoxit = (floor(($MMerc[0] / 250)) * 1.05);

$VMerc = $DB->getCol("SELECT VitreousMercoxit FROM runs WHERE id='$ID' LIMIT 1");
$VitreousMercoxit = (floor(($VMerc[0] / 250)) * 1.10);

  //Ore m3 Remainder
  	//Remainder Calcs
	
$Veldm3Rem = (((($Veld[0] / 333) - floor(($Veld[0] /333))) + (($CVeld[0] / 333) - floor(($CVeld[0] / 333))) + (($DVeld[0] / 333) - floor(($DVeld[0] /333))) * 333) * 0.10);

$Scordm3Rem = (((($Scord[0] / 333) - floor(($Scord[0] / 333))) + (($CScord[0] / 333) - floor(($CScord[0] / 333))) + (($MScord[0] / 333) - floor(($MScord[0] / 333))) * 333) * 0.15);

$Pyroxm3Rem = (((($Pyrox[0] / 333) - floor(($Pyrox[0] / 333))) + (($SPyrox[0] / 333) - floor(($SPyrox[0] / 333))) + (($VPyrox[0] / 333) - floor(($VPyrox[0] / 333))) * 333) * 0.30);		

$Plagm3Rem = (((($Plag[0] / 333) - floor(($Plag[0] / 333))) + (($APlag[0] / 333) - floor(($APlag[0] / 333))) + (($RPlag[0] / 333) - floor(($RPlag[0] / 333))) * 333) * 0.35);	

$Ombm3Rem = (((($Omb[0] / 500) - floor(($Omb[0] / 500))) + (($SOmb[0] / 500) - floor(($SOmb[0] / 500))) + (($GOmb[0] / 500) - floor(($GOmb[0] / 500))) * 500) * 0.60);	

$Kernm3Rem = (((($Kern[0] / 400) - floor(($Kern[0] / 400))) + (($LKern[0] / 400) - floor(($LKern[0] / 400))) + (($FKern[0] / 400) - floor(($FKern[0] / 400))) * 400) * 1.20);	

$Jaspm3Rem = (((($Jasp[0] / 500) - floor(($Jasp[0] / 500))) + (($PuJasp[0] / 500) - floor(($PuJasp[0] / 500))) + (($PrJasp[0] / 500) - floor(($PrJasp[0] / 500))) * 500) * 2.00);

$Hemm3Rem = (((($Hem[0] / 500) - floor(($Hem[0] / 500))) + (($VHem[0] / 500) - floor(($VHem[0] / 500))) + (($RHem[0] / 500) - floor(($RHem[0] / 500))) * 500) * 3.00);	

$Hedm3Rem = (((($Hed[0] / 500) - floor(($Hed[0] / 500))) + (($VHed[0] / 500) - floor(($VHed[0] / 500))) + (($GHed[0] / 500) - floor(($GHed[0] / 500))) * 500) * 3.00);	

$Gnem3Rem = (((($Gne[0] / 400) - floor(($Gne[0] / 400))) + (($IGne[0] / 400) - floor(($IGne[0] / 400))) + (($PGne[0] / 400) - floor(($PGne[0] / 400))) * 400) * 5.00);

$Ochm3Rem = (((($DOch[0] / 400) - floor(($DOch[0] / 400))) + (($OnOch[0] / 400) - floor(($OnOch[0] / 400))) + (($ObOch[0] / 400) - floor(($ObOch[0] / 400))) * 400) * 8.00);

$Crokm3Rem = (((($Crok[0] / 250) - floor(($Crok[0] / 250))) + (($SCrok[0] / 250) - floor(($SCrok[0] / 250))) + (($CCrok[0] / 250) - floor(($CCrok[0] / 250))) * 250) * 16.00);

$Spodm3Rem = (((($Spod[0] / 250) - floor(($Spod[0] / 250))) + (($BSpod[0] / 250) - floor(($BSpod[0] / 250))) + (($GSpod[0] / 250) - floor(($GSpod[0] / 250))) * 250)* 16.00);

$Bism3Rem = (((($Bis[0] / 200) - floor(($Bis[0] / 200))) + (($TBis[0] / 200) - floor(($TBis[0] / 200))) + (($MBis[0] / 200) - floor(($MBis[0] / 200))) * 200) * 16.00);

$Arkm3Rem = (((($Ark[0] / 200) - floor(($Ark[0] / 200))) + (($CArk[0] / 200) - floor(($CArk[0] / 200))) + (($PArk[0] / 200) - floor(($PArk[0] / 200))) * 200) * 16.00);

$Mercm3Rem = (((($Merc[0] / 250) - floor(($Merc[0] / 250))) + (($MMerc[0] / 250) - floor(($MMerc[0] / 250))) + (($VMerc[0] / 250) - floor(($VMerc[0] / 250))) * 250) * 40.00);

$m3Remain = ($Veldm3Rem + $Scordm3Rem + $Pyroxm3Rem + $Plagm3Rem + $Ombm3Rem + $Kernm3Rem + $Jaspm3Rem + $Hemm3Rem + $Hedm3Rem + $Gnem3Rem + $Ochm3Rem + $Crokm3Rem + $Spodm3Rem + $Bism3Rem + $Arkm3Rem + $Mercm3Rem);



  //Compounds (Batch Size: 1)
$CondensedAlloy = $DB->getCol("SELECT CondensedAlloy FROM runs WHERE id='$ID' LIMIT 1");
$CrystalCompound = $DB->getCol("SELECT CrystalCompound FROM runs WHERE id='$ID' LIMIT 1");
$PreciousAlloy = $DB->getCol("SELECT PreciousAlloy FROM runs WHERE id='$ID' LIMIT 1");
$SheenCompound = $DB->getCol("SELECT SheenCompound FROM runs WHERE id='$ID' LIMIT 1");
$GleamingAlloy = $DB->getCol("SELECT GleamingAlloy FROM runs WHERE id='$ID' LIMIT 1");
$LucentCompound = $DB->getCol("SELECT LucentCompound FROM runs WHERE id='$ID' LIMIT 1");
$DarkCompound = $DB->getCol("SELECT DarkCompound FROM runs WHERE id='$ID' LIMIT 1");
$MotleyCompound = $DB->getCol("SELECT MotleyCompound FROM runs WHERE id='$ID' LIMIT 1");
$LusteringAlloy = $DB->getCol("SELECT LusteringAlloy FROM runs WHERE id='$ID' LIMIT 1");
$GlossyCompound = $DB->getCol("SELECT GlossyCompound FROM runs WHERE id='$ID' LIMIT 1");
$PlushCompound = $DB->getCol("SELECT PlushCompound FROM runs WHERE id='$ID' LIMIT 1");
$OpulentCompound = $DB->getCol("SELECT OpulentCompound FROM runs WHERE id='$ID' LIMIT 1");

  //Ice (Batch Size: 1)
$WhiteGlaze = $DB->getCol("SELECT WhiteGlaze FROM runs WHERE id='$ID' LIMIT 1");
$PristineWhiteGlaze = $DB->getCol("SELECT PristineWhiteGlaze FROM runs WHERE id='$ID' LIMIT 1");
$GlacialMass = $DB->getCol("SELECT GlacialMass FROM runs WHERE id='$ID' LIMIT 1");
$SmoothGlacialMass = $DB->getCol("SELECT SmoothGlacialMass FROM runs WHERE id='$ID' LIMIT 1");
$BlueIce = $DB->getCol("SELECT BlueIce FROM runs WHERE id='$ID' LIMIT 1");
$ThickBlueIce = $DB->getCol("SELECT ThickBlueIce FROM runs WHERE id='$ID' LIMIT 1");
$ClearIcicle = $DB->getCol("SELECT ClearIcicle FROM runs WHERE id='$ID' LIMIT 1");
$EnrichedClearIcicle = $DB->getCol("SELECT EnrichedClearIcicle FROM runs WHERE id='$ID' LIMIT 1");
$GlareCrust = $DB->getCol("SELECT GlareCrust FROM runs WHERE id='$ID' LIMIT 1");
$DarkGlitter = $DB->getCol("SELECT DarkGlitter FROM runs WHERE id='$ID' LIMIT 1");
$Gelidus = $DB->getCol("SELECT Gelidus FROM runs WHERE id='$ID' LIMIT 1");
$Krystallos = $DB->getCol("SELECT Krystallos FROM runs WHERE id='$ID' LIMIT 1");




//Mineral Refinement Calculations

	//Tritanium
$Tritanium = ((($Veldspar + $ConcentratedVeldspar + $DenseVeldspar) * 1000) + (($Scordite + $CondensedScordite + $MassiveScordite) * 833) + (($Pyroxeres + 		$SolidPyroxeres + $ViscousPyroxeres) * 844) + (($Plagioclase + $AzurePlagioclase + $RichPlagioclase) * 256) + (($Omber + $SilveryOmber + $GoldenOmber) * 307) + (($Kernite + $LuminousKernite + $FieryKernite) * 386) + (($Jaspet + $PureJaspet + $PristineJaspet) * 259) + (($Hemorphite + $VividHemorphite + $RadiantHemorphite) * 212) + (($Gneiss + $IridescentGneiss + $PrismaticGneiss) * 171) + (($DarkOchre + $OnyxOchre + $ObsidianOchre) * 250) + (($Crokite + $SharpCrokite + $CrystallineCrokite) * 331) + (($Spodumain + $BrightSpodumain + $GleamingSpodumain) * 700) + (($Arkonor + $CrimsonArkonor + $PrimeArkonor) * 300) + ($CondensedAlloy[0] * 88) + ($SheenCompound[0] * 124) + ($GleamingAlloy[0] * 299) + ($PlushCompound[0] * 3200));

	//Pyerite
$Pyerite = ((($Scordite + $CondensedScordite + $MassiveScordite) * 416) + (($Pyroxeres + $SolidPyroxeres + $ViscousPyroxeres) * 59) + (($Plagioclase + $AzurePlagioclase + $RichPlagioclase) * 512) + (($Omber + $SilveryOmber + $GoldenOmber) * 123) + (($Jaspet + $PureJaspet + $PristineJaspet) * 259) + (($Spodumain + $BrightSpodumain + $GleamingSpodumain) * 140) + (($Bistot + $TriclinicBistot + $MonoclinicBistot) * 170) + ($CondensedAlloy[0] * 44) + ($PreciousAlloy[0] * 7) + ($SheenCompound[0] * 44) + ($LucentCompound[0] * 174) + ($PlushCompound[0] * 800));
	
	//Mexallon
$Mexallon = ((($Pyroxeres + $SolidPyroxeres + $ViscousPyroxeres) * 120) + (($Plagioclase + $AzurePlagioclase + $RichPlagioclase) * 256) + (($Kernite + $LuminousKernite + $FieryKernite) * 773) + (($Jaspet + $PureJaspet + $PristineJaspet) * 518) + (($Gneiss + $IridescentGneiss + $PrismaticGneiss) * 171) + ($CondensedAlloy[0] * 11) + ($CrystalCompound[0] * 39) + ($LucentCompound[0] * 2) + ($LusteringAlloy[0] * 88) + ($GlossyCompound[0] * 210));

	//Isogen
$Isogen = ((($Omber + $SilveryOmber + $GoldenOmber) * 307) + (($Kernite + $LuminousKernite + $FieryKernite) * 386) + (($Hemorphite + $VividHemorphite + $RadiantHemorphite) * 212) + (($Hedbergite + $VitricHedbergite + $GlazedHedbergite) * 708) + (($Gneiss + $IridescentGneiss + $PrismaticGneiss) * 343) + ($CrystalCompound[0] * 2) + ($PreciousAlloy[0] * 18) + ($SheenCompound[0] * 23) + ($LucentCompound[0] * 11) + ($DarkCompound[0] * 23) + ($MotleyCompound[0] * 28) + ($LusteringAlloy[0] * 32) + ($PlushCompound[0] * 20));
	
	//Nocxium
$Nocxium = ((($Pyroxeres + $SolidPyroxeres + $ViscousPyroxeres) * 11) + (($Jaspet + $PureJaspet + $PristineJaspet) * 259) + (($Hemorphite + $VividHemorphite + $RadiantHemorphite) * 424) + (($Hedbergite + $VitricHedbergite + $GlazedHedbergite) * 354) + (($DarkOchre + $OnyxOchre + $ObsidianOchre) * 500) + (($Crokite + $SharpCrokite + $CrystallineCrokite) * 331) + ($SheenCompound[0] * 1) + ($GleamingAlloy[0] * 5) + ($LucentCompound[0] * 5) + ($DarkCompound[0] * 10) + ($MotleyCompound[0] * 13) + ($LusteringAlloy[0] * 35) + ($GlossyCompound[0] * 4));

	//Zydrine
$Zydrine = ((($Jaspet + $PureJaspet + $PristineJaspet) * 8) + (($Hemorphite + $VividHemorphite + $RadiantHemorphite) * 28) + (($Hedbergite + $VitricHedbergite + $GlazedHedbergite) * 32) + (($Gneiss + $IridescentGneiss + $PrismaticGneiss) * 171) + (($DarkOchre + $OnyxOchre + $ObsidianOchre) * 250) + (($Crokite + $SharpCrokite + $CrystallineCrokite) * 663) + (($Bistot + $TriclinicBistot + $MonoclinicBistot) * 341) + (($Arkonor + $CrimsonArkonor + $PrimeArkonor) * 166) + ($LusteringAlloy[0] * 1) + ($PlushCompound[0] * 9));

	//Megacyte
$Megacyte = ((($Spodumain + $BrightSpodumain + $GleamingSpodumain) * 140) + (($Bistot + $TriclinicBistot + $MonoclinicBistot) * 170) + (($Arkonor + $CrimsonArkonor + $PrimeArkonor) * 333) + ($GlossyCompound[0] * 3));

	//Morphite
$Morphite = ((($Mercoxit + $MagmaMercoxit + $VitreousMercoxit) * 530) + ($OpulentCompound[0] * 2));
 
 
 //Ice Refinement Calculations
	//Isotopes
		//Nitrogen Isotopes
$NitrogenIsotopes = ((($WhiteGlaze[0] * 300) + ($PristineWhiteGlaze[0] * 350)));
		
		//Hydrogen Isotopes
$HydrogenIsotopes = (($GlacialMass[0] * 300) + ($SmoothGlacialMass[0] * 350));
		
		//Oxygen Isotopes
$OxygenIsotopes = (($BlueIce[0] * 300) + ($ThickBlueIce[0] * 350));

		//Helium Isotopes
$HeliumIsotopes = (($ClearIcicle[0] * 300) + ($EnrichedClearIcicle[0] * 350));

	//Liquid Ozone
$LiquidOzone = ((($WhiteGlaze[0] + $GlacialMass[0] + $BlueIce[0] + $ClearIcicle[0]) * 25) + (($PristineWhiteGlaze[0] + $SmoothGlacialMass[0] + $ThickBlueIce[0] + $EnrichedClearIcicle[0]) *40) + ($Krystallos[0] * 250) + (($GlareCrust[0] + $Gelidus[0]) * 500) + ($DarkGlitter[0] * 1000));

	//Heavy Water
$HeavyWater	= ((($WhiteGlaze[0] + $GlacialMass[0] + $BlueIce[0] + $ClearIcicle[0]) * 50) + (($PristineWhiteGlaze[0] + $SmoothGlacialMass[0] + $ThickBlueIce[0] +$EnrichedClearIcicle[0]) * 75) + ($Krystallos[0] * 100) + ($Gelidus[0] * 250) + ($DarkGlitter[0] * 500) + ($GlareCrust[0] * 1000));
	
	//Strontium
$Strontium = (($WhiteGlaze[0] + $PristineWhiteGlaze[0] + $GlacialMass[0] + $SmoothGlacialMass[0] + $BlueIce[0] + $ThickBlueIce[0] + $ClearIcicle[0] + $EnrichedClearIcicle[0]) + ($GlareCrust[0] * 25) + ($DarkGlitter[0] * 50) + ($Gelidus[0] * 75) + ($Krystallos[0] * 100));

/*
echo "Veld " .  $Veldm3Rem ;
echo " Scord " . $Scordm3Rem ;
echo " Pyrox " . $Pyroxm3Rem ;
echo " Plag " . $Plagm3Rem ;
echo " Omb " . $Ombm3Rem ;
echo " Kern " . $Kernm3Rem; 
echo " Jasp " . $Jaspm3Rem ;
echo " Hem " . $Hemm3Rem ;
echo " Hed " . $Hedm3Rem ;
echo " Gne " . $Gnem3Rem ;
echo " Och " . $Ochm3Rem ;
echo " Crok " . $Crokm3Rem; 
echo " Spod " . $Spodm3Rem ;
echo " Bis " . $Bism3Rem ;
echo " Ark " . $Arkm3Rem ;
echo " Mrec " . $Mercm3Rem;
*/


 ?>