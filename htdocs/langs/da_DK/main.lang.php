<?php
/* Copyright (C) 2012	Regis Houssin	<regis@dolibarr.fr>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

$main = array(
		'CHARSET' => 'UTF-8',
		'SeparatorDecimal' => '.',
		'SeparatorThousand' => ',',
		'FormatDateShort' => '%d/%m/%Y',
		'FormatDateShortJava' => 'dd/MM/yyyy',
		'FormatHourShort' => '%H:%M',
		'FormatDateTextShort' => '%d %b %Y',
		'FormatDateText' => '%d %B %Y',
		'FormatDateHourShort' => '%d/%m/%Y %H:%M',
		'FormatDateHourTextShort' => '%d %b %Y %H:%M',
		'FormatDateHourText' => '%d %B %Y %H:%M',
		'DatabaseConnection' => 'Database forbindelse',
		'Error' => 'Fejl',
		'ErrorFieldRequired' => 'Felt \' %s\' er påkrævet',
		'ErrorFieldFormat' => 'Felt \' %s\' har en dårlig værdi',
		'ErrorFileDoesNotExists' => 'Fil %s ikke eksisterer',
		'ErrorFailedToOpenFile' => 'Kunne ikke åbne filen %s',
		'ErrorCanNotCreateDir' => 'Kan ikke oprette dir %s',
		'ErrorCanNotReadDir' => 'Ikke kan læse dir %s',
		'ErrorConstantNotDefined' => 'Parameter %s ikke defineret',
		'ErrorUnknown' => 'Ukendt fejl',
		'ErrorSQL' => 'SQL Fejl',
		'ErrorLogoFileNotFound' => 'Logo fil \' %s\' blev ikke fundet',
		'ErrorGoToModuleSetup' => 'Gå til Modul setup at rette dette',
		'ErrorFailedToSendMail' => 'Failed to send mail (sender=%s, receiver=Det lykkedes ikke at sende e-mails (afsender= %s, receiver= %s)',
		'ErrorAttachedFilesDisabled' => 'Vedhæftning af filer funktionen er slået fra på denne serveur',
		'ErrorFileNotUploaded' => 'Filen blev ikke uploadet. Kontroller, at størrelse ikke overstiger den maksimalt tilladte, at den frie plads der er til rådighed på disken, og at der ikke allerede en fil med samme navn i denne mappe.',
		'ErrorInternalErrorDetected' => 'Fejl opdaget',
		'ErrorNoRequestRan' => 'Ingen anmodning løb',
		'ErrorWrongHostParameter' => 'Forkert vært parameter',
		'ErrorYourCountryIsNotDefined' => 'Deres land er ikke defineret. Gå til Forside-Setup-Edit og post igen form.',
		'ErrorRecordIsUsedByChild' => 'Det lykkedes ikke at slette denne rekord. Denne registrering anvendes af mindst på barnet registre.',
		'ErrorWrongValue' => 'Forkert værdi',
		'ErrorWrongValueForParameterX' => 'Forkert værdi for parameter %s',
		'ErrorNoRequestInError' => 'Ingen anmodning ved en fejl',
		'ErrorServiceUnavailableTryLater' => 'Tjenesten er ikke tilgængelig for øjeblikket. Prøv igen senere.',
		'ErrorDuplicateField' => 'Dobbelt værdi i et unikt område',
		'ErrorSomeErrorWereFoundRollbackIsDone' => 'Nogle fejl blev fundet. Vi rollback ændringer.',
		'ErrorConfigParameterNotDefined' => '<b>Parameter %s</b> er ikke defineret inde Dolibarr konfigurationsfil <b>conf.php.</b>',
		'ErrorCantLoadUserFromDolibarrDatabase' => 'Kunne ikke finde <b>bruger %s</b> i Dolibarr database.',
		'ErrorNoVATRateDefinedForSellerCountry' => 'Fejl, der ikke momssatser defineret for land \' %s\'.',
		'ErrorFailedToSaveFile' => 'Fejl, kunne ikke gemme filen.',
		'ErrorOnlyPngJpgSupported' => 'Fejl, kun. Png og. Jpg billedformat filen understøttes.',
		'ErrorImageFormatNotSupported' => 'Din PHP understøtter ikke funktioner til at konvertere billeder af dette format.',
		'RecordSaved' => 'Optag gemt',
		'LevelOfFeature' => 'Niveau funktionsliste',
		'NotDefined' => 'Ikke defineret',
		'DefinedAndHasThisValue' => 'Defineret og værdi for',
		'IsNotDefined' => 'undefined',
		'DolibarrInHttpAuthenticationSoPasswordUseless' => 'Dolibarr autentificering tilstand er opsætningen <b>til %s</b> i opsætningsfilen <b>conf.php.</b> <br> Det betyder, at password database er eksterne til Dolibarr, så ændrer dette område kan ikke have nogen effekt.',
		'Administrator' => 'Administrator',
		'Undefined' => 'Undefined',
		'PasswordForgotten' => 'Glemt password?',
		'SeeAbove' => 'Se ovenfor',
		'HomeArea' => 'Hjem område',
		'LastConnexion' => 'Seneste forbindelse',
		'PreviousConnexion' => 'Forrige forbindelse',
		'ConnectedSince' => 'Connected siden',
		'AuthenticationMode' => 'Autentificerings mode',
		'RequestedUrl' => 'Anmodede webadresse',
		'DatabaseTypeManager' => 'Database type manager',
		'RequestLastAccess' => 'Anmodning om sidste database adgang',
		'RequestLastAccessInError' => 'Anmodning om sidste database adgang ved en fejl',
		'ReturnCodeLastAccessInError' => 'Retur kode for sidste database adgang ved en fejl',
		'InformationLastAccessInError' => 'Oplysninger til sidste database adgang ved en fejl',
		'DolibarrHasDetectedError' => 'Dolibarr har opdaget en teknisk fejl',
		'InformationToHelpDiagnose' => 'Det er oplysninger, der kan bidrage til at diagnosticere',
		'MoreInformation' => 'Mere information',
		'NotePublic' => 'Note (offentlige)',
		'NotePrivate' => 'Note (privat)',
		'PrecisionUnitIsLimitedToXDecimals' => 'Dolibarr blev setup at begrænse præcision på enhedspriser <b>til %s</b> decimaler.',
		'DoTest' => 'Test',
		'ToFilter' => 'Filter',
		'WarningYouHaveAtLeastOneTaskLate' => 'Advarsel, du har mindst ét element, der har oversteget den tolerance forsinkelse.',
		'yes' => 'ja',
		'Yes' => 'Ja',
		'no' => 'nej',
		'No' => 'Nej',
		'All' => 'Alle',
		'Home' => 'Hjem',
		'Help' => 'Hjælp',
		'Always' => 'Altid',
		'Never' => 'Aldrig',
		'Under' => 'under',
		'Period' => 'Periode',
		'Activate' => 'Aktivér',
		'Activated' => 'Aktiveret',
		'Closed' => 'Lukket',
		'Closed2' => 'Lukket',
		'Enabled' => 'Aktiveret',
		'Disable' => 'Deaktivere',
		'Disabled' => 'Deaktiveret',
		'Add' => 'Tilføj',
		'Update' => 'Opdatering',
		'AddActionToDo' => 'Tilføj en indsats for at gøre',
		'AddActionDone' => 'Tilføj indsats gøres',
		'Close' => 'Luk',
		'Close2' => 'Luk',
		'Confirm' => 'Bekræft',
		'ConfirmSendCardByMail' => 'Har du virkelig ønsker at sende dette kort med posten?',
		'Delete' => 'Slet',
		'Remove' => 'Fjerne',
		'Resiliate' => 'Resiliate',
		'Cancel' => 'Annuller',
		'Modify' => 'Modify',
		'Edit' => 'Redigér',
		'Validate' => 'Validate',
		'ToValidate' => 'At validere',
		'Save' => 'Gemme',
		'SaveAs' => 'Gem som',
		'TestConnection' => 'Test forbindelse',
		'ToClone' => 'Klon',
		'CloneEMailing' => 'Klon E-mail',
		'CloneContent' => 'Klon besked',
		'CloneReceivers' => 'Cloner modtagere',
		'ConfirmClone' => 'Vælg de data, du vil klone:',
		'ConfirmCloneEMailing' => 'Er du sikker på at du vil klone denne e-mail?',
		'NoCloneOptionsSpecified' => 'Ingen data at klone defineret.',
		'CopyOf' => 'Kopi af',
		'Show' => 'Vise',
		'Search' => 'Søgning',
		'Valid' => 'Gyldig',
		'Approve' => 'Godkend',
		'ReOpen' => 'Re-Open',
		'Upload' => 'Send fil',
		'Select' => 'Vælg',
		'Choose' => 'Vælge',
		'ChooseLangage' => 'Vælg dit sprog',
		'Author' => 'Forfatter',
		'User' => 'Bruger',
		'Users' => 'Brugere',
		'Group' => 'Gruppen',
		'Groups' => 'Grupper',
		'Password' => 'Password',
		'PasswordRetype' => 'Gentag dit password',
		'Name' => 'Navn',
		'Parameter' => 'Parameter',
		'Parameters' => 'Parametre',
		'Value' => 'Værdi',
		'GlobalValue' => 'Global værdi',
		'PersonalValue' => 'Personlige værdi',
		'NewValue' => 'Ny værdi',
		'CurrentValue' => 'Nuværende værdi',
		'Code' => 'Kode',
		'Type' => 'Type',
		'Language' => 'Sprog',
		'MultiLanguage' => 'Multi-sprog',
		'Note' => 'Note',
		'CurrentNote' => 'Aktuel note',
		'Title' => 'Titel',
		'Label' => 'Label',
		'RefOrLabel' => 'Ref. eller etiket',
		'Info' => 'Log',
		'Family' => 'Familie',
		'Description' => 'Beskrivelse',
		'Designation' => 'Beskrivelse',
		'Model' => 'Model',
		'DefaultModel' => 'Standard model',
		'Action' => 'Action',
		'About' => 'Om',
		'Number' => 'Antal',
		'Numero' => 'Numero',
		'Limit' => 'Limit',
		'Limits' => 'Grænseværdier',
		'DevelopmentTeam' => 'Development Team',
		'Logout' => 'Logout',
		'Connection' => 'Forbindelsesstyring',
		'Setup' => 'Setup',
		'Alert' => 'Alert',
		'Previous' => 'Forrige',
		'Next' => 'Næste',
		'Cards' => 'Postkort',
		'Card' => 'Card',
		'Now' => 'Nu',
		'Date' => 'Dato',
		'DateStart' => 'Dato start',
		'DateEnd' => 'Dato udgangen',
		'DateCreation' => 'Lavet dato',
		'DateModification' => 'Ændringsdatoen',
		'DateLastModification' => 'Seneste ændringsdato',
		'DateValidation' => 'Validering dato',
		'DateClosing' => 'Udløbsdato',
		'DateDue' => 'Forfaldsdag',
		'DateValue' => 'Valørdato',
		'DateValueShort' => 'Valørdato',
		'DateOperation' => 'Operation dato',
		'DateOperationShort' => 'OPE. Dato',
		'DateLimit' => 'Limit dato',
		'DateRequest' => 'Anmodning dato',
		'DateProcess' => 'Proces dato',
		'DatePlanShort' => 'Dato høvlet',
		'DateRealShort' => 'Dato reel.',
		'DurationYear' => 'år',
		'DurationMonth' => 'måned',
		'DurationDay' => 'dag',
		'DurationYears' => 'år',
		'DurationMonths' => 'måneder',
		'DurationDays' => 'dage',
		'Year' => 'År',
		'Month' => 'Måned',
		'Week' => 'Uge',
		'Day' => 'Dag',
		'Hour' => 'Time',
		'Minute' => 'Minut',
		'Second' => 'Anden',
		'Years' => 'År',
		'Months' => 'Måneder',
		'Days' => 'Dage',
		'days' => 'dage',
		'Hours' => 'Timer',
		'Minutes' => 'Minutter',
		'Seconds' => 'Sekunder',
		'Today' => 'I dag',
		'Yesterday' => 'I går',
		'Tomorrow' => 'I morgen',
		'Quadri' => 'Quadri',
		'MonthOfDay' => 'Måned fra den dato',
		'HourShort' => 'H',
		'Rate' => 'Hyppighed',
		'Bytes' => 'Bytes',
		'Cut' => 'Skære',
		'Copy' => 'Kopier',
		'Paste' => 'Paste',
		'Default' => 'Standard',
		'DefaultValue' => 'Standardværdi',
		'DefaultGlobalValue' => 'Global værdi',
		'Price' => 'Pris',
		'UnitPrice' => 'Enhedspris',
		'UnitPriceHT' => 'Enhedspris (netto)',
		'UnitPriceTTC' => 'Enhedspris',
		'PriceU' => 'UP',
		'PriceUHT' => 'UP (netto)',
		'PriceUTTC' => 'UP',
		'Amount' => 'Mængde',
		'AmountInvoice' => 'Fakturabeløbet',
		'AmountPayment' => 'Indbetalingsbeløb',
		'AmountHT' => 'Beløb (efter skat)',
		'AmountTTC' => 'Beløb (inkl. moms)',
		'AmountVAT' => 'Beløb moms',
		'AmountTotal' => 'Samlet beløb',
		'AmountAverage' => 'Gennemsnitligt beløb',
		'PriceQtyHT' => 'Prisen for denne mængde (efter skat)',
		'PriceQtyMinHT' => 'Pris mængde min. (efter skat)',
		'PriceQtyTTC' => 'Pris for denne mængde (inkl. moms)',
		'PriceQtyMinTTC' => 'Pris mængde min. (inkl. afgift)',
		'Percentage' => 'Pourcentage',
		'Total' => 'Total',
		'SubTotal' => 'Tilsammen',
		'TotalHT' => 'Total (efter skat)',
		'TotalTTC' => 'I alt (inkl. moms)',
		'TotalTTCToYourCredit' => 'I alt (inkl. moms) til dit kredit',
		'TotalVAT' => 'Total moms',
		'IncludedVAT' => 'Inkluderet moms',
		'HT' => 'Efter skat',
		'TTC' => 'Inc. moms',
		'VAT' => 'Moms',
		'VATRate' => 'Momssats',
		'Average' => 'Gennemsnit',
		'Sum' => 'Sum',
		'Delta' => 'Delta',
		'Module' => 'Modul',
		'Option' => 'Option',
		'List' => 'Liste',
		'FullList' => 'Fuldstændig liste',
		'Statistics' => 'Statistik',
		'Status' => 'Status',
		'Ref' => 'Ref.',
		'RefSupplier' => 'Ref. leverandør',
		'RefPayment' => 'Ref. betaling',
		'CommercialProposals' => 'Kommerciel forslag',
		'Comment' => 'Kommentar',
		'Comments' => 'Kommentarer',
		'ActionsToDo' => 'Tiltag for at gøre',
		'ActionsDone' => 'Handlinger gøres',
		'ActionsToDoShort' => 'At gøre',
		'ActionsRunningshort' => 'Started',
		'ActionsDoneShort' => 'Gjort',
		'ActionRunningNotStarted' => 'Ikke startet',
		'ActionRunningShort' => 'Started',
		'ActionDoneShort' => 'Finished',
		'ContactsForCompany' => 'Kontakter til denne tredjepart',
		'ActionsOnCompany' => 'Aktioner om denne tredjepart',
		'NActions' => '%s aktioner',
		'NActionsLate' => '%s sent',
		'Filter' => 'Filter',
		'RemoveFilter' => 'Fjern filter',
		'ChartGenerated' => 'Chart genereret',
		'ChartNotGenerated' => 'Chart ikke genereret',
		'GeneratedOn' => 'Bygge videre på %s',
		'Generate' => 'Generer',
		'Duration' => 'Varighed',
		'TotalDuration' => 'Samlet varighed',
		'Summary' => 'Resumé',
		'MyBookmarks' => 'Mine bogmærker',
		'OtherInformationsBoxes' => 'Andre oplysninger kasser',
		'DolibarrBoard' => 'Dolibarr bord',
		'DolibarrStateBoard' => 'Statistik',
		'DolibarrWorkBoard' => 'Arbejdsopgaver bord',
		'NotYetAvailable' => 'Endnu ikke tilgængelig',
		'NotAvailable' => 'Ikke til rådighed',
		'Popularity' => 'Popularitet',
		'Categories' => 'Kategorier',
		'Category' => 'Kategori',
		'By' => 'Ved',
		'From' => 'Fra',
		'to' => 'til',
		'and' => 'og',
		'or' => 'eller',
		'Other' => 'Anden',
		'Others' => 'Andre',
		'Quantity' => 'Mængde',
		'Qty' => 'Qty',
		'ChangedBy' => 'Ændret ved',
		'ReCalculate' => 'Genopbygge',
		'ResultOk' => 'Succes',
		'ResultKo' => 'Fejl',
		'Reporting' => 'Rapportering',
		'Reportings' => 'Rapportering',
		'Draft' => 'Udkast',
		'Drafts' => 'Drafts',
		'Validated' => 'Valideret',
		'Opened' => 'Åbnet',
		'New' => 'Ny',
		'Discount' => 'Discount',
		'Unknown' => 'Ukendt',
		'General' => 'Almindelige',
		'Size' => 'Størrelse',
		'Received' => 'Modtaget',
		'Paid' => 'Betales',
		'Topic' => 'SUJET',
		'ByCompanies' => 'Af selskaber',
		'ByUsers' => 'Af brugerne',
		'Links' => 'Links',
		'Link' => 'Link',
		'Receipts' => 'Receipts',
		'Rejects' => 'Afviser',
		'Preview' => 'Preview',
		'NextStep' => 'Næste skridt',
		'PreviousStep' => 'Forrige trin',
		'Datas' => 'Oplysningerne',
		'None' => 'Ingen',
		'Late' => 'Sen',
		'Photo' => 'Billede',
		'Photos' => 'Billeder',
		'AddPhoto' => 'Tilføj billede',
		'CurrentLogin' => 'Nuværende login',
		'January' => 'Januar',
		'February' => 'Februar',
		'March' => 'Marts',
		'April' => 'April',
		'May' => 'Maj',
		'June' => 'Juni',
		'July' => 'Juli',
		'August' => 'August',
		'September' => 'September',
		'October' => 'Oktober',
		'November' => 'November',
		'December' => 'December',
		'AttachedFiles' => 'Vedhæftede filer og dokumenter',
		'DateFormatYYYYMM' => 'ÅÅÅÅ-MM',
		'DateFormatYYYYMMDD' => 'ÅÅÅÅ-MM-DD',
		'DateFormatYYYYMMDDHHMM' => 'ÅÅÅÅ-MM-DD HH: SS',
		'ReportName' => 'Rapportnavn',
		'ReportPeriod' => 'Beretningsperioden',
		'ReportDescription' => 'Beskrivelse',
		'Report' => 'Rapport',
		'Keyword' => 'Mot cl',
		'Legend' => 'Legend',
		'FillTownFromZip' => 'Udfyld byen fra zip',
		'ShowLog' => 'Vis log',
		'File' => 'Fil',
		'Files' => 'Files',
		'NotAllowed' => 'Ikke tilladt',
		'ReadPermissionNotAllowed' => 'Læs tilladelse ikke tilladt',
		'AmountInCurrency' => 'Beløb i %s valuta',
		'Example' => 'Eksempel',
		'NoExample' => 'Nr. eksempel',
		'FindBug' => 'Fejlmeld',
		'NbOfThirdParties' => 'Antallet af tredjemand',
		'NbOfCustomers' => 'Antal kunder',
		'NbOfLines' => 'Antal linjer',
		'NbOfObjects' => 'Antallet af objekter',
		'NbOfReferers' => 'Antal referers',
		'Referers' => 'Referers',
		'TotalQuantity' => 'Samlet mængde',
		'DateFromTo' => 'Fra %s til %s',
		'DateFrom' => 'Fra %s',
		'DateUntil' => 'Indtil %s',
		'Check' => 'Kontrollere',
		'Internal' => 'Intern',
		'External' => 'Eksterne',
		'Internals' => 'Intern',
		'Externals' => 'Eksterne',
		'Warning' => 'Advarsel',
		'Warnings' => 'Advarsler',
		'BuildPDF' => 'Build PDF',
		'RebuildPDF' => 'Genopbygge PDF',
		'BuildDoc' => 'Build Dok',
		'RebuildDoc' => 'Genopbygge Dok',
		'Entity' => 'Enhed',
		'Entities' => 'Enheder',
		'EventLogs' => 'Logs',
		'CustomerPreview' => 'Customer Preview',
		'SupplierPreview' => 'Leverandør preview',
		'AccountancyPreview' => 'Regnskabsmæssig preview',
		'ShowCustomerPreview' => 'Vis kunden preview',
		'ShowSupplierPreview' => 'Vis leverandør preview',
		'ShowAccountancyPreview' => 'Vis regnskabspool preview',
		'RefCustomer' => 'Ref. kunde',
		'Currency' => 'Valuta',
		'InfoAdmin' => 'Oplysninger til administratorer',
		'Undo' => 'Fortryd',
		'Redo' => 'Redo',
		'ExpandAll' => 'Udvid alle',
		'UndoExpandAll' => 'Fortryd udvide',
		'FeatureNotYetSupported' => 'Feature endnu ikke understøttet',
		'CloseWindow' => 'Luk vindue',
		'Question' => 'Spørgsmål',
		'Response' => 'Response',
		'Priority' => 'Prioritet',
		'MailSentBy' => 'E-mail sendt fra',
		'TextUsedInTheMessageBody' => 'Email organ',
		'SendAcknowledgementByMail' => 'Send Ack. via e-mail',
		'NoEMail' => 'Ingen e-mail',
		'Owner' => 'Ejer',
		'DetectedVersion' => 'Registreret version',
		'FollowingConstantsWillBeSubstituted' => 'Efter konstanterne skal erstatte med tilsvarende værdi.',
		'Refresh' => 'Opdatér',
		'BackToList' => 'Tilbage til listen',
		'GoBack' => 'Gå tilbage',
		'CanBeModifiedIfOk' => 'Kan ændres, hvis det er gyldigt',
		'CanBeModifiedIfKo' => 'Kan ændres, hvis ikke gyldigt',
		'RecordModifiedSuccessfully' => 'Optag modificerede held',
		'AutomaticCode' => 'Automatisk kode',
		'NotManaged' => 'Ikke lykkedes',
		'FeatureDisabled' => 'Feature handicappede',
		'MoveBox' => 'Flyt boksen %s',
		'Offered' => 'Fri',
		'NotEnoughPermissions' => 'Du har ikke tilladelse til denne handling',
		'SessionName' => 'Session navn',
		'Method' => 'Metode',
		'Receive' => 'Modtag',
		'PartialWoman' => 'Delvis',
		'PartialMan' => 'Delvis',
		'TotalWoman' => 'Total',
		'TotalMan' => 'Total',
		'NeverReceived' => 'Aldrig modtaget',
		'Canceled' => 'Annulleret',
		'YouCanChangeValuesForThisListFromDictionnarySetup' => 'Du kan ændre værdier for denne liste fra menuen setup - dictionnary',
		'Color' => 'Color',
		'Documents' => 'Forbundet filer',
		'Documents2' => 'Dokumenter',
		'BuildDocuments' => 'Genereret dokumenter',
		'UploadDisabled' => 'Upload handicappede',
		'MenuECM' => 'Dokumenter',
		'MenuAWStats' => 'AWStats',
		'MenuMembers' => 'Medlemmer',
		'MenuAgendaGoogle' => 'Google dagsorden',
		'ThisLimitIsDefinedInSetup' => 'Dolibarr grænse (Menu hjemme-setup-sikkerhed): %s Kb, PHP grænse: %s Kb',
		'NoFileFound' => 'Ingen dokumenter gemmes i denne mappe',
		'CurrentUserLanguage' => 'Valgt sprog',
		'CurrentTheme' => 'Nuværende tema',
		'DisabledModules' => 'Handikappede moduler',
		'For' => 'For',
		'ForCustomer' => 'For kunden',
		'Signature' => 'Underskrift',
		'HidePassword' => 'Vis kommandoen med adgangskode skjulte',
		'UnHidePassword' => 'Vis reelle kommandoen med klare adgangskode',
		'Root' => 'Rot',
		'Monday' => 'Mandag',
		'Tuesday' => 'Tirsdag',
		'Wednesday' => 'Onsdag',
		'Thursday' => 'Torsdag',
		'Friday' => 'Fredag',
		'Saturday' => 'Lørdag',
		'Sunday' => 'Søndag',
		'ShortMonday' => 'M',
		'ShortTuesday' => 'T',
		'ShortWednesday' => 'W',
		'ShortThursday' => 'T',
		'ShortFriday' => 'F',
		'ShortSaturday' => 'S',
		'ShortSunday' => 'S',
		'DIRECTION' => 'ltr',
		'GoToWikiHelpPage' => 'Læs online hjælp (har brug for Internet-adgang)',
		'GoToHelpPage' => 'Læs hjælpe',
		'ConnectedOnMultiCompany' => 'Connected på enhed',
		'OnlineHelp' => 'Online hjælp',
		'PageWiki' => 'Wiki side',
		'PeriodEndDate' => 'Slutdato for perioden',
		'ShowCardHere' => 'Vis kort',
		'NoteSomeFeaturesAreDisabled' => 'Bemærk, at en masse funktioner / moduler er slået fra i denne demonstration.',
		'Person' => 'Person',
		'DateModificationShort' => 'Modif. dato',
		'DateBuild' => 'Rapport bygge dato',
		'DurationWeek' => 'uge',
		'DurationWeeks' => 'uger',
		'AmountHTShort' => 'Beløb (netto)',
		'AmountTTCShort' => 'Beløb (inkl. moms)',
		'TotalHTShort' => 'I alt (netto)',
		'TotalTTCShort' => 'I alt (inkl. moms)',
		'CompanyFoundation' => 'Company / Foundation',
		'OtherInformations' => 'Andre informationer',
		'NoneF' => 'Ingen',
		'Login' => 'Login',
		'Month01' => 'januar',
		'Month02' => 'februar',
		'Month03' => 'marts',
		'Month04' => 'april',
		'Month05' => 'må',
		'Month06' => 'juni',
		'Month07' => 'juli',
		'Month08' => 'august',
		'Month09' => 'september',
		'Month10' => 'oktober',
		'Month11' => 'november',
		'Month12' => 'december',
		'MonthShort01' => 'jan',
		'MonthShort02' => 'feb',
		'MonthShort03' => 'marts',
		'MonthShort04' => 'apr',
		'MonthShort05' => 'må',
		'MonthShort06' => 'jun',
		'MonthShort07' => 'jul',
		'MonthShort08' => 'aug',
		'MonthShort09' => 'Sep',
		'MonthShort10' => 'oktober',
		'MonthShort11' => 'nov',
		'MonthShort12' => 'dec',
		'FileTransferComplete' => 'Fil blev uploadet successfuly',
		'ShowProspectPreview' => 'Vis udsigt preview',
		'Informations' => 'Informations',
		'Page' => 'Page',
		'Notes' => 'Noter',
		'AddNewLine' => 'Tilføj ny linje',
		'AddFile' => 'Tilføj fil',
		'ListOfFiles' => 'Listen med tilgængelige filer',
		'FreeZone' => 'Fri tekst',
		'CloneMainAttributes' => 'Klon formål med sine vigtigste attributter',
		'PDFMerge' => 'PDF Sammenflet',
		'Merge' => 'Merge',
		'Day1' => 'Mandag',
		'Day2' => 'Tirsdag',
		'Day3' => 'Onsdag',
		'Day4' => 'Torsdag',
		'Day5' => 'Fredag',
		'Day6' => 'Lørdag',
		'Day0' => 'Søndag',
		'ErrorGoToGlobalSetup' => 'Gå til \'Company / Foundation "-opsætningen til at løse dette',
		'FormatHourShortDuration' => '%H:%M',
		'NoError' => 'Ingen fejl',
		'ErrorNoSocialContributionForSellerCountry' => 'Fejl, ingen social bidrag type der er defineret for landets %s \'.',
		'BackgroundColorByDefault' => 'Standard baggrundsfarve',
		'FileWasNotUploaded' => 'En fil er valgt for udlæg, men endnu ikke var uploadet. Klik på "Vedhæft fil" for dette.',
		'NbOfEntries' => 'Nb af tilmeldinger',
		'Resize' => 'Resize',
		'Recenter' => 'Recenter',
		'NumberByMonth' => 'Antal efter måned',
		'KiloBytes' => 'Kilobyte',
		'MegaBytes' => 'Megabyte',
		'GigaBytes' => 'Gigabyte',
		'b' => 'b.',
		'Kb' => 'Kb',
		'Mb' => 'Mb',
		'Gb' => 'Gb',
		'AmountLT1ES' => 'Beløb RE',
		'AmountLT2ES' => 'Beløb IRPF',
		'TotalLT1ES' => 'Total RE',
		'TotalLT2ES' => 'Total IRPF',
		'Available' => 'Tilgængelig',
		'Examples' => 'Eksempler',
		'Reason' => 'Årsag',
		'SendByMail' => 'Send via e-mail',
		'DocumentsNb' => 'Linkede filer (%s)',
		'PrintContentArea' => 'Vis side for at udskrive hovedindhold område',
		'NoMenu' => 'Ingen sub-menu',
		'WarningYouAreInMaintenanceMode' => 'Advarsel, du er i en vedligeholdelses mode, så kun login <b>%s</b> er tilladt at bruge ansøgningen på i øjeblikket.',
		'CreditCard' => 'Kreditkort',
		'FieldsWithAreMandatory' => 'Felter med <b>%s</b> er obligatoriske',
		'FieldsWithIsForPublic' => 'Felter med <b>%s</b> er vist på offentlig liste over medlemmer. Hvis du ikke ønsker dette, se "offentlige" boks.',
		'AccordingToGeoIPDatabase' => '(Ifølge GeoIP konvertering)',
		'Line' => 'Line',
		'NotSupported' => 'Ikke understøttet',
		'RequiredField' => 'Obligatorisk felt',
		'Result' => 'Resultat',
		'ToTest' => 'Test',
		'ValidateBefore' => 'Kortet skal være valideret, før du bruger denne funktion',
		'Visibility' => 'Synlighed',
		'Private' => 'Private',
		'Hidden' => 'Skjulte',
		'Resources' => 'Ressourcer',
		'Source' => 'Kilde',
		'Prefix' => 'Præfiks',
		'FormatDateShortJQuery' => 'dd/mm/yy',
		'AddLink' => 'Tilføj link',
		'Of' => 'af',
		'SearchOf' => 'Søg',
		'AmountByMonth' => 'Beløb efter måned',
		'DatePayment' => 'Dato for betaling',
		'TeraBytes' => 'Terabyte',
		'Tb' => 'Tb',
		'LT1ES' => 'RE',
		'LT2ES' => 'IRPF',
		'OtherStatistics' => 'Andre statistik',
		'ActionNotApplicable' => 'Ikke relevant',
		'ActionsOnMember' => 'Events Om dette medlem',
		'JanuaryMin' => 'Jan',
		'FebruaryMin' => 'Februar',
		'MarchMin' => 'Mar',
		'AprilMin' => 'April',
		'MayMin' => 'Maj',
		'JuneMin' => 'Juni',
		'JulyMin' => 'Juli',
		'AugustMin' => 'August',
		'SeptemberMin' => 'September',
		'OctoberMin' => 'Oktober',
		'NovemberMin' => 'November',
		'DecemberMin' => 'December',
		'CoreErrorTitle' => 'Systemfejl',
		'CoreErrorMessage' => 'Beklager, der opstod en fejl. Kontroller logs, eller kontakt din systemadministrator.',
		'Before' => 'Før',
		'After' => 'Efter',
		'IPAddress' => 'IP-adressen',
		'Frequency' => 'Frekvens',
		'IM' => 'Instant messaging',
		'NewAttribute' => 'Ny attribut',
		'AttributeCode' => 'Attribut koden',
		'OptionalFieldsSetup' => 'Ekstra attributter opsætning',
		'URLPhoto' => 'Url af foto / logo',
		'SetLinkToThirdParty' => 'Link til en anden tredjepart',
		'CreateDraft' => 'Opret udkast',
		'ClickToEdit' => 'Klik for at redigere',
		'ObjectDeleted' => 'Objekt %s slettet',
		'ByCountry' => 'Efter land',
		'ByTown' => 'Ved byen',
		'ByDate' => 'Efter dato',
		'ByMonthYear' => 'Efter måned / år',
		'ByYear' => 'Ved år',
		'ByMonth' => 'efter måned',
		'ByDay' => 'Ved dag',
		'BySalesRepresentative' => 'Ved salgsrepræsentant',
		'MondayMin' => 'Mo',
		'TuesdayMin' => 'Tu',
		'WednesdayMin' => 'Vi',
		'ThursdayMin' => 'Th',
		'FridayMin' => 'Fr',
		'SaturdayMin' => 'Sa',
		'SundayMin' => 'Su',
);
?>