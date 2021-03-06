<?php

use App\Http\Controllers\CapabilitiesController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProvenanceController;
use App\Http\Controllers\AllergyIntoleranceController;
use App\Http\Controllers\CarePlanController;
use App\Http\Controllers\CareTeamController;
use App\Http\Controllers\ConditionController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DiagnosticReportController;
use App\Http\Controllers\DocumentReferenceController;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\FHIRValueSetController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ImmunizationController;
use App\Http\Controllers\MedicationRequestController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PractitionerController;
use App\Http\Controllers\ObservationController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\SnomedCTController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\ValueSetController;
use App\Http\Controllers\InfoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
Route::get('patient/recursive', [PatientController::class, 'recursive']);
Route::get('patient/pagination', [PatientController::class, 'pagination']);
Route::get('patient/getmodified', [PatientController::class, 'getmodified']);
Route::get('/{tenantID}/Patient/tenancy/{patientID}',[PatientController::class, 'tenancytest']);
Route::get('Patient/search', [PatientController::class, 'search']);
*/
//Route::resource('{tenantID}/Patient', PatientController::class);
Route::resource('Patient', PatientController::class);
Route::resource('Provenance', ProvenanceController::class);
Route::resource('AllergyIntolerance', AllergyIntoleranceController::class);
Route::resource('CarePlan', CarePlanController::class);
Route::resource('CareTeam', CareTeamController::class);
Route::resource('Condition', ConditionController::class);
Route::resource('Device', DeviceController::class);
Route::resource('DiagnosticReport', DiagnosticReportController::class);
Route::resource('DocumentReference', DocumentReferenceController::class);
Route::resource('Goal', GoalController::class);
Route::resource('Immunization', ImmunizationController::class);
Route::resource('Encounter', EncounterController::class);
Route::resource('MedicationRequest', MedicationRequestController::class);
Route::resource('Medication', MedicationController::class);
Route::resource('Organization', OrganizationController::class);
Route::resource('Observation', ObservationController::class);
Route::resource('Location', LocationController::class);
Route::resource('Practitioner', PractitionerController::class);
Route::resource('Procedure', ProcedureController::class);
Route::resource('Group', GroupController::class);


Route::get('capabilities', [CapabilitiesController::class, 'index']);
Route::get('capabilitiesaws', [CapabilitiesController::class, 'aws']);
Route::get('explore', [CapabilitiesController::class, 'exploreClass']);

Route::get('auth', [AuthController::class, 'auth']);
Route::get('denyauth', [AuthController::class, 'deny']);
Route::get('authorize', [AuthController::class, 'authorization']);
Route::get('denyauthorize', [AuthController::class, 'denyauthorization']);


Route::get('fhirds', [TestController::class, 'datasource']);

Route::get('FHIRValueSet/methods',[FHIRValueSetController::class, 'getMethods']);
Route::get('FHIRValueSet/{ValueSet}/{Term?}/{Sort?}',[FHIRValueSetController::class, 'getValueSet']);
Route::get('SnomedCT/{ConceptGroup}/{Term?}/{Sort?}',[SnomedCTController::class, 'query']);
Route::get('ValueSet/{ValueSet}/{Term?}/{Sort?}',[ValueSetController::class, 'getValueSet']);

Route::get('getinfo/{Concept}',[InfoController::class, 'getinfo']);
Route::get('parseCCDA',[InfoController::class, 'parseCCDA']);
