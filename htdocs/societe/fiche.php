<?php
/* Copyright (C) 2001-2007 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2003      Brian Fraval         <brian@fraval.org>
 * Copyright (C) 2004-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005      Eric Seigne          <eric.seigne@ryxeo.com>
 * Copyright (C) 2005-2012 Regis Houssin        <regis@dolibarr.fr>
 * Copyright (C) 2008	   Patrick Raguin       <patrick.raguin@auguria.net>
 * Copyright (C) 2010-2011 Juanjo Menent        <jmenent@2byte.es>
 * Copyright (C) 2010-2012 Herve Prot           <herve.prot@symeos.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       htdocs/societe/soc.php
 *  \ingroup    societe
 *  \brief      Third party card page
 */

require("../main.inc.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/company.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/images.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/lib/files.lib.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formadmin.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formcompany.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/html.formfile.class.php");
require_once(DOL_DOCUMENT_ROOT."/core/class/extrafields.class.php");
require_once(DOL_DOCUMENT_ROOT."/contact/class/contact.class.php");
if ($conf->adherent->enabled) require_once(DOL_DOCUMENT_ROOT."/adherents/class/adherent.class.php");
if ($conf->highcharts->enabled) dol_include_once("/highCharts/class/highCharts.class.php");

$langs->load("companies");
$langs->load("commercial");
$langs->load("bills");
$langs->load("banks");
$langs->load("users");
if ($conf->notification->enabled) $langs->load("mails");

$mesg=''; $error=0; $errors=array();

$action		= (GETPOST('action') ? GETPOST('action') : 'view');
$confirm	= GETPOST('confirm');
$socid		= GETPOST('id');
if ($user->societe_id) $socid=$user->societe_id;

$object = new Societe($couch);
$extrafields = new ExtraFields($db);

// Get object canvas (By default, this is not defined, so standard usage of dolibarr)
/*$object->getCanvas($socid);
$canvas = $object->canvas?$object->canvas:GETPOST("canvas");
if (! empty($canvas))
{
    require_once(DOL_DOCUMENT_ROOT."/core/class/canvas.class.php");
    $objcanvas = new Canvas($db, $action);
    $objcanvas->getCanvas('thirdparty', 'card', $canvas);
}*/

// Security check
$result = restrictedArea($user, 'societe', $socid, '&societe', '', 'fk_soc', 'rowid', $objcanvas);

// Initialize technical object to manage hooks of thirdparties. Note that conf->hooks_modules contains array array
include_once(DOL_DOCUMENT_ROOT.'/core/class/hookmanager.class.php');
$hookmanager=new HookManager($db);
$hookmanager->initHooks(array('thirdparty_extrafields'));


/*
 * Actions
 */

$parameters=array('id'=>$socid, 'objcanvas'=>$objcanvas);$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks
$error=$hookmanager->error; $errors=$hookmanager->errors;


if (empty($reshook))
{
    if ($_POST["getcustomercode"])
    {
        // We defined value code_client
        $_POST["code_client"]="Acompleter";
    }

    if ($_POST["getsuppliercode"])
    {
        // We defined value code_fournisseur
        $_POST["code_fournisseur"]="Acompleter";
    }

    // Add new third party
    if ((! $_POST["getcustomercode"] && ! $_POST["getsuppliercode"])
    && ($action == 'add' || $action == 'update') && $user->rights->societe->creer)
    {
        require_once(DOL_DOCUMENT_ROOT."/core/lib/functions2.lib.php");

        if ($action == 'update') $object->load($socid);
		else $object->canvas=$canvas;

        if (GETPOST("private") == 1)
        {
            $object->particulier       = GETPOST("private");

            $object->name              = empty($conf->global->MAIN_FIRSTNAME_NAME_POSITION)?trim($_POST["prenom"].' '.$_POST["nom"]):trim($_POST["nom"].' '.$_POST["prenom"]);
            $object->civilite_id       = (int)$_POST["civilite_id"];
            // Add non official properties
            $object->name_bis          = $_POST["nom"];
            $object->firstname         = $_POST["prenom"];
        }
        else
        {
            $object->name              = $_POST["nom"];
        }
        $object->address               = $_POST["adresse"];
        $object->zip                   = $_POST["zipcode"];
        $object->town                  = $_POST["town"];
        $object->country_id            = $_POST["country_id"];
        $object->state_id              = $_POST["departement_id"];

        $contact["Phone"]['value'] = $_POST["tel"];
        $contact["Phone"]["type"] = 'AC_TEL';
        $contact["Fax"]['value'] = $_POST["fax"];
        $contact["Fax"]["type"]   = 'AC_FAX';
        $contact["EMail"] = trim($_POST["email"]);
        $contact["EMail"]["type"]   = 'AC_EMAIL';
        
        $object->contact = $contact;
        
        $object->url                   = array(clean_url(trim($_POST["url"]),0));
        /*$object->idprof["idprof1"]     = $_POST["idprof1"];
        $object->idprof["idprof2"]     = $_POST["idprof2"];
        $object->idprof["idprof3"]     = $_POST["idprof3"];
        $object->idprof["idprof4"]     = $_POST["idprof4"];*/
        $object->prefix_comm           = $_POST["prefix_comm"];
        
        
        
        $object->code_compta->CustomerCode = $_POST["code_client"];
        $object->code_compta->SupplierCode = $_POST["code_fournisseur"];
        $object->capital               = (int)$_POST["capital"];
        $object->barcode               = $_POST["barcode"];

        $object->tva_intra             = $_POST["tva_intra"];
        $object->tva_assuj             = (int)$_POST["assujtva_value"];
        $object->status                = (int)$_POST["status"];

        // Local Taxes
        $object->localtax1_assuj       = (int)$_POST["localtax1assuj_value"];
        $object->localtax2_assuj       = (int)$_POST["localtax2assuj_value"];

        $object->forme_juridique_code  = $_POST["forme_juridique_code"];
        $object->effectif_id           = $_POST["effectif_id"];
        if (GETPOST("private") == 1)
        {
            $object->typent_id         = 8; // TODO predict another method if the field "special" change of rowid
        }
        else
        {
            $object->typent_id         = $_POST["typent_id"];
        }

        $object->client                = (int)$_POST["client"];
        $object->fournisseur           = (int)$_POST["fournisseur"];
        $object->fournisseur_categorie = $_POST["fournisseur_categorie"];

        $object->commercial_id         = $_POST["commercial_id"];
        $object->default_lang          = $_POST["default_lang"];

        // Get extra fields
        foreach($_POST as $key => $value)
        {
            if (preg_match("/^options_/",$key))
            {
                $object->array_options[$key]=$_POST[$key];
            }
        }
        
        if($conf->map->enabled)
        {
            //Retire le CEDEX de la ville :
            $town=$_POST["town"];
            $town=strtolower($town);
            $find="cedex";
            $pos=strpos($town, $find);
            if($pos!=false)
            {
                $town=substr($town, 0,$pos);
            }
            $apiUrl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode ($_POST["adresse"].",".$_POST["zipcode"].",".$town);
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $apiUrl);
            curl_setopt($c, CURLOPT_HEADER, false);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
            // make the call
            $json = curl_exec($c);
            $response = json_decode($json);
            curl_close($c);
            if($response->status == "OK"){
                $object->gps = array($response->results[0]->geometry->location->lat,$response->results[0]->geometry->location->lng);
            }
            else
            {
                $object->gps = array(0,0);
            }
        }

        if (GETPOST('deletephoto')) $object->logo = '';
        else if (! empty($_FILES['photo']['name'])) $object->logo = dol_sanitizeFileName($_FILES['photo']['name']);

        // Check parameters
        if (empty($_POST["cancel"]))
        {
            if (! empty($object->contact['EMail']->value) && ! isValidEMail($object->contact['EMail']->value))
            {
                $langs->load("errors");
                $error++; $errors[] = $langs->trans("ErrorBadEMail",$object->contact['EMail']->value);
                $action = ($action=='add'?'create':'edit');
            }
            if (! empty($object->url[0]) && ! isValidUrl($object->url[0]))
            {
                $langs->load("errors");
                $error++; $errors[] = $langs->trans("ErrorBadUrl",$object->url[0]);
                $action = ($action=='add'?'create':'edit');
            }
            if ($object->fournisseur && ! $conf->fournisseur->enabled)
            {
                $langs->load("errors");
                $error++; $errors[] = $langs->trans("ErrorSupplierModuleNotEnabled");
                $action = ($action=='add'?'create':'edit');
            }

        	/*for ($i = 1; $i < 3; $i++)
        	{
    			$slabel="idprof".$i;
        		if (($_POST[$slabel] && $object->id_prof_verifiable($i)))
				{
					if($object->id_prof_exists($i,$_POST["$slabel"],$object->id()))
					{
						$langs->load("errors");
                		$error++; $errors[] = $langs->transcountry('ProfId'.$i, $object->country_id)." ".$langs->trans("ErrorProdIdAlreadyExist", $_POST[$slabel]);
                		$action = ($action=='add'?'create':'edit');
					}
				}
			}*/
        }
        if (! $error)
        {
            if ($action == 'add')
            {
                if (! empty($conf->global->MAIN_FIRST_TO_UPPER)) $object->name=ucwords($object->name);
        
                dol_syslog(get_class($object)."::create ".$object->name);

                // Check parameters
                if (! empty($conf->global->SOCIETE_MAIL_REQUIRED) && ! isValidEMail($object->email))
                {
                    $langs->load("errors");
                    $message = $langs->trans("ErrorBadEMail",$object->email);
                    return -1;
                }

                $object->tms=dol_now();                

                // For automatic creation during create action (not used by Dolibarr GUI, can be used by scripts)
                if ($object->code_client == -1)      $object->get_codeclient($object->prefix_comm,0);
                if ($object->code_fournisseur == -1) $object->get_codefournisseur($object->prefix_comm,1);
                
                if (! $message)
                {
                    try {
                        $object->record();
                        Header("Location: ".$_SERVER['PHP_SELF']."?id=".$object->id());
                        exit;
                    }
                    catch (Exception $e) {
                        $message="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
                        dol_syslog("Ego::Create ".$message, LOG_ERR);
                    }
                }

                $message='<div class="error">'.$message.'</div>';
                $action="create";
                
                $result = 0; // TODO go next
                if ($result >= 0)
                {
                    if ($object->particulier)
                    {
                        dol_syslog("This thirdparty is a personal people",LOG_DEBUG);
                        $contact=new Contact($db);

     			$contact->civilite_id   		= $object->civilite_id;
                        $contact->name				= $object->name_bis;
                        $contact->firstname			= $object->firstname;
                        $contact->address			= $object->address;
                        $contact->zip				= $object->zip;
                        $contact->town				= $object->town;
                        $contact->state_id                  	= $object->state_id;
                        $contact->country_id                    = $object->country_id;
                        $contact->socid				= $object->id();	// fk_soc
                        $contact->status			= 1;
                        $contact->email				= $object->email;
			$contact->phone_pro			= $object->tel;
			$contact->fax				= $object->fax;
                        $contact->priv				= 0;
        
                        $result=$contact->create($user);
                        if (! $result >= 0)
                        {
                            $error=$contact->error; $errors=$contact->errors;
                        }
                    }

                    // Gestion du logo de la société
                    $dir     = $conf->societe->multidir_output[$conf->entity]."/".$object->id()."/logos/";
                    $file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
                    if ($file_OK)
                    {
                        if (image_format_supported($_FILES['photo']['name']))
                        {
                            dol_mkdir($dir);

                            if (@is_dir($dir))
                            {
                                $newfile=$dir.'/'.dol_sanitizeFileName($_FILES['photo']['name']);
                                $result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1);

                                if (! $result > 0)
                                {
                                    $errors[] = "ErrorFailedToSaveFile";
                                }
                                else
                                {
                                    // Create small thumbs for company (Ratio is near 16/9)
                                    // Used on logon for example
                                    $imgThumbSmall = vignette($newfile, $maxwidthsmall, $maxheightsmall, '_small', $quality);

                                    // Create mini thumbs for company (Ratio is near 16/9)
                                    // Used on menu or for setup page for example
                                    $imgThumbMini = vignette($newfile, $maxwidthmini, $maxheightmini, '_mini', $quality);
                                }
                            }
                        }
                    }
                    // Gestion du logo de la société
                }
                else
                {
                    $error=$object->error; $errors=$object->errors;
                }

                if ($result >= 0)
                {
                    $db->commit();

                    $url=$_SERVER["PHP_SELF"]."?id=".$object->id();
                    if (($object->client == 1 || $object->client == 3) && empty($conf->global->SOCIETE_DISABLE_CUSTOMERS)) $url=DOL_URL_ROOT."/comm/fiche.php?socid=".$object->id();
                    else if ($object->fournisseur == 1) $url=DOL_URL_ROOT."/fourn/fiche.php?socid=".$object->id();
                    Header("Location: ".$url);
                    exit;
                }
                else
                {
                    $db->rollback();
                    $action='create';
                }
            }

            if ($action == 'update')
            {
                if ($_POST["cancel"])
                {
                    Header("Location: ".$_SERVER["PHP_SELF"]."?id=".$socid);
                    exit;
                }

		$backtopage='';
                if (! empty($_POST["backtopage"])) $backtopage=$_POST["backtopage"];

                $oldcopy=dol_clone($object);
                
                ### Calcul des coordonnées GPS
                if($conf->map->enabled)
                {
                    //Retire le CEDEX de la ville :
                    $town=$_POST["town"];
                    $town=strtolower($town);
                    $find="cedex";
                    $pos=strpos($town, $find);
                    if($pos!=false)
                    {
                        $town=substr($town, 0,$pos);
                        //print $town;exit;
                    }
                    $apiUrl = "http://maps.googleapis.com/maps/api/geocode/json?sensor=false&address=".urlencode ($_POST["adresse"].",".$_POST["zipcode"].",".$_POST["town"]);
                    $c = curl_init();
                    curl_setopt($c, CURLOPT_URL, $apiUrl);
                    curl_setopt($c, CURLOPT_HEADER, false);
                    curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                    // make the call
                    $json = curl_exec($c);
                    $response = json_decode($json);
                    curl_close($c);
                    if($response->status == "OK")
                    {
                        $object->gps = array($response->results[0]->geometry->location->lat,$response->results[0]->geometry->location->lng);
                    }
                    else
                    {
                        $object->gps = array(0,0);
                    }
                }

                // To not set code if third party is not concerned. But if it had values, we keep them.
                if (empty($object->client) && empty($oldcopy->code_client))          $object->code_client='';
                if (empty($object->fournisseur)&& empty($oldcopy->code_fournisseur)) $object->code_fournisseur='';
                //var_dump($object);exit;

                $result = $object->update($socid,$user,1,$oldcopy->codeclient_modifiable(),$oldcopy->codefournisseur_modifiable());
                
                if ($result <=  0)
                {
                    $error = $object->error; $errors = $object->errors;
                }

                // Gestion du logo de la société
                $dir     = $conf->societe->multidir_output[$object->entity]."/".$object->id()."/logos";
                $file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
                if ($file_OK)
                {
                    if (GETPOST('deletephoto'))
                    {
                        $fileimg=$dir.'/'.$object->logo;
                        $dirthumbs=$dir.'/thumbs';
                        dol_delete_file($fileimg);
                        dol_delete_dir_recursive($dirthumbs);
                    }

                    if (image_format_supported($_FILES['photo']['name']) > 0)
                    {
                        dol_mkdir($dir);

                        if (@is_dir($dir))
                        {
                            $newfile=$dir.'/'.dol_sanitizeFileName($_FILES['photo']['name']);
                            $result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1);

                            if (! $result > 0)
                            {
                                $errors[] = "ErrorFailedToSaveFile";
                            }
                            else
                            {
                                // Create small thumbs for company (Ratio is near 16/9)
                                // Used on logon for example
                                $imgThumbSmall = vignette($newfile, $maxwidthsmall, $maxheightsmall, '_small', $quality);

                                // Create mini thumbs for company (Ratio is near 16/9)
                                // Used on menu or for setup page for example
                                $imgThumbMini = vignette($newfile, $maxwidthmini, $maxheightmini, '_mini', $quality);
                            }
                        }
                    }
                    else
                    {
                        $errors[] = "ErrorBadImageFormat";
                    }
                }
                // Gestion du logo de la société

                if (! $error && ! count($errors))
                {

                    Header("Location: ".$_SERVER["PHP_SELF"]."?id=".$socid);
                    exit;
                }
                else
                {
                    //$object->id() = $socid;
                    $action= "edit";
                }
            }
        }
    }

    // Delete third party
    if ($action == 'confirm_delete' && $confirm == 'yes' && $user->rights->societe->supprimer)
    {
        
        $object->load($socid);
        $result = $object->delete();

        if ($result > 0)
        {
            Header("Location: ".DOL_URL_ROOT."/comm/list.php");
            exit;
        }
        else
        {
            $langs->load("errors");
            $error=$langs->trans($object->error); $errors = $object->errors;
            $action='';
        }
    }


    /*
     * Generate document
     */
    if ($action == 'builddoc')  // En get ou en post
    {
        if (is_numeric(GETPOST('model')))
        {
            $error=$langs->trans("ErrorFieldRequired",$langs->transnoentities("Model"));
        }
        else
        {
            require_once(DOL_DOCUMENT_ROOT.'/core/modules/societe/modules_societe.class.php');

            $object->load($socid);
            $object->fetch_thirdparty();

            // Define output language
            $outputlangs = $langs;
            $newlang='';
            if ($conf->global->MAIN_MULTILANGS && empty($newlang) && ! empty($_REQUEST['lang_id'])) $newlang=$_REQUEST['lang_id'];
            if ($conf->global->MAIN_MULTILANGS && empty($newlang)) $newlang=$fac->client->default_lang;
            if (! empty($newlang))
            {
                $outputlangs = new Translate("",$conf);
                $outputlangs->setDefaultLang($newlang);
            }
            $result=thirdparty_doc_create($db, $object->id(), '', $_REQUEST['model'], $outputlangs);
            if ($result <= 0)
            {
                dol_print_error($db,$result);
                exit;
            }
            else
            {
                Header('Location: '.$_SERVER["PHP_SELF"].'?id='.$object->id().(empty($conf->global->MAIN_JUMP_TAG)?'':'#builddoc'));
                exit;
            }
        }
    }
}



/*
 *  View
 */

// fetch optionals attributes and labels
$extralabels=$extrafields->fetch_name_optionals_label('company');

$help_url='EN:Module_Third_Parties|FR:Module_Tiers|ES:Empresas';
llxHeader('',$langs->trans("ThirdParty"),$help_url);

$form = new Form($db);
$formfile = new FormFile($db);
$formadmin = new FormAdmin($db);
$formcompany = new FormCompany($db);

$countrynotdefined=$langs->trans("ErrorSetACountryFirst").' ('.$langs->trans("SeeAbove").')';


if (is_object($objcanvas) && $objcanvas->displayCanvasExists($action))
{
    // -----------------------------------------
    // When used with CANVAS
    // -----------------------------------------
    if (! $objcanvas->hasActions() && $socid)
 	{
	     $object->load($socid);                // For use with "pure canvas" (canvas that contains templates only)
 	}
   	$objcanvas->assign_values($action, $socid);	// Set value for templates
    $objcanvas->display_canvas($action);		// Show template
}
else
{
    // -----------------------------------------
    // When used in standard mode
    // -----------------------------------------
    if ($action == 'create')
    {
        /*
         *  Creation
         */

        // Load object modCodeTiers
        $module=$conf->global->SOCIETE_CODECLIENT_ADDON;
        if (! $module) dolibarr_error('',$langs->trans("ErrorModuleThirdPartyCodeInCompanyModuleNotDefined"));
        if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php')
        {
            $module = substr($module, 0, dol_strlen($module)-4);
        }
        $dirsociete=array_merge(array('/core/modules/societe/'),$conf->societe_modules);
        foreach ($dirsociete as $dirroot)
        {
            $res=dol_include_once($dirroot.$module.".php");
            if ($res) break;
        }
        $modCodeClient = new $module;
        $module=$conf->global->SOCIETE_CODEFOURNISSEUR_ADDON;
        if (! $module) $module=$conf->global->SOCIETE_CODECLIENT_ADDON;
        if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php')
        {
            $module = substr($module, 0, dol_strlen($module)-4);
        }
        $dirsociete=array_merge(array('/core/modules/societe/'),$conf->societe_modules);
        foreach ($dirsociete as $dirroot)
        {
            $res=dol_include_once($dirroot.$module.".php");
            if ($res) break;
        }
        $modCodeFournisseur = new $module;

        //if ($_GET["type"]=='cp') { $object->client=3; }
        if (GETPOST("type")!='f')  { $object->client=3; }
        if (GETPOST("type")=='c')  { $object->client=1; }
        if (GETPOST("type")=='p')  { $object->client=2; }
        if ($conf->fournisseur->enabled && (GETPOST("type")=='f' || GETPOST("type")==''))  { $object->fournisseur=1; }
        if (GETPOST("private")==1) { $object->particulier=1; }

        $object->name				= $_POST["nom"];
        $object->firstname			= $_POST["prenom"];
        $object->particulier		= GETPOST('private', 'int');
        $object->prefix_comm		= $_POST["prefix_comm"];
        $object->client				= $_POST["client"]?$_POST["client"]:$object->client;
        $object->code_client		= $_POST["code_client"];
        $object->fournisseur		= $_POST["fournisseur"]?$_POST["fournisseur"]:$object->fournisseur;
        $object->code_fournisseur	= $_POST["code_fournisseur"];
        $object->address			= $_POST["adresse"];
        $object->zip				= $_POST["zipcode"];
        $object->town				= $_POST["town"];
        $object->state_id			= $_POST["departement_id"];
        $object->tel				= $_POST["tel"];
        $object->fax				= $_POST["fax"];
        $object->email				= $_POST["email"];
        $object->url				= $_POST["url"];
        $object->capital			= $_POST["capital"];
        $object->barcode			= $_POST["barcode"];
        $object->idprof1			= $_POST["idprof1"];
        $object->idprof2			= $_POST["idprof2"];
        $object->idprof3			= $_POST["idprof3"];
        $object->idprof4			= $_POST["idprof4"];
        $object->typent_id			= $_POST["typent_id"];
        $object->effectif_id		= $_POST["effectif_id"];
        $object->civility_id		= $_POST["civilite_id"];

        $object->tva_assuj			= $_POST["assujtva_value"];
        $object->status				= $_POST["status"];

        //Local Taxes
        $object->localtax1_assuj	= $_POST["localtax1assuj_value"];
        $object->localtax2_assuj	= $_POST["localtax2assuj_value"];

        $object->tva_intra			= $_POST["tva_intra"];

        $object->commercial_id		= $_POST["commercial_id"];
        $object->default_lang		= $_POST["default_lang"];

        $object->logo = dol_sanitizeFileName($_FILES['photo']['name']);

        // Gestion du logo de la société
        $dir     = $conf->societe->multidir_output[$object->entity]."/".$object->id()."/logos";
        $file_OK = is_uploaded_file($_FILES['photo']['tmp_name']);
        if ($file_OK)
        {
            if (image_format_supported($_FILES['photo']['name']))
            {
                dol_mkdir($dir);

                if (@is_dir($dir))
                {
                    $newfile=$dir.'/'.dol_sanitizeFileName($_FILES['photo']['name']);
                    $result = dol_move_uploaded_file($_FILES['photo']['tmp_name'], $newfile, 1);

                    if (! $result > 0)
                    {
                        $errors[] = "ErrorFailedToSaveFile";
                    }
                    else
                    {
                        // Create small thumbs for company (Ratio is near 16/9)
                        // Used on logon for example
                        $imgThumbSmall = vignette($newfile, $maxwidthsmall, $maxheightsmall, '_small', $quality);

                        // Create mini thumbs for company (Ratio is near 16/9)
                        // Used on menu or for setup page for example
                        $imgThumbMini = vignette($newfile, $maxwidthmini, $maxheightmini, '_mini', $quality);
                    }
                }
            }
        }

        // We set country_id, country_id and country for the selected country
        $object->country_id=$_POST["country_id"]?$_POST["country_id"]:$mysoc->country_id;
        if ($object->country_id)
        {
            $tmparray=getCountry($object->country_id,'all');
            $object->country_id=$tmparray['code'];
            $object->country=$tmparray['label'];
        }
        $object->forme_juridique_code=$_POST['forme_juridique_code'];
        /* Show create form */

        print_fiche_titre($langs->trans("NewCompany"));

        if ($conf->use_javascript_ajax)
        {
            print "\n".'<script type="text/javascript">';
            print '$(document).ready(function () {
						id_te_private=8;
                        id_ef15=1;
                        is_private='.(GETPOST("private")?GETPOST("private"):0).';
						if (is_private) {
							$(".individualline").show();
						} else {
							$(".individualline").hide();
						}
                        $("#radiocompany").click(function() {
                        	$(".individualline").hide();
                        	$("#typent_id").val(0);
                        	$("#effectif_id").val(0);
                        	$("#TypeName").html(document.formsoc.ThirdPartyName.value);
                        	document.formsoc.private.value=0;
                        });
                        $("#radioprivate").click(function() {
                        	$(".individualline").show();
                        	$("#typent_id").val(id_te_private);
                        	$("#effectif_id").val(id_ef15);
                        	$("#TypeName").html(document.formsoc.LastName.value);
                        	document.formsoc.private.value=1;
                        });
                        $("#selectcountry_id").change(function() {
                        	document.formsoc.action.value="create";
                        	document.formsoc.submit();
                        });
                     });';
            print '</script>'."\n";

            print "<br>\n";
            print $langs->trans("ThirdPartyType").': &nbsp; ';
            print '<input type="radio" id="radiocompany" class="flat" name="private" value="0"'.(! GETPOST("private")?' checked="checked"':'');
            print '> '.$langs->trans("Company/Fundation");
            print ' &nbsp; &nbsp; ';
            print '<input type="radio" id="radioprivate" class="flat" name="private" value="1"'.(! GETPOST("private")?'':' checked="checked"');
            print '> '.$langs->trans("Individual");
            print ' ('.$langs->trans("ToCreateContactWithSameName").')';
            print "<br>\n";
            print "<br>\n";
        }


        dol_htmloutput_errors($error,$errors);

        print '<form enctype="multipart/form-data" action="'.$_SERVER["PHP_SELF"].'" method="post" name="formsoc">';

        print '<input type="hidden" name="action" value="add">';
        print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
        print '<input type="hidden" name="private" value='.$object->particulier.'>';
        print '<input type="hidden" name="type" value='.GETPOST("type").'>';
        print '<input type="hidden" name="LastName" value="'.$langs->trans('LastName').'">';
        print '<input type="hidden" name="ThirdPartyName" value="'.$langs->trans('ThirdPartyName').'">';
        if ($modCodeClient->code_auto || $modCodeFournisseur->code_auto) print '<input type="hidden" name="code_auto" value="1">';

        print '<table class="border" width="100%">';

        // Name, firstname
        if ($object->particulier || GETPOST("private"))
        {
            print '<tr><td><span id="TypeName" class="fieldrequired">'.$langs->trans('LastName').'</span></td><td'.(empty($conf->global->SOCIETE_USEPREFIX)?' colspan="3"':'').'><input type="text" size="30" maxlength="60" name="nom" value="'.$object->name.'"></td>';
            if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
            {
                print '<td>'.$langs->trans('Prefix').'</td><td><input type="text" size="5" maxlength="5" name="prefix_comm" value="'.$object->prefix_comm.'"></td>';
            }
            print '</tr>';
        }
        else
        {
            print '<tr><td><span span id="TypeName" class="fieldrequired">'.$langs->trans('ThirdPartyName').'</span></td><td'.(empty($conf->global->SOCIETE_USEPREFIX)?' colspan="3"':'').'><input type="text" size="30" maxlength="60" name="nom" value="'.$object->name.'"></td>';
            if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
            {
                print '<td>'.$langs->trans('Prefix').'</td><td><input type="text" size="5" maxlength="5" name="prefix_comm" value="'.$object->prefix_comm.'"></td>';
            }
            print '</tr>';
        }
        // If javascript on, we show option individual
        if ($conf->use_javascript_ajax)
        {
            print '<tr class="individualline"><td>'.$langs->trans('FirstName').'</td><td><input type="text" size="30" name="prenom" value="'.$object->firstname.'"></td>';
            print '<td colspan=2>&nbsp;</td></tr>';
            print '<tr class="individualline"><td>'.$langs->trans("UserTitle").'</td><td>';
            print $formcompany->select_civility($object->civility_id).'</td>';
            print '<td colspan=2>&nbsp;</td></tr>';
        }

        // Prospect/Customer
        print '<tr><td width="25%"><span class="fieldrequired">'.$langs->trans('ProspectCustomer').'</span></td><td width="25%"><select class="flat" name="client">';
        $selected=isset($_POST['client'])?GETPOST('client'):$object->client;
        if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS)) print '<option value="2"'.($selected==2?' selected="selected"':'').'>'.$langs->trans('Prospect').'</option>';
        if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS)) print '<option value="3"'.($selected==3?' selected="selected"':'').'>'.$langs->trans('ProspectCustomer').'</option>';
        print '<option value="1"'.($selected==1?' selected="selected"':'').'>'.$langs->trans('Customer').'</option>';
        print '<option value="0"'.($selected==0?' selected="selected"':'').'>'.$langs->trans('NorProspectNorCustomer').'</option>';
        print '</select></td>';

        print '<td width="25%">'.$langs->trans('CustomerCode').'</td><td width="25%">';
        print '<table class="nobordernopadding"><tr><td>';
        $tmpcode=$object->code_client;
        if ($modCodeClient->code_auto) $tmpcode=$modCodeClient->getNextValue($object,0);
        print '<input type="text" name="code_client" size="16" value="'.$tmpcode.'" maxlength="15">';
        print '</td><td>';
        $s=$modCodeClient->getToolTip($langs,$object,0);
        print $form->textwithpicto('',$s,1);
        print '</td></tr></table>';

        print '</td></tr>';

        if ($conf->fournisseur->enabled && ! empty($user->rights->fournisseur->lire))
        {
            // Supplier
            print '<tr>';
            print '<td><span class="fieldrequired">'.$langs->trans('Supplier').'</span></td><td>';
            print $form->selectyesno("fournisseur",(isset($_POST['fournisseur'])?GETPOST('fournisseur'):$object->fournisseur),1);
            print '</td>';
            print '<td>'.$langs->trans('SupplierCode').'</td><td>';
            print '<table class="nobordernopadding"><tr><td>';
            $tmpcode=$object->code_fournisseur;
            if ($modCodeFournisseur->code_auto) $tmpcode=$modCodeFournisseur->getNextValue($object,1);
            print '<input type="text" name="code_fournisseur" size="16" value="'.$tmpcode.'" maxlength="15">';
            print '</td><td>';
            $s=$modCodeFournisseur->getToolTip($langs,$object,1);
            print $form->textwithpicto('',$s,1);
            print '</td></tr></table>';
            print '</td></tr>';

            // Category
            /*if ($object->fournisseur)
            {
                $load = $object->LoadSupplierCateg();
                if ( $load == 0)
                {
                    if (count($object->SupplierCategories) > 0)
                    {
                        print '<tr>';
                        print '<td>'.$langs->trans('SupplierCategory').'</td><td colspan="3">';
                        print $form->selectarray("fournisseur_categorie",$object->SupplierCategories,$_POST["fournisseur_categorie"],1);
                        print '</td></tr>';
                    }
                }
            }*/
        }

        // Status
        print '<tr><td>'.$langs->trans('Status').'</td><td colspan="3">';
        print $form->selectarray('status', array('0'=>$langs->trans('ActivityCeased'),'1'=>$langs->trans('InActivity')),1);
        print '</td></tr>';

        // Barcode
        if ($conf->global->MAIN_MODULE_BARCODE)
        {
            print '<tr><td>'.$langs->trans('Gencod').'</td><td colspan="3"><input type="text" name="barcode" value="'.$object->barcode.'">';
            print '</td></tr>';
        }

        // Address
        print '<tr><td valign="top">'.$langs->trans('Address').'</td><td colspan="3"><textarea name="adresse" cols="40" rows="3" wrap="soft">';
        print $object->address;
        print '</textarea></td></tr>';

        // Zip / Town
        print '<tr><td>'.$langs->trans('Zip').'</td><td>';
        print $formcompany->select_ziptown($object->zip,'zipcode',array('town','selectcountry_id','departement_id'),6);
        print '</td><td>'.$langs->trans('Town').'</td><td>';
        print $formcompany->select_ziptown($object->town,'town',array('zipcode','selectcountry_id','departement_id'));
        print '</td></tr>';

        // Country
        print '<tr><td width="25%">'.$langs->trans('Country').'</td><td colspan="3">';
        print $form->select_country($object->country_id,'country_id');
        if ($user->admin) print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"),1);
        print '</td></tr>';

        // State
        if (empty($conf->global->SOCIETE_DISABLE_STATE))
        {
            print '<tr><td>'.$langs->trans('State').'</td><td colspan="3">';
            if ($object->country_id) print $formcompany->select_state($object->state_id,$object->country_id,'departement_id');
            else print $countrynotdefined;
            print '</td></tr>';
        }

        // Phone / Fax
        print '<tr><td>'.$langs->trans('Phone').'</td><td><input type="text" name="tel" value="'.$object->tel.'"></td>';
        print '<td>'.$langs->trans('Fax').'</td><td><input type="text" name="fax" value="'.$object->fax.'"></td></tr>';

        print '<tr><td>'.$langs->trans('EMail').($conf->global->SOCIETE_MAIL_REQUIRED?'*':'').'</td><td><input type="text" name="email" size="32" value="'.$object->email.'"></td>';
        print '<td>'.$langs->trans('Web').'</td><td><input type="text" name="url" size="32" value="'.$object->url.'"></td></tr>';

        // Prof ids
        $i=1; $j=0;
        while ($i <= 6)
        {
            $idprof=$langs->transcountry('ProfId'.$i,$object->country_id);
            if ($idprof!='-')
            {
                if (($j % 2) == 0) print '<tr>';
                print '<td>'.$idprof.'</td><td>';
                $key='idprof'.$i;
                print $formcompany->get_input_id_prof($i,'idprof'.$i,$object->$key,$object->country_id);
                print '</td>';
                if (($j % 2) == 1) print '</tr>';
                $j++;
            }
            $i++;
        }
        if ($j % 2 == 1) print '<td colspan="2"></td></tr>';

        // Assujeti TVA
        $form = new Form($db);
        print '<tr><td>'.$langs->trans('VATIsUsed').'</td>';
        print '<td>';
        print $form->selectyesno('assujtva_value',1,1);     // Assujeti par defaut en creation
        print '</td>';
        print '<td nowrap="nowrap">'.$langs->trans('VATIntra').'</td>';
        print '<td nowrap="nowrap">';
        $s = '<input type="text" class="flat" name="tva_intra" size="12" maxlength="20" value="'.$object->tva_intra.'">';

        if (empty($conf->global->MAIN_DISABLEVATCHECK))
        {
            $s.=' ';

            if ($conf->use_javascript_ajax)
            {
                print "\n";
                print '<script language="JavaScript" type="text/javascript">';
                print "function CheckVAT(a) {\n";
                print "newpopup('".DOL_URL_ROOT."/societe/checkvat/checkVatPopup.php?vatNumber='+a,'".dol_escape_js($langs->trans("VATIntraCheckableOnEUSite"))."',500,230);\n";
                print "}\n";
                print '</script>';
                print "\n";
                $s.='<a href="#" onclick="javascript: CheckVAT(document.formsoc.tva_intra.value);">'.$langs->trans("VATIntraCheck").'</a>';
                $s = $form->textwithpicto($s,$langs->trans("VATIntraCheckDesc",$langs->trans("VATIntraCheck")),1);
            }
            else
            {
                $s.='<a href="'.$langs->transcountry("VATIntraCheckURL",$object->id_pays).'" target="_blank">'.img_picto($langs->trans("VATIntraCheckableOnEUSite"),'help').'</a>';
            }
        }
        print $s;
        print '</td>';
        print '</tr>';

        // Type - Size
        print '<tr><td>'.$langs->trans("ThirdPartyType").'</td><td>'."\n";
        print $form->selectarray("typent_id",$formcompany->typent_array(0), $object->typent_id);
        if ($user->admin) print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"),1);
        print '</td>';
        print '<td>'.$langs->trans("Staff").'</td><td>';
        print $form->selectarray("effectif_id",$formcompany->effectif_array(0), $object->effectif_id);
        if ($user->admin) print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"),1);
        print '</td></tr>';

        // Legal Form
        print '<tr><td>'.$langs->trans('JuridicalStatus').'</td>';
        print '<td colspan="3">';
        if ($object->country_id)
        {
            $formcompany->select_forme_juridique($object->forme_juridique_code,$object->country_id);
        }
        else
        {
            print $countrynotdefined;
        }
        print '</td></tr>';

        // Capital
        print '<tr><td>'.$langs->trans('Capital').'</td><td colspan="3"><input type="text" name="capital" size="10" value="'.$object->capital.'"> '.$langs->trans("Currency".$conf->currency).'</td></tr>';

        // Local Taxes
        // TODO add specific function by country
        if($mysoc->country_id=='ES')
        {
            if($mysoc->localtax1_assuj=="1" && $mysoc->localtax2_assuj=="1")
            {
                print '<tr><td>'.$langs->trans("LocalTax1IsUsedES").'</td><td>';
                print $form->selectyesno('localtax1assuj_value',0,1);
                print '</td><td>'.$langs->trans("LocalTax2IsUsedES").'</td><td>';
                print $form->selectyesno('localtax2assuj_value',0,1);
                print '</td></tr>';

            }
            elseif($mysoc->localtax1_assuj=="1")
            {
                print '<tr><td>'.$langs->trans("LocalTax1IsUsedES").'</td><td colspan="3">';
                print $form->selectyesno('localtax1assuj_value',0,1);
                print '</td><tr>';
            }
            elseif($mysoc->localtax2_assuj=="1")
            {
                print '<tr><td>'.$langs->trans("LocalTax2IsUsedES").'</td><td colspan="3">';
                print $form->selectyesno('localtax2assuj_value',0,1);
                print '</td><tr>';
            }
        }

        if ($conf->global->MAIN_MULTILANGS)
        {
            print '<tr><td>'.$langs->trans("DefaultLang").'</td><td colspan="3">'."\n";
            print $formadmin->select_language(($object->default_lang?$object->default_lang:$conf->global->MAIN_LANG_DEFAULT),'default_lang',0,0,1);
            print '</td>';
            print '</tr>';
        }

        if ($user->rights->societe->client->voir)
        {
            // Assign a Name
            print '<tr>';
            print '<td>'.$langs->trans("AllocateCommercial").'</td>';
            print '<td colspan="3">';
            $form->select_users($object->commercial_id,'commercial_id',1);
            print '</td></tr>';
        }

        // Other attributes
        $parameters=array('colspan' => ' colspan="3"');
        $reshook=$hookmanager->executeHooks('formObjectOptions',$parameters,$object,$action);    // Note that $action and $object may have been modified by hook
        if (empty($reshook) && ! empty($extrafields->attribute_label))
        {
            foreach($extrafields->attribute_label as $key=>$label)
            {
                $value=(isset($_POST["options_".$key])?$_POST["options_".$key]:$object->array_options["options_".$key]);
                print '<tr><td>'.$label.'</td><td colspan="3">';
                print $extrafields->showInputField($key,$value);
                print '</td></tr>'."\n";
            }
        }

        // Ajout du logo
        print '<tr>';
        print '<td>'.$langs->trans("Logo").'</td>';
        print '<td colspan="3">';
        print '<input class="flat" type="file" name="photo" id="photoinput" />';
        print '</td>';
        print '</tr>';

        print '</table>'."\n";

        print '<br><center>';
        print '<input type="submit" class="button" value="'.$langs->trans('AddThirdParty').'">';
        print '</center>'."\n";

        print '</form>'."\n";
    }
    elseif ($action == 'edit')
    {
        /*
         * Edition
         */

        //print_fiche_titre($langs->trans("EditCompany"));

        if ($socid)
        {
            try {
                $object->load($socid);
            } catch (Exception $e) {
                $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
                dol_syslog("soc::edit ".$error, LOG_ERR);
                print $error;
                exit;
            }    
            $res=$object->fetch_optionals($object->id(),$extralabels);
            //if ($res < 0) { dol_print_error($db); exit; }


	        $head = societe_prepare_head($object);

	        dol_fiche_head($head, 'card', $langs->trans("ThirdParty"),0,'company');


            // Load object modCodeTiers
            $module=$conf->global->SOCIETE_CODECLIENT_ADDON;
            if (! $module) dolibarr_error('',$langs->trans("ErrorModuleThirdPartyCodeInCompanyModuleNotDefined"));
            if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php')
            {
                $module = substr($module, 0, dol_strlen($module)-4);
            }
            $dirsociete=array_merge(array('/core/modules/societe/'),$conf->societe_modules);
            foreach ($dirsociete as $dirroot)
            {
                $res=dol_include_once($dirroot.$module.".php");
                if ($res) break;
            }
            $modCodeClient = new $module;
            // We verified if the tag prefix is used
            if ($modCodeClient->code_auto)
            {
                $prefixCustomerIsUsed = $modCodeClient->verif_prefixIsUsed();
            }
            $module=$conf->global->SOCIETE_CODEFOURNISSEUR_ADDON;
            if (! $module) $module=$conf->global->SOCIETE_CODECLIENT_ADDON;
            if (substr($module, 0, 15) == 'mod_codeclient_' && substr($module, -3) == 'php')
            {
                $module = substr($module, 0, dol_strlen($module)-4);
            }
            $dirsociete=array_merge(array('/core/modules/societe/'),$conf->societe_modules);
            foreach ($dirsociete as $dirroot)
            {
                $res=dol_include_once($dirroot.$module.".php");
                if ($res) break;
            }
            $modCodeFournisseur = new $module;
            // On verifie si la balise prefix est utilisee
            if ($modCodeFournisseur->code_auto)
            {
                $prefixSupplierIsUsed = $modCodeFournisseur->verif_prefixIsUsed();
            }

            if (! empty($_POST["nom"]))
            {
                // We overwrite with values if posted
                $object->name					= $_POST["nom"];
                $object->prefix_comm			= $_POST["prefix_comm"];
                $object->client					= $_POST["client"];
                $object->code_client			= $_POST["code_client"];
                $object->fournisseur			= $_POST["fournisseur"];
                $object->code_fournisseur		= $_POST["code_fournisseur"];
                $object->address				= $_POST["adresse"];
                $object->zip					= $_POST["zipcode"];
                $object->town					= $_POST["town"];
                $object->country_id				= $_POST["country_id"]?$_POST["country_id"]:$mysoc->country_id;
                $object->state_id				= $_POST["departement_id"];
                $object->tel					= $_POST["tel"];
                $object->fax					= $_POST["fax"];
                $object->email					= $_POST["email"];
                $object->url					= $_POST["url"];
                $object->capital				= $_POST["capital"];
                $object->idprof1				= $_POST["idprof1"];
                $object->idprof2				= $_POST["idprof2"];
                $object->idprof3				= $_POST["idprof3"];
                $object->idprof4				= $_POST["idprof4"];
                $object->typent_id				= $_POST["typent_id"];
                $object->effectif_id			= $_POST["effectif_id"];
                $object->barcode				= $_POST["barcode"];
                $object->forme_juridique_code	= $_POST["forme_juridique_code"];
                $object->default_lang			= $_POST["default_lang"];

                $object->tva_assuj				= $_POST["assujtva_value"];
                $object->tva_intra				= $_POST["tva_intra"];
                $object->status					= $_POST["status"];

                //Local Taxes
                $object->localtax1_assuj		= $_POST["localtax1assuj_value"];
                $object->localtax2_assuj		= $_POST["localtax2assuj_value"];

                // We set country_id, and pays_code label of the chosen country
                // TODO move to DAO class
                if ($object->country_id)
                {
                    $sql = "SELECT code, libelle from ".MAIN_DB_PREFIX."c_pays where code = '".$object->country_id."'";
                    $resql=$db->query($sql);
                    if ($resql)
                    {
                        $obj = $db->fetch_object($resql);
                    }
                    else
                    {
                        dol_print_error($db);
                    }
                    $object->country		= $langs->trans("Country".$object->country_id);
                }
            }

            dol_htmloutput_errors($error,$errors);

            if ($conf->use_javascript_ajax)
            {
                print "\n".'<script type="text/javascript" language="javascript">';
                print '$(document).ready(function () {
                			$("#selectcountry_id").change(function() {
                				document.formsoc.action.value="edit";
                				document.formsoc.submit();
                			});
                       })';
                print '</script>'."\n";
            }

            print '<form enctype="multipart/form-data" action="'.$_SERVER["PHP_SELF"].'?id='.$object->id().'" method="post" name="formsoc">';
            print '<input type="hidden" name="action" value="update">';
            print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
            print '<input type="hidden" name="id" value="'.$object->id().'">';
            if ($modCodeClient->code_auto || $modCodeFournisseur->code_auto) print '<input type="hidden" name="code_auto" value="1">';

            print '<table class="border" width="100%">';

            // Name
            print '<tr><td><span class="fieldrequired">'.$langs->trans('ThirdPartyName').'</span></td><td colspan="3"><input type="text" size="40" maxlength="60" name="nom" value="'.$object->name.'"></td></tr>';

            // Prefix
            if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
            {
                print '<tr><td>'.$langs->trans("Prefix").'</td><td colspan="3">';
                // It does not change the prefix mode using the auto numbering prefix
                if (($prefixCustomerIsUsed || $prefixSupplierIsUsed) && $object->prefix_comm)
                {
                    print '<input type="hidden" name="prefix_comm" value="'.$object->prefix_comm.'">';
                    print $object->prefix_comm;
                }
                else
                {
                    print '<input type="text" size="5" maxlength="5" name="prefix_comm" value="'.$object->prefix_comm.'">';
                }
                print '</td>';
            }

            // Prospect/Customer
            print '<tr><td width="25%"><span class="fieldrequired">'.$langs->trans('ProspectCustomer').'</span></td><td width="25%"><select class="flat" name="client">';
            if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS)) print '<option value="2"'.($object->client==2?' selected="selected"':'').'>'.$langs->trans('Prospect').'</option>';
            if (empty($conf->global->SOCIETE_DISABLE_PROSPECTS)) print '<option value="3"'.($object->client==3?' selected="selected"':'').'>'.$langs->trans('ProspectCustomer').'</option>';
            print '<option value="1"'.($object->client==1?' selected="selected"':'').'>'.$langs->trans('Customer').'</option>';
            print '<option value="0"'.($object->client==0?' selected="selected"':'').'>'.$langs->trans('NorProspectNorCustomer').'</option>';
            print '</select></td>';
            print '<td width="25%">'.$langs->trans('CustomerCode').'</td><td width="25%">';

            print '<table class="nobordernopadding"><tr><td>';
            if ((!$object->code_client || $object->code_client == -1) && $modCodeClient->code_auto)
            {
                $tmpcode=$object->code_client;
                if (empty($tmpcode) && $modCodeClient->code_auto) $tmpcode=$modCodeClient->getNextValue($object,0);
                print '<input type="text" name="code_client" size="16" value="'.$tmpcode.'" maxlength="15">';
            }
            else if ($object->codeclient_modifiable())
            {
                print '<input type="text" name="code_client" size="16" value="'.$object->code_client.'" maxlength="15">';
            }
            else
            {
                print $object->code_client;
                print '<input type="hidden" name="code_client" value="'.$object->code_client.'">';
            }
            print '</td><td>';
            $s=$modCodeClient->getToolTip($langs,$object,0);
            print $form->textwithpicto('',$s,1);
            print '</td></tr></table>';

            print '</td></tr>';

            // Supplier
            if ($conf->fournisseur->enabled && ! empty($user->rights->fournisseur->lire))
            {
                print '<tr>';
                print '<td><span class="fieldrequired">'.$langs->trans('Supplier').'</span></td><td>';
                print $form->selectyesno("fournisseur",$object->fournisseur,1);
                print '</td>';
                print '<td>'.$langs->trans('SupplierCode').'</td><td>';

                print '<table class="nobordernopadding"><tr><td>';
                if ((!$object->code_fournisseur || $object->code_fournisseur == -1) && $modCodeFournisseur->code_auto)
                {
                    $tmpcode=$object->code_fournisseur;
                    if (empty($tmpcode) && $modCodeFournisseur->code_auto) $tmpcode=$modCodeFournisseur->getNextValue($object,1);
                    print '<input type="text" name="code_fournisseur" size="16" value="'.$tmpcode.'" maxlength="15">';
                }
                else if ($object->codefournisseur_modifiable())
                {
                    print '<input type="text" name="code_fournisseur" size="16" value="'.$object->code_fournisseur.'" maxlength="15">';
                }
                else
                {
                    print $object->code_fournisseur;
                    print '<input type="hidden" name="code_fournisseur" value="'.$object->code_fournisseur.'">';
                }
                print '</td><td>';
                $s=$modCodeFournisseur->getToolTip($langs,$object,1);
                print $form->textwithpicto('',$s,1);
                print '</td></tr></table>';

                print '</td></tr>';

                // Category
                if ($conf->categorie->enabled && $object->fournisseur)
                {
                    $load = $object->LoadSupplierCateg();
                    if ( $load == 0)
                    {
                        if (count($object->SupplierCategories) > 0)
                        {
                            print '<tr>';
                            print '<td>'.$langs->trans('SupplierCategory').'</td><td colspan="3">';
                            print $form->selectarray("fournisseur_categorie",$object->SupplierCategories,'',1);
                            print '</td></tr>';
                        }
                    }
                }
            }

            // Barcode
            if ($conf->global->MAIN_MODULE_BARCODE)
            {
                print '<tr><td valign="top">'.$langs->trans('Gencod').'</td><td colspan="3"><input type="text" name="barcode" value="'.$object->barcode.'">';
                print '</td></tr>';
            }

            // Status
            print '<tr><td>'.$langs->trans("Status").'</td><td colspan="3">';
            print $form->selectarray('status', array('0'=>$langs->trans('ActivityCeased'),'1'=>$langs->trans('InActivity')),$object->status);
            print '</td></tr>';

            // Address
            print '<tr><td valign="top">'.$langs->trans('Address').'</td><td colspan="3"><textarea name="adresse" cols="40" rows="3" wrap="soft">';
            print $object->address;
            print '</textarea></td></tr>';

            // Zip / Town
            print '<tr><td>'.$langs->trans('Zip').'</td><td>';
            print $formcompany->select_ziptown($object->zip,'zipcode',array('town','selectcountry_id','departement_id'),6);
            print '</td><td>'.$langs->trans('Town').'</td><td>';
            print $formcompany->select_ziptown($object->town,'town',array('zipcode','selectcountry_id','departement_id'));
            print '</td></tr>';

            // Country
            print '<tr><td>'.$langs->trans('Country').'</td><td colspan="3">';
            print $form->select_country($object->country_id,'country_id');
            if ($user->admin) print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"),1);
            print '</td></tr>';

            // State
            if (empty($conf->global->SOCIETE_DISABLE_STATE))
            {
                print '<tr><td>'.$langs->trans('State').'</td><td colspan="3">';
                print $formcompany->select_state($object->state_id,$object->country_id);
                print '</td></tr>';
            }

            // Phone / Fax
            print '<tr><td>'.$langs->trans('Phone').'</td><td><input type="text" name="tel" value="'.$object->tel.'"></td>';
            print '<td>'.$langs->trans('Fax').'</td><td><input type="text" name="fax" value="'.$object->fax.'"></td></tr>';

            // EMail / Web
            print '<tr><td>'.$langs->trans('EMail').($conf->global->SOCIETE_MAIL_REQUIRED?'*':'').'</td><td><input type="text" name="email" size="32" value="'.$object->email.'"></td>';
            print '<td>'.$langs->trans('Web').'</td><td><input type="text" name="url" size="32" value="'.$object->url.'"></td></tr>';

            // Prof ids
            $i=1; $j=0;
            while ($i <= 6)
            {
                $idprof=$langs->transcountry('ProfId'.$i,$object->country_id);
                if ($idprof!='-')
                {
                    if (($j % 2) == 0) print '<tr>';
                    print '<td>'.$idprof.'</td><td>';
                    $key='idprof'.$i;
                    print $formcompany->get_input_id_prof($i,'idprof'.$i,$object->$key,$object->country_id);
                    print '</td>';
                    if (($j % 2) == 1) print '</tr>';
                    $j++;
                }
                $i++;
            }
            if ($j % 2 == 1) print '<td colspan="2"></td></tr>';

            // VAT payers
            print '<tr><td>'.$langs->trans('VATIsUsed').'</td><td>';
            print $form->selectyesno('assujtva_value',$object->tva_assuj,1);
            print '</td>';

            // VAT Code
            print '<td nowrap="nowrap">'.$langs->trans('VATIntra').'</td>';
            print '<td nowrap="nowrap">';
            $s ='<input type="text" class="flat" name="tva_intra" size="12" maxlength="20" value="'.$object->tva_intra.'">';

            if (empty($conf->global->MAIN_DISABLEVATCHECK))
            {
                $s.=' &nbsp; ';

                if ($conf->use_javascript_ajax)
                {
                    print "\n";
                    print '<script language="JavaScript" type="text/javascript">';
                    print "function CheckVAT(a) {\n";
                    print "newpopup('".DOL_URL_ROOT."/societe/checkvat/checkVatPopup.php?vatNumber='+a,'".dol_escape_js($langs->trans("VATIntraCheckableOnEUSite"))."',500,285);\n";
                    print "}\n";
                    print '</script>';
                    print "\n";
                    $s.='<a href="#" onclick="javascript: CheckVAT(document.formsoc.tva_intra.value);">'.$langs->trans("VATIntraCheck").'</a>';
                    $s = $form->textwithpicto($s,$langs->trans("VATIntraCheckDesc",$langs->trans("VATIntraCheck")),1);
                }
                else
                {
                    $s.='<a href="'.$langs->transcountry("VATIntraCheckURL",$object->id_pays).'" target="_blank">'.img_picto($langs->trans("VATIntraCheckableOnEUSite"),'help').'</a>';
                }
            }
            print $s;
            print '</td>';
            print '</tr>';

            // Local Taxes
            // TODO add specific function by country
            if($mysoc->country_id=='ES')
            {
                if($mysoc->localtax1_assuj=="1" && $mysoc->localtax2_assuj=="1")
                {
                    print '<tr><td>'.$langs->trans("LocalTax1IsUsedES").'</td><td>';
                    print $form->selectyesno('localtax1assuj_value',$object->localtax1_assuj,1);
                    print '</td><td>'.$langs->trans("LocalTax2IsUsedES").'</td><td>';
                    print $form->selectyesno('localtax2assuj_value',$object->localtax2_assuj,1);
                    print '</td></tr>';

                }
                elseif($mysoc->localtax1_assuj=="1")
                {
                    print '<tr><td>'.$langs->trans("LocalTax1IsUsedES").'</td><td colspan="3">';
                    print $form->selectyesno('localtax1assuj_value',$object->localtax1_assuj,1);
                    print '</td></tr>';

                }
                elseif($mysoc->localtax2_assuj=="1")
                {
                    print '<tr><td>'.$langs->trans("LocalTax2IsUsedES").'</td><td colspan="3">';
                    print $form->selectyesno('localtax2assuj_value',$object->localtax2_assuj,1);
                    print '</td></tr>';
                }
            }

            // Type - Size
            print '<tr><td>'.$langs->trans("ThirdPartyType").'</td><td>';
            print $form->selectarray("typent_id",$formcompany->typent_array(1), $object->typent_id);
            if ($user->admin) print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"),1);
            print '</td>';
            print '<td>'.$langs->trans("Staff").'</td><td>';
            print $form->selectarray("effectif_id",$formcompany->effectif_array(1), $object->effectif_id);
            if ($user->admin) print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"),1);
            print '</td></tr>';

            print '<tr><td>'.$langs->trans('JuridicalStatus').'</td><td colspan="3">';
            //print $form->selectarray("forme_juridique_code",$formcompany->forme_juridique_array(1), $object->forme_juridique_code);
            $formcompany->select_forme_juridique($object->forme_juridique_code,$object->country_id);
            if ($user->admin) print info_admin($langs->trans("YouCanChangeValuesForThisListFromDictionnarySetup"),1);
            print '</td></tr>';

            // Capital
            print '<tr><td>'.$langs->trans("Capital").'</td><td colspan="3"><input type="text" name="capital" size="10" value="'.$object->capital.'"> '.$langs->trans("Currency".$conf->currency).'</td></tr>';

            // Default language
            if ($conf->global->MAIN_MULTILANGS)
            {
                print '<tr><td>'.$langs->trans("DefaultLang").'</td><td colspan="3">'."\n";
                print $formadmin->select_language($object->default_lang,'default_lang',0,0,1);
                print '</td>';
                print '</tr>';
            }

            // Other attributes
            $parameters=array('colspan' => ' colspan="3"');
            $reshook=$hookmanager->executeHooks('formObjectOptions',$parameters,$object,$action);    // Note that $action and $object may have been modified by hook
            if (empty($reshook) && ! empty($extrafields->attribute_label))
            {
                foreach($extrafields->attribute_label as $key=>$label)
                {
                    $value=(isset($_POST["options_".$key])?$_POST["options_".$key]:$object->array_options["options_".$key]);
                    print '<tr><td>'.$label.'</td><td colspan="3">';
                    print $extrafields->showInputField($key,$value);
                    print "</td></tr>\n";
                }
            }

            // Logo
            print '<tr>';
            print '<td>'.$langs->trans("Logo").'</td>';
            print '<td colspan="3">';
            if ($object->logo) print $form->showphoto('societe',$object,50);
            $caneditfield=1;
            if ($caneditfield)
            {
                if ($object->logo) print "<br>\n";
                print '<table class="nobordernopadding">';
                if ($object->logo) print '<tr><td><input type="checkbox" class="flat" name="deletephoto" id="photodelete"> '.$langs->trans("Delete").'<br><br></td></tr>';
                //print '<tr><td>'.$langs->trans("PhotoFile").'</td></tr>';
                print '<tr><td><input type="file" class="flat" name="photo" id="photoinput"></td></tr>';
                print '</table>';
            }
            print '</td>';
            print '</tr>';

            print '</table>';
            print '<br>';

            print '<center>';
            print '<input type="submit" class="button" name="save" value="'.$langs->trans("Save").'">';
            print ' &nbsp; &nbsp; ';
            print '<input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'">';
            print '</center>';

            print '</form>';

	        dol_fiche_end();
        }
    }
    else
    {
        /*
         * View
         */
        
        try
        {
            $res=$object->load($socid);
        } catch (Exception $e) {
            $error="Something weird happened: ".$e->getMessage()." (errcode=".$e->getCode().")\n";
            dol_syslog("societe::load ".$error, LOG_ERR);
            print $error;
            exit;
        }
        //if ($res < 0) { dol_print_error($db,$object->error); exit; }
        $res=$object->fetch_optionals($object->id(),$extralabels);
        //if ($res < 0) { dol_print_error($db); exit; }
        
        print '<div class="row">';
        
        $head = societe_prepare_head($object);

        print start_box($langs->trans("ThirdParty"),"eight","16-Apartment-Building.png",$head);
        // First onglet
        
        $address_book = new AddressBook();
        $address_book->address_book = $object->AddressBook;
        print '<article class="tab_pane">';
        print $object->content_box_information($address_book->content($object));
        print $object->content_note();
        print '</article>';
        
        $deal = new Deal();
        $deal->deal = $object->Deal;
        print '<article class="tab_pane">';
        print $object->content_box_information($deal->content($object));
        print $object->content_note();
        print '</article>';
        
        $accounting = new Accounting();
        $accounting->accounting = $object->Accounting;
        print '<article class="tab_pane">';
        print $object->content_box_information($accounting->content($object));
        print $object->content_note();
        print '</article>';
        print end_box();
        
        print start_box($langs->trans("Informations"),"four","16-Info-_-About.png");
        
        print '<ul class="overview_list">
                <li>
                    <a href="#">
                        <img src="'.DOL_URL_ROOT.'/theme/blank.gif" style="background-image: url('.DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/ico/open/dollar.png)" alt="" />
                        <span class="ov_nb">$10201</span>
                        <span class="ov_text">Total sale</span>
                    </a>
		</li>
		<li>
                    <a href="#">
			<img src="'.DOL_URL_ROOT.'/theme/blank.gif" style="background-image: url('.DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/ico/open/tag.png)" alt="" />
			<span class="ov_nb">$231</span>
			<span class="ov_text">Today sale</span>
                    </a>
		</li>
		<li>
                    <a href="#">
                        <img src="'.DOL_URL_ROOT.'/theme/blank.gif" style="background-image: url('.DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/ico/open/happy-face.png)" alt="" />
                        <span class="ov_nb">32</span>
                        <span class="ov_text">New registrations</span>
                    </a>
		</li>
		<li>
                    <a href="#">
			<img src="'.DOL_URL_ROOT.'/theme/blank.gif" style="background-image: url('.DOL_URL_ROOT.'/theme/'.$conf->theme.'/img/ico/open/bar-chart.png)" alt="" />
                        <span class="ov_nb">3021</span>
			<span class="ov_text">Unique visitors</span>
                    </a>
		</li>
            </ul>';
        
        print end_box();
        
        print '</div><div class="row">';
        
        $head = societe_prepare_head($object);

        dol_fiche_head($head, 'card', $langs->trans("ThirdParty"),0,'company');
        
        print start_box($langs->trans("ThirdParty"),"twelve","16-Apartment-Building.png");
        
        // Confirm delete third party
        if ($action == 'delete' || $conf->use_javascript_ajax)
        {
            $form = new Form($db);
            $ret=$form->form_confirm($_SERVER["PHP_SELF"]."?id=".$object->id(),$langs->trans("DeleteACompany"),$langs->trans("ConfirmDeleteCompany"),"confirm_delete",'',0,"action-delete");
            if ($ret == 'html') print '<br>';
        }

        dol_htmloutput_errors($error,$errors);

        $showlogo=$object->logo;
        $showbarcode=($conf->barcode->enabled && $user->rights->barcode->lire);
        
        $var=false;

        print '<table class="noborder" width="100%">';

        // Logo+barcode
        $rowspan=4;
        if (! empty($conf->global->SOCIETE_USEPREFIX)) $rowspan++;
        if ($object->client) $rowspan++;
        if ($conf->fournisseur->enabled && $object->fournisseur && ! empty($user->rights->fournisseur->lire)) $rowspan++;
        if ($conf->global->MAIN_MODULE_BARCODE) $rowspan++;
        if (empty($conf->global->SOCIETE_DISABLE_STATE)) $rowspan++;
        $htmllogobar='';
        if ($showlogo || $showbarcode)
        {
            $htmllogobar.='<td rowspan="'.$rowspan.'" style="text-align: center;" width="25%">';
            if ($showlogo)   $htmllogobar.=$form->showphoto('societe',$object,50);
            if ($showlogo && $showbarcode) $htmllogobar.='<br><br>';
            if ($showbarcode) $htmllogobar.=$form->showbarcode($object,50);
            $htmllogobar.='</td>';
        }

        // Prefix
        if (! empty($conf->global->SOCIETE_USEPREFIX))  // Old not used prefix field
        {
            print '<tr '.$bc[$var].'><td>'.$langs->trans('Prefix').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">'.$object->prefix_comm.'</td>';
            print $htmllogobar; $htmllogobar='';
            print '</tr>';
            $var=!$var;
        }

        // Customer code
        if ($object->client)
        {
            print '<tr '.$bc[$var].'><td>';
            print $langs->trans('CustomerCode').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">';
            print $object->code_client;
            if ($object->check_codeclient() <> 0) print ' <font class="error">('.$langs->trans("WrongCustomerCode").')</font>';
            print '</td>';
            print $htmllogobar; $htmllogobar='';
            print '</tr>';
            $var=!$var;
        }

        // Supplier code
        if ($conf->fournisseur->enabled && $object->fournisseur && ! empty($user->rights->fournisseur->lire))
        {
            print '<tr '.$bc[$var].'><td>';
            print $langs->trans('SupplierCode').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">';
            print $object->code_fournisseur;
            if ($object->check_codefournisseur() <> 0) print ' <font class="error">('.$langs->trans("WrongSupplierCode").')</font>';
            print '</td>';
            print $htmllogobar; $htmllogobar='';
            print '</tr>';
            $var=!$var;
        }

        // Barcode
        if ($conf->global->MAIN_MODULE_BARCODE)
        {
            print '<tr '.$bc[$var].'><td>';
            print $langs->trans('Gencod').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">'.$object->barcode;
            print '</td>';
            print $htmllogobar; $htmllogobar='';
            print '</tr>';
            $var=!$var;
        }

        // State
        if (empty($conf->global->SOCIETE_DISABLE_STATE)) print '<tr><td>'.$langs->trans('State').'</td><td colspan="'.(2+(($showlogo || $showbarcode)?0:1)).'">'.$object->state.'</td>';
        
        print '<tr '.$bc[$var].'><td>'.$langs->trans('Phone').'</td><td style="min-width: 25%;">'.dol_print_phone($object->tel,$object->country_id,0,$object->id(),'AC_TEL').'</td>';
        print '<td>'.$langs->trans('Fax').'</td><td style="min-width: 25%;">'.dol_print_phone($object->fax,$object->country_id,0,$object->id(),'AC_FAX').'</td></tr>';
        $var=!$var;
        
        // EMail
        print '<tr '.$bc[$var].'><td>'.$langs->trans('EMail').'</td><td width="25%">';
        print dol_print_email($object->email,0,$object->id,'AC_EMAIL');
        print '</td>';

        // Prof ids
        $i=1; $j=0;
        while ($i <= 6)
        {
            $idprof=$langs->transcountry('ProfId'.$i,$object->country_id);
            if ($idprof!='-')
            {
                if (($j % 2) == 0) print '<tr '.$bc[$var].'>';
                print '<td>'.$idprof.'</td><td>';
                $key='idprof'.$i;
                print $object->$key;
                if ($object->$key)
                {
                    if ($object->id_prof_check($i,$object) > 0) print ' &nbsp; '.$object->id_prof_url($i,$object);
                    else print ' <font class="error">('.$langs->trans("ErrorWrongValue").')</font>';
                }
                print '</td>';
                if (($j % 2) == 1){ print '</tr>';$var=!$var;}
                $j++;
            }
            $i++;
        }
        if ($j % 2 == 1) { print '<td colspan="2"></td></tr>'; $var=!$var;}

        // VAT payers
        print '<tr '.$bc[$var].'><td>';
        print $langs->trans('VATIsUsed');
        print '</td><td>';
        print yn($object->tva_assuj);
        print '</td>';

        // VAT Code
        print '<td nowrap="nowrap">'.$langs->trans('VATIntra').'</td><td>';
        if ($object->tva_intra)
        {
            $s='';
            $s.=$object->tva_intra;
            $s.='<input type="hidden" id="tva_intra" name="tva_intra" size="12" maxlength="20" value="'.$object->tva_intra.'">';

            if (empty($conf->global->MAIN_DISABLEVATCHECK))
            {
                $s.=' &nbsp; ';

                if ($conf->use_javascript_ajax)
                {
                    print "\n";
                    print '<script language="JavaScript" type="text/javascript">';
                    print "function CheckVAT(a) {\n";
                    print "newpopup('".DOL_URL_ROOT."/societe/checkvat/checkVatPopup.php?vatNumber='+a,'".dol_escape_js($langs->trans("VATIntraCheckableOnEUSite"))."',500,285);\n";
                    print "}\n";
                    print '</script>';
                    print "\n";
                    $s.='<a href="#" onclick="javascript: CheckVAT( $(\'#tva_intra\').val() );">'.$langs->trans("VATIntraCheck").'</a>';
                    $s = $form->textwithpicto($s,$langs->trans("VATIntraCheckDesc",$langs->trans("VATIntraCheck")),1);
                }
                else
                {
                    $s.='<a href="'.$langs->transcountry("VATIntraCheckURL",$object->id_pays).'" target="_blank">'.img_picto($langs->trans("VATIntraCheckableOnEUSite"),'help').'</a>';
                }
            }
            print $s;
        }
        else
        {
            print '&nbsp;';
        }
        print '</td>';
        print '</tr>';
        $var=!$var;

        // Local Taxes
        // TODO add specific function by country
        if($mysoc->country_id=='ES')
        {
            if($mysoc->localtax1_assuj=="1" && $mysoc->localtax2_assuj=="1")
            {
                print '<tr '.$bc[$var].'><td>'.$langs->trans("LocalTax1IsUsedES").'</td><td>';
                print yn($object->localtax1_assuj);
                print '</td><td>'.$langs->trans("LocalTax2IsUsedES").'</td><td>';
                print yn($object->localtax2_assuj);
                print '</td></tr>';

            }
            elseif($mysoc->localtax1_assuj== 1)
            {
                print '<tr '.$bc[$var].'><td>'.$langs->trans("LocalTax1IsUsedES").'</td><td colspan="3">';
                print yn($object->localtax1_assuj);
                print '</td><tr>';
            }
            elseif($mysoc->localtax2_assuj== 1)
            {
                print '<tr '.$bc[$var].'><td>'.$langs->trans("LocalTax2IsUsedES").'</td><td colspan="3">';
                print yn($object->localtax2_assuj);
                print '</td><tr>';
            }
            $var=!$var;
        }

        // Type + Staff
        $arr = $formcompany->typent_array(1);
        $typent= $arr[$object->typent_id];
        print '<tr '.$bc[$var].'><td>'.$langs->trans("ThirdPartyType").'</td><td>'.$typent.'</td><td>'.$langs->trans("Staff").'</td><td>'.$object->effectif_id.'</td></tr>';
        $var=!$var;
        
        // Legal
        print '<tr '.$bc[$var].'><td>'.$langs->trans('JuridicalStatus').'</td><td colspan="3">'.$object->forme_juridique.'</td></tr>';
        $var=!$var;

        // Capital
        print '<tr '.$bc[$var].'><td>'.$langs->trans('Capital').'</td><td colspan="3">';
        if ($object->capital) print $object->capital.' '.$langs->trans("Currency".$conf->currency);
        else print '&nbsp;';
        print '</td></tr>';
        $var=!$var;

        // Default language
        if ($conf->global->MAIN_MULTILANGS)
        {
            require_once(DOL_DOCUMENT_ROOT."/core/lib/functions2.lib.php");
            print '<tr '.$bc[$var].'><td>'.$langs->trans("DefaultLang").'</td><td colspan="3">';
            //$s=picto_from_langcode($object->default_lang);
            //print ($s?$s.' ':'');
            $langs->load("languages");
            $labellang = ($object->default_lang?$langs->trans('Language_'.$object->default_lang):'');
            print $labellang;
            print '</td></tr>';
            $var=!$var;
        }

        // Other attributes
        $parameters=array('socid'=>$socid, 'colspan' => ' colspan="3"');
        $reshook=$hookmanager->executeHooks('formObjectOptions',$parameters,$object,$action);    // Note that $action and $object may have been modified by hook
        if (empty($reshook) && ! empty($extrafields->attribute_label))
        {
            foreach($extrafields->attribute_label as $key=>$label)
            {
                $value=(isset($_POST["options_".$key])?$_POST["options_".$key]:$object->array_options["options_".$key]);
                print '<tr '.$bc[$var].'><td>'.$label.'</td><td colspan="3">';
                print $extrafields->showOutputField($key,$value);
                print "</td></tr>\n";
                $var=!$var;
            }
        }

        // Ban
        if (empty($conf->global->SOCIETE_DISABLE_BANKACCOUNT))
        {
            print '<tr '.$bc[$var].'><td>';
            print '<table width="100%" class="nobordernopadding"><tr><td>';
            print $langs->trans('RIB');
            print '<td><td align="right">';
            if ($user->rights->societe->creer)
            print '<a href="'.DOL_URL_ROOT.'/societe/rib.php?id='.$object->id().'">'.img_edit().'</a>';
            else
            print '&nbsp;';
            print '</td></tr></table>';
            print '</td>';
            print '<td colspan="3">';
            print $object->display_rib();
            print '</td></tr>';
            $var=!$var;
        }

        // Parent company
        if (empty($conf->global->SOCIETE_DISABLE_PARENTCOMPANY))
        {
            print '<tr '.$bc[$var].'><td>';
            print '<table width="100%" class="nobordernopadding"><tr><td>';
            print $langs->trans('ParentCompany');
            print '<td><td align="right">';
            if ($user->rights->societe->creer)
            print '<a href="'.DOL_URL_ROOT.'/societe/lien.php?id='.$object->id().'">'.img_edit() .'</a>';
            else
            print '&nbsp;';
            print '</td></tr></table>';
            print '</td>';
            print '<td colspan="3">';
            if ($object->parent)
            {
                $socm->fetch($object->parent);
                print $socm->getNomUrl(1).' '.($socm->code_client?"(".$socm->code_client.")":"");
                print $socm->town?' - '.$socm->town:'';
            }
            else {
                print $langs->trans("NoParentCompany");
            }
            print '</td></tr>';
            $var=!$var;
        }

        // Commercial
        print '<tr '.$bc[$var].'><td>';
        print '<table width="100%" class="nobordernopadding"><tr><td>';
        print $langs->trans('SalesRepresentatives');
        print '<td><td align="right">';
        if ($user->rights->societe->creer)
        print '<a href="'.DOL_URL_ROOT.'/societe/commerciaux.php?id='.$object->id().'">'.img_edit().'</a>';
        else
        print '&nbsp;';
        print '</td></tr></table>';
        print '</td>';
        print '<td colspan="3">';

        $listsalesrepresentatives=$object->getSalesRepresentatives($user);
        $nbofsalesrepresentative=count($listsalesrepresentatives);
        if ($nbofsalesrepresentative > 3)   // We print only number
        {
            print '<a href="'.DOL_URL_ROOT.'/societe/commerciaux.php?id='.$object->id().'">';
            print $nbofsalesrepresentative;
            print '</a>';
        }
        else if ($nbofsalesrepresentative > 0)
        {
            $userstatic=new User($db);
            $i=0;
            foreach($listsalesrepresentatives as $val)
            {
                $userstatic->id=$val['id'];
                $userstatic->lastname=$val['name'];
                $userstatic->firstname=$val['firstname'];
                print $userstatic->getNomUrl(1);
                $i++;
                if ($i < $nbofsalesrepresentative) print ', ';
            }
        }
        else print $langs->trans("NoSalesRepresentativeAffected");
        print '</td></tr>';
        $var=!$var;

        // Module Adherent
        if ($conf->adherent->enabled)
        {
            $langs->load("members");
            print '<tr '.$bc[$var].'><td width="25%" valign="top">'.$langs->trans("LinkedToDolibarrMember").'</td>';
            print '<td colspan="3">';
            $adh=new Adherent($db);
            $result=$adh->fetch('','',$object->id());
            if ($result > 0)
            {
                $adh->ref=$adh->getFullName($langs);
                print $adh->getNomUrl(1);
            }
            else
            {
                print $langs->trans("UserNotLinkedToMember");
            }
            print '</td>';
            print "</tr>\n";
            $var=!$var;
        }

        print '</table>';

        dol_fiche_end();


        /*
         *  Actions
         */

        print '<br>';

        if ($conf->ecm->enabled && empty($conf->global->SOCIETE_DISABLE_BUILDDOC))
        {
            print '<table width="100%"><tr><td valign="top" width="50%">';
            print '<a name="builddoc"></a>'; // ancre

            /*
             * Documents generes
             */
            $filedir=$conf->societe->multidir_output[$object->entity].'/'.$object->id();
            $urlsource=$_SERVER["PHP_SELF"]."?id=".$object->id();
            $genallowed=$user->rights->societe->creer;
            $delallowed=$user->rights->societe->supprimer;

            $var=true;

            $somethingshown=$formfile->show_documents('company',$object->id(),$filedir,$urlsource,$genallowed,$delallowed,'',0,0,0,28,0,'',0,'',$object->default_lang);

            print '</td>';
            print '<td></td>';
            print '</tr>';
            print '</table>';

            print '<br>';
        }

        // Subsidiaries list
        $result=show_subsidiaries($conf,$langs,$db,$object);

        // Contacts list
        if (empty($conf->global->SOCIETE_DISABLE_CONTACTS))
        {
            $result=show_contacts($conf,$langs,$db,$object,$_SERVER["PHP_SELF"].'?id='.$object->id());
        }

        // Addresses list
        if (! empty($conf->global->SOCIETE_ADDRESSES_MANAGEMENT))
        {
        	$result=show_addresses($conf,$langs,$db,$object,$_SERVER["PHP_SELF"].'?id='.$object->id());
        }

        // Projects list
        $result=show_projects($conf,$langs,$db,$object,$_SERVER["PHP_SELF"].'?id='.$object->id());
        
        print end_box();
    }

}

print '</div>'; // end row

// End of page
llxFooter();
$db->close();
?>