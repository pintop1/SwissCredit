<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Globals as Utils;
use Hash;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailerCustomer;
use Auth;
use Excel;
use App\Exports\CustomerExport;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Folder;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $user = Utils::getUser();
        if($user->role == 'account officer'){
            $activityCount = count(Utils::getActivities($user));
            $commentCount = count(Utils::getComments($user));
            $customerCount = count(Utils::getCustomers($user));
            $folderCount = count(Utils::getFolders($user));
            $teamCount = count(Utils::getTeam($user));
            $ctasks = Utils::getCreatedTasks($user);
            return view('home', ['user'=>$user, 'activityCount'=>$activityCount, 'commentCount'=>$commentCount, 'customerCount'=>$customerCount, 'folderCount'=>$folderCount, 'teamCount'=>$teamCount, 'ctasks'=>$ctasks]);
        }else {
            $activityCount = count(Utils::getActivities($user));
            $commentCount = count(Utils::getComments($user));
            $customerCount = count(Utils::getAllCustomers());
            $folderCount = count(Utils::getAllFolders());
            $teamCount = count(Utils::getTeam($user));
            $ctasks = Utils::getCreatedTasks($user);
            return view('home', ['user'=>$user, 'activityCount'=>$activityCount, 'commentCount'=>$commentCount, 'customerCount'=>$customerCount, 'folderCount'=>$folderCount, 'teamCount'=>$teamCount, 'ctasks'=>$ctasks]);
        }
    }

    public function team()
    {
        $user = Utils::getUser();
        $teams = User::where('role', '!=', 'super admin')->orderBy('id', 'desc')->get();
        return view('team', ['user'=>$user, 'teams'=>$teams]);
    }

    public function viewComments($id)
    {
        $form = Utils::getForm($id);
        $user = Utils::getUser();
        $comments = DB::table('comments')->where('doc', $id)->orderBy('id', 'asc')->get();
        return view('comments', ['user'=>$user, 'comments'=>$comments, 'form'=>$form]);
    }

    public function comment(Request $req){
        $user = Utils::getUser();
        if($req->msg != null){
            DB::table('comments')->insert([
               'staff'=>$user->email,
               'comments'=>$req->msg,
               'doc'=>$req->id,
               'created_at'=>Carbon::now(),
               'updated_at'=>Carbon::now(),
            ]);
            return redirect()->back();
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> The input field is required!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function tasks()
    {
        $user = Utils::getUser();
        $tasks = Utils::getTasks($user);
        $ctasks = Utils::getCreatedTasks($user);
        return view('tasks', ['user'=>$user, 'tasks'=>$tasks, 'ctasks'=>$ctasks]);
    }

    public function fileManager()
    {
        $user = Utils::getUser();
        $folders = DB::table('folders')->where([['status', null],['parent', null]])->orderBy('id', 'desc')->paginate(50);
        return view('folders', ['user'=>$user, 'folders'=>$folders]);
    }

    public function viewFolder($slug)
    {
        $user = Utils::getUser();
        $folder = DB::table('folders')->where('slug', $slug)->where('status', null)->first();
        $permission = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'approved'],['internal_control', 'approved'],['folder', $folder->id],['status', 'not used']])->first();
        $pending = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'pending'],['folder', $folder->id],['status', 'not used']])->orWhere([['staff', $user->email],['internal_control', 'pending'],['folder', $folder->id],['status', 'not used']])->first();
        Utils::createActivity($user->email, 'You viewed a folder', '<i class="lni-folder bg-soft-primary"></i>', 'You viewed a customer folder with the name <b><q>'.$folder->name.'</q></b>');
        $subs = collect(DB::table('folders')->where('parent', $folder->id)->where('status', null)->orderBy('id', 'desc')->get());
        $uploads = collect(DB::table('uploads')->where('folder', $folder->id)->where('status', null)->orderBy('id', 'desc')->get());
        $contents = Utils::dpaginate($subs->merge($uploads), 50);
        $links = DB::table('links')->where('folder', $folder->id)->where('status', null)->orderBy('id', 'asc')->get();
        return view('viewFolder', ['user'=>$user, 'folder'=>$folder, 'contents'=>$contents, 'links'=>$links, 'permission'=>$permission, 'pending'=>$pending]);
    }

    public function recycleBin()
    {
        $user = Utils::getUser();
        $subs = collect(DB::table('folders')->where('status', 'deleted')->orderBy('id', 'desc')->get());
        $uploads = collect(DB::table('uploads')->where('status', 'deleted')->orderBy('id', 'desc')->get());
        $contents = Utils::dpaginate($subs->merge($uploads), 50);
        $links = DB::table('links')->where('status', 'deleted')->count();
        $posts = DB::table('links')->where('status', 'deleted')->orderBy('id','desc')->get();
        return view('bin', ['user'=>$user, 'contents'=>$contents, 'links'=>$links]);
    }

    public function addFolder($slug = null)
    {
        $user = Utils::getUser();
        return view('addFolder', ['user'=>$user, 'slug'=>$slug]);
    }

    public function addFolderPost(Request $req)
    {
        $user = Utils::getUser();
        if($req->slug != ''){
            $folder = DB::table('folders')->where('slug', $req->slug)->first();
            $permission = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'approved'],['internal_control', 'approved'],['folder', $folder->id],['status', 'not used']])->first();
            if($folder->file != null){
                $form = Utils::getForm($folder->file);
                if($form->status == 'approved' && $permission == null){
                    return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> You no longer has access to write to this folder!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    if($user->role != 'credit risk'){
                        return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Only User with the role of credit risk can create a folder!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                    }else {
                        $folders = DB::table('folders')->where(['name'=>$req->name, 'parent'=>$folder->id])->count();
                        if($folders > 0){
                            Utils::createActivity($user->email, 'You attempted to add a folder', '<i class="lni-folder bg-soft-danger"></i>', 'You tried creating a folder with the name <b><q>'.$req->name.'</q></b> but the folder already exixsts');
                            return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Folder already exists in this directory!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                        }else {
                            Folder::create([
                                'name'=>$req->name,
                                'parent'=>$folder->id,
                                'staff'=>$user->email,
                            ]);
                            Utils::createActivity($user->email, 'You created a folder', '<i class="lni-folder bg-soft-success"></i>', 'You created a folder with the name <b><q>'.$req->name.'</q></b> successfully.');
                            return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your folder has been created.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                        }
                    }
                }
            }else {
                if($user->role != 'credit risk'){
                    return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Only User with the role of credit risk can create a folder!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    $folders = DB::table('folders')->where(['name'=>$req->name, 'parent'=>$folder->id])->count();
                    if($folders > 0){
                        Utils::createActivity($user->email, 'You attempted to add a folder', '<i class="lni-folder bg-soft-danger"></i>', 'You tried creating a folder with the name <b><q>'.$req->name.'</q></b> but the folder already exixsts');
                        return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Folder already exists in this directory!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                    }else {
                        Folder::create([
                            'name'=>$req->name,
                            'parent'=>$folder->id,
                            'staff'=>$user->email,
                        ]);
                        Utils::createActivity($user->email, 'You created a folder', '<i class="lni-folder bg-soft-success"></i>', 'You created a folder with the name <b><q>'.$req->name.'</q></b> successfully.');
                        return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your folder has been created.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                    }
                }
            }

        }else {
            if($user->role != 'credit risk'){
                return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Only User with the role of credit risk can create a folder!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                $folders = DB::table('folders')->where(['name'=>$req->name, 'parent'=>null])->count();
                if($folders > 0){
                    Utils::createActivity($user->email, 'You attempted to add a folder', '<i class="lni-folder bg-soft-danger"></i>', 'You tried creating a folder with the name <b><q>'.$req->name.'</q></b> but the folder already exixsts');
                    return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> Folder already exists in this directory!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    Folder::create([
                        'name'=>$req->name,
                        'parent'=>null,
                        'staff'=>$user->email,
                    ]);
                    Utils::createActivity($user->email, 'You created a folder', '<i class="lni-folder bg-soft-success"></i>', 'You created a folder with the name <b><q>'.$req->name.'</q></b> successfully.');
                    return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your folder has been created.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }
        }
    }

    public function addFiles($slug)
    {
        $user = Utils::getUser();
        return view('addFiles', ['user'=>$user, 'slug'=>$slug]);
    }

    public function addLinks($slug)
    {
        $user = Utils::getUser();
        return view('addLinks', ['user'=>$user, 'slug'=>$slug]);
    }

    public function addFilesPost(Request $req){
        $user = Utils::getUser();
        $folder = DB::table('folders')->where('slug', $req->slug)->first();
        $errors = 0;
        $permission = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'approved'],['internal_control', 'approved'],['folder', $folder->id],['status', 'not used']])->first();
        if($folder->file != null){
            $form = Utils::getForm($folder->file);
            $error = false;
            $errorLog = "";
            if($user->role == 'credit risk'){
                if($form->credit_risk == 'approved' && $permission == null){
                    $error = true;
                    $errorLog = "This file has passed the credit risk stage and can no longer be write to unless you request for permission!";
                }else {
                    $error = false;
                    $errorLog = "";
                }
            }else if($user->role == 'underwriter'){
                if($form->underwriters == 'approved' && $permission == null){
                    $error = true;
                    $errorLog = "This file has passed the underwriter stage and can no longer be write to unless you request for permission!";
                }else {
                    $error = false;
                    $errorLog = "";
                }
            }else if($user->role == 'risk'){
                if($form->risk == 'approved' && $permission == null){
                    $error = true;
                    $errorLog = "This file has passed the risk stage and can no longer be write to unless you request for permission!";
                }else {
                    $error = false;
                    $errorLog = "";
                }
            }
            if($error){
                return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> '.$errorLog.'</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                if ($files = $req->file('uploads')) {
                    foreach($files as $img) {
                        $destinationPath = 'uploads/';
                        $dimagename =$img->getClientOriginalName();
                        $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                        $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                        $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                        $counts = DB::table('uploads')->where(['name'=>$filename, 'folder'=>$folder->id])->count();
                        if($counts > 0){
                            $errors += 1;
                        }else {
                            $img->move($destinationPath, $dfile);
                            DB::table('uploads')->insert([
                                'file'=>$destinationPath.''.$dfile,
                                'name'=>$filename,
                                'staff'=>$user->email,
                                'folder'=>$folder->id,
                                'created_at'=>Carbon::now(),
                                'updated_at'=>Carbon::now()
                            ]);
                        }
                    }
                    if($errors > 0){
                        Utils::createActivity($user->email, 'You attempted to add a file', '<i class="fas fa-file bg-soft-warning"></i>', 'You tried adding a file a file to a folder but some of the files already exists within the same directory.');
                        return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-warning b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Warning!</strong> '.$errors.' of your files could not be upload because it already exists in this folder!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                    }else {
                        Utils::createActivity($user->email, 'You added a file', '<i class="fas fa-file bg-soft-success"></i>', 'You just added a file to a folder with the file name <b><q>'.$filename.'</q></b> successfully.');
                        return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> All your files has been uploaded.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                    }

                }
            }
        }else {
            if ($files = $req->file('uploads')) {
                foreach($files as $img) {
                    $destinationPath = 'uploads/';
                    $dimagename =$img->getClientOriginalName();
                    $filename = pathinfo($dimagename, PATHINFO_FILENAME);
                    $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
                    $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
                    $counts = DB::table('uploads')->where(['name'=>$filename, 'folder'=>$folder->id])->count();
                    if($counts > 0){
                        $errors += 1;
                    }else {
                        $img->move($destinationPath, $dfile);
                        DB::table('uploads')->insert([
                            'file'=>$destinationPath.''.$dfile,
                            'name'=>$filename,
                            'staff'=>$user->email,
                            'folder'=>$folder->id,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now()
                        ]);
                    }
                }
                if($errors > 0){
                    Utils::createActivity($user->email, 'You attempted to add a file', '<i class="fas fa-file bg-soft-warning"></i>', 'You tried adding a file a file to a folder but some of the files already exists within the same directory.');
                    return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-warning b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Warning!</strong> '.$errors.' of your files could not be upload because it already exists in this folder!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    Utils::createActivity($user->email, 'You added a file', '<i class="fas fa-file bg-soft-success"></i>', 'You just added a file to a folder with the file name <b><q>'.$filename.'</q></b> successfully.');
                    return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> All your files has been uploaded.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }

            }
        }


    }

    public function addLinksPost(Request $req){
        $user = Utils::getUser();
        $folder = DB::table('folders')->where('slug', $req->slug)->first();
        $permission = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'approved'],['internal_control', 'approved'],['folder', $folder->id],['status', 'not used']])->first();
        if($folder->file != null){
            $form = Utils::getForm($folder->file);
            $error = false;
            $errorLog = "";
            if($user->role == 'credit risk'){
                if($form->credit_risk == 'approved' && $permission == null){
                    $error = true;
                    $errorLog = "This file has passed the credit risk stage and can no longer be write to unless you request for permission!";
                }else {
                    $error = false;
                    $errorLog = "";
                }
            }else if($user->role == 'underwriter'){
                if($form->underwriters == 'approved' && $permission == null){
                    $error = true;
                    $errorLog = "This file has passed the underwriter stage and can no longer be write to unless you request for permission!";
                }else {
                    $error = false;
                    $errorLog = "";
                }
            }else if($user->role == 'risk'){
                if($form->risk == 'approved' && $permission == null){
                    $error = true;
                    $errorLog = "This file has passed the risk stage and can no longer be write to unless you request for permission!";
                }else {
                    $error = false;
                    $errorLog = "";
                }
            }
            if($error){
                return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> '.$errorLog.'</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                DB::table('links')->insert([
                    'title'=>$req->title,
                    'folder'=>$folder->id,
                    'link'=>$req->link,
                    'staff'=>$user->email,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                Utils::createActivity($user->email, 'You added a link', '<i class="fas fa-file bg-soft-success"></i>', 'You just added a link to a folder with the title name <b><q>'.$req->title.'</q></b> successfully.');
                return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your link has been uploaded.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }else {
            DB::table('links')->insert([
                'title'=>$req->title,
                'folder'=>$folder->id,
                'link'=>$req->link,
                'staff'=>$user->email,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            Utils::createActivity($user->email, 'You added a link', '<i class="fas fa-file bg-soft-success"></i>', 'You just added a link to a folder with the title name <b><q>'.$req->title.'</q></b> successfully.');
            return redirect($req->prev)->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your link has been uploaded.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function previewFile($id){
        $user = Utils::getUser();
        $dfile = DB::table('uploads')->where('id', $id)->first();
        $header = Utils::previewer($dfile->file);
        Utils::createActivity($user->email, 'You previewed an uploaded file', '<i class="fas fa-file bg-soft-primary"></i>', 'You just previewed a file with the name <b><q>'.$dfile->name.'</q></b> successfully.');
        if($header == 'nil') {
            return redirect(asset($dfile->file));
        }else {
            return view('previewer', ['file'=>$header]);
        }


    }

    public function bvnVerify()
    {
        $user = Utils::getUser();
        return view('bvn', ['user'=>$user]);
    }

    public function bvnVerifyPost(Request $req)
    {
        $user = Utils::getUser();
        $return = Utils::checkBvn($req->bvn);
        if($return->status){
            return view('bvn', ['user'=>$user, 'bvnDetails'=>$return]);
            Utils::createActivity($user->email, 'You verified customer\'s bvn', '<i class="dripicons dripicons-checkmark bg-soft-success"></i>', 'You verified a Bank verification number <b><q>'.$req->bvn.'</q><b> successfully.');
        }else {
            Utils::createActivity($user->email, 'You tried verifying a BVN', '<i class="fas fa-file bg-soft-success"></i>', 'You tried verifying a bank verification Number but failed with the error <b><q>'.$return->message.'</q></b> successfully.');
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> '.$return->message.' </div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function addStaff(){
        $user = Utils::getUser();
        if($user->role != 'it' && $user->role != 'super admin'){
            return view('errors.401');
        }else
        return view('addStaff', ['user'=>$user]);
    }

    public function addStaffPost(Request $req){
        $user = Utils::getUser();
        $req->validate([
            'email' => 'required|unique:users',
            'staff_id' => 'required|unique:users',
        ]);
        User::create([
            'name'=>$req->name,
            'email'=>$req->email,
            'password'=>Hash::make('swissData2020'),
            'role'=>$req->role,
            'staff_id'=>$req->staff_id,
            'team'=>$req->team,
            'lead'=>$req->lead,
        ]);
        Utils::createActivity($user->email, 'You added a staff', '<i class="lni-users bg-soft-success"></i>', 'You added a staff with the ID <b><q>'.$req->staff_id.'</q></b> to the system with the role <b><q>'.$req->role.'</q></b>.');
        $uniqid = uniqid().mt_rand(000000,999999);
        Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Account creation', 'Congratulations!!! you just got added to the system. Please click here to update your profile', $req->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/profile'), 'bg-primary', $uniqid);
        return redirect('/staffs')->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your staff has been created successfully.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }
    public function addTask(){
        $user = Utils::getUser();
        $users = DB::table('users')->orderBy('id', 'desc')->get();
        if($user->role != 'director' && $user->role != 'internal control' && $user->role != 'super admin'){
            return view('errors.401');
        }else
        return view('addTask', ['user'=>$user, 'users'=>$users]);
    }

    public function addTaskPost(Request $req){
        $user = Utils::getUser();
        DB::table('tasks')->insert([
            'title'=>$req->title,
            'status'=>'0',
            'message'=>$req->message,
            'users'=>json_encode($req->users),
            'due_time'=>date('Y-m-d h:i:s', strtotime('+ '.$req->due_time.' hours')),
            'staff'=>$user->email,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        Utils::createActivity($user->email, 'You added a task', '<i class="dripicons dripicons-to-do bg-soft-success"></i>', 'You added a new task with the title <b><q>'.$req->title.'</q></b> with '.count($req->users).' staff attached.');
        foreach($req->users as $duse){
            $uniqid = uniqid().mt_rand(000000,999999);
            Utils::createNotification('<i class="dripicons dripicons-to-do text-dark"></i>', 'New Task', 'Hello there, a new task has been created for you.', $duse, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/tasks'), 'bg-warning', $uniqid);
        }
        return redirect('/tasks')->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your task has been created successfully.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function finishTask($id){
        $user = Utils::getUser();
        $task = DB::table('tasks')->where('id', $id)->first();
        $isPart = Utils::userPartTask($user, $task->id);
        $isDone = Utils::getTaskDone($user, $task->id);
        $newP = (100/count(json_decode($task->users))) + $task->status;
        if(!$isPart){
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> This task was not assigned to you!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else if($isDone){
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> You have already completed this task!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            DB::table('task_dones')->insert([
                'user'=>$user->email,
                'task'=>$task->id,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ]);
            Utils::createActivity($user->email, 'You completed a task', '<i class="dripicons dripicons-to-do bg-soft-warning"></i>', 'You completed your portion of a task with title <b><q>'.$task->title.'</q></b> successfully.');
            DB::table('tasks')->where('id', $id)->update(['status'=>$newP]);
            $uniqid = uniqid().mt_rand(000000,999999);
            Utils::createNotification('<i class="dripicons dripicons-to-do text-white"></i>', 'Task Completed', ucwords($user->name).' just completed his portion of a task.', $task->staff, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/tasks/'.$task->id.'/view'), 'bg-primary', $uniqid);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have completed your task.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function deleteTask($id){
        $user = Utils::getUser();
        $task = DB::table('tasks')->where('id', $id)->first();
        if($task->staff == $user->email){
            DB::table('task_dones')->where('task', $id)->delete();
            DB::table('tasks')->where('id', $id)->delete();
            Utils::createActivity($user->email, 'You deleted a task', '<i class="dripicons dripicons-to-do bg-soft-pink"></i>', 'You just deleted a task with the title <b><q>'.$task->title.'</q></b> successfully.');

            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have deleted a task.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');

        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oops!</strong> You can only delete a task you created!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function viewTask($id){
        $user = Utils::getUser();
        $task = DB::table('tasks')->where('id', $id)->first();
        Utils::createActivity($user->email, 'You viewed a task', '<i class="dripicons dripicons-to-do bg-soft-purple"></i>', 'You viewed a task with the title <b><q>'.$task->title.'</q></b> successfully.');
        return view('viewTask', ['user'=>$user, 'task'=>$task]);
    }

    public function customers(Request $req){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'gender', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'pension_number', 'tid', 'salary'];
            $posts = DB::table('customers')->where([['type', 'new'],['status', null]])->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('customers', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::table('customers')->where([['type', 'new'],['status', null]])->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('customers', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function nysc(Request $req){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'gender', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'pension_number', 'tid', 'salary'];
            $posts = DB::table('customers')->where([['type', 'nysc'],['status', null]])->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('nysc', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::table('customers')->where([['type', 'nysc'],['status', null]])->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('nysc', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function renewals(Request $req){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'gender', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'pension_number', 'tid', 'salary'];
            $posts = DB::table('customers')->where([['type', 'Renewal'],['status', null]])->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('renewals', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::table('customers')->where([['type', 'Renewal'],['status', null]])->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('renewals', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function topups(Request $req){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'gender', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'pension_number', 'tid', 'salary'];
            $posts = DB::table('customers')->where([['type', 'Top-up'],['status', null]])->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('topups', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::table('customers')->where([['type', 'Top-up'],['status', null]])->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('topups', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function declineLoan($id){
        $user = Utils::getUser();
        $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
        $ret = '<div class="modal fade inmodal" id="modalLoginForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog" role="document"><form action="'.route('reason').'" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="id" value="'.$id.'"><div class="modal-content"><div class="modal-body mx-3"><div class="md-form mb-5"><div class="form-group"><label>Reason For declining this file?</label><textarea class="form-control" name="reason"></textarea></div></div></div><div class="modal-footer d-flex justify-content-center"><button class="btn btn-default">Submit</button></div></div></form></div></div>';
        $ret .= "<script>$(function(){ swal.fire({ title: 'Are you sure?', text: 'This file will be moved to declined file', type: 'warning', showCancelButton: true, confirmButtonText: 'Yes, decline file!', cancelButtonText: 'No, cancel!', reverseButtons: true }).then((result) => { if (result.value) { $('.inmodal').modal('show'); } else if (result.dismiss === Swal.DismissReason.cancel) { swal.fire('Cancelled', 'Your action has been cancelled', 'error'); } }); });</script>";
        echo $ret;
    }

    public function approveLoan($id){
        $user = Utils::getUser();
        $customer = DB::table('customers')->where('id', $id)->first();
        $folder = DB::table('folders')->where('id', $customer->folder)->first();
        $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
        if($folder != null){
            $ret = '<div class="modal fade inmodal"><div class="modal-dialog" role="document"><form action="'.route('checklist').'" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="id" value="'.$id.'"><div class="modal-content"><div class="modal-header text-center"><h4 class="modal-title w-100 font-weight-bold">CHECKLIST</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body mx-3"><div class="md-form mb-5">';
            foreach($checklists as $checklist){
                $ret .= '<div class="form-group"><h6 class="title">'.$checklist->name.'</h6><div class="d-inline-block"><label>Yes</label><input type="radio" name="checklist_'.$checklist->id.'" value="yes"';
                if(Utils::getChecklist($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div><div class="d-inline-block ml-5"><label>No</label><input type="radio" name="checklist_{{ $checklist->id }}" value="no"';
                if(Utils::getChecklistNo($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div></div>';
            }
            $ret .= '</div></div><div class="modal-footer d-flex justify-content-center"><button class="btn btn-default">Proceed</button></div></div></form></div></div>';
            $ret .= "<script>$(function(){ swal.fire({ title: 'Are you sure?', text: 'You won\'t be able to revert this!', type: 'warning', showCancelButton: true, confirmButtonText: 'Yes, approve file!', cancelButtonText: 'No, cancel!', reverseButtons: true }).then((result) => { if (result.value) { $('.inmodal').modal('show'); } else if (result.dismiss === Swal.DismissReason.cancel) { swal.fire('Cancelled', 'Your action has been cancelled', 'error'); } }); });</script>";
            echo $ret;
        }else {
            $folders = DB::table('folders')->where('file', null)->orderBy('name', 'asc')->get();
            $ret = '<div class="modal fade inmodal"><div class="modal-dialog" role="document"><form action="'.route('checklist').'" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="id" value="'.$id.'"><div class="modal-content"><div class="modal-header text-center"><h4 class="modal-title w-100 font-weight-bold">CHECKLIST</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body mx-3"><div class="md-form mb-5">';
            foreach($checklists as $checklist){
                $ret .= '<div class="form-group"><h6 class="title">'.$checklist->name.'</h6><div class="d-inline-block"><label>Yes</label><input type="radio" name="checklist_'.$checklist->id.'" value="yes"';
                if(Utils::getChecklist($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div><div class="d-inline-block ml-5"><label>No</label><input type="radio" name="checklist_{{ $checklist->id }}" value="no"';
                if(Utils::getChecklistNo($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div></div>';
            }
            $ret .= '<div class="form-group"><label>Folder to Attach</label><select name="folder" class="form-control" required><option value="">--- Select a folder ---</option>';
            foreach($folders as $folder){
                $ret .= '<option value="'.$folder->id.'">'.$folder->name.'</option>';
            }
            $ret .= '</select></div>';
            $ret .= '</div></div><div class="modal-footer d-flex justify-content-center"><button class="btn btn-default">Proceed</button></div></div></form></div></div>';
            $ret .= "<script>$(function(){ swal.fire({ title: 'Are you sure?', text: 'You won\'t be able to revert this!', type: 'warning', showCancelButton: true, confirmButtonText: 'Yes, approve file!', cancelButtonText: 'No, cancel!', reverseButtons: true }).then((result) => { if (result.value) { $('.inmodal').modal('show'); } else if (result.dismiss === Swal.DismissReason.cancel) { swal.fire('Cancelled', 'Your action has been cancelled', 'error'); } }); });</script>";
            echo $ret;
        }
    }

    public function approveLoanCompliance($id){
        $user = Utils::getUser();
        $customer = DB::table('customers')->where('id', $id)->first();
        $folder = DB::table('folders')->where('id', $customer->folder)->first();
        $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
        if($folder != null){
            $ret = '<div class="modal fade inmodal"><div class="modal-dialog" role="document"><form action="'.route('compliance').'" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="id" value="'.$id.'"><div class="modal-content"><div class="modal-header text-center"><h4 class="modal-title w-100 font-weight-bold">CHECKLIST</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body mx-3"><div class="md-form mb-5">';
            foreach($checklists as $checklist){
                $ret .= '<div class="form-group"><h6 class="title">'.$checklist->name.'</h6><div class="d-inline-block"><label>Yes</label><input type="radio" name="checklist_'.$checklist->id.'" value="yes"';
                if(Utils::getChecklist($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div><div class="d-inline-block ml-5"><label>No</label><input type="radio" name="checklist_{{ $checklist->id }}" value="no"';
                if(Utils::getChecklistNo($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div></div>';
            }
            $ret .= '</div></div><div class="modal-footer d-flex justify-content-center"><button class="btn btn-default">Proceed</button></div></div></form></div></div>';
            $ret .= "<script>$(function(){ swal.fire({ title: 'Are you sure?', text: 'You won\'t be able to revert this!', type: 'warning', showCancelButton: true, confirmButtonText: 'Yes, approve file!', cancelButtonText: 'No, cancel!', reverseButtons: true }).then((result) => { if (result.value) { $('.inmodal').modal('show'); } else if (result.dismiss === Swal.DismissReason.cancel) { swal.fire('Cancelled', 'Your action has been cancelled', 'error'); } }); });</script>";
            echo $ret;
        }else {
            $folders = DB::table('folders')->where('file', null)->orderBy('name', 'asc')->get();
            $ret = '<div class="modal fade inmodal"><div class="modal-dialog" role="document"><form action="'.route('checklist').'" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="id" value="'.$id.'"><div class="modal-content"><div class="modal-header text-center"><h4 class="modal-title w-100 font-weight-bold">CHECKLIST</h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="modal-body mx-3"><div class="md-form mb-5">';
            foreach($checklists as $checklist){
                $ret .= '<div class="form-group"><h6 class="title">'.$checklist->name.'</h6><div class="d-inline-block"><label>Yes</label><input type="radio" name="checklist_'.$checklist->id.'" value="yes"';
                if(Utils::getChecklist($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div><div class="d-inline-block ml-5"><label>No</label><input type="radio" name="checklist_{{ $checklist->id }}" value="no"';
                if(Utils::getChecklistNo($checklist->id,$id)){
                    $ret .= ' checked';
                }
                $ret .= '></div></div>';
            }
            $ret .= '<div class="form-group"><label>Folder to Attach</label><select name="folder" class="form-control" required><option value="">--- Select a folder ---</option>';
            foreach($folders as $folder){
                $ret .= '<option value="'.$folder->id.'">'.$folder->name.'</option>';
            }
            $ret .= '</select></div>';
            $ret .= '</div></div><div class="modal-footer d-flex justify-content-center"><button class="btn btn-default">Proceed</button></div></div></form></div></div>';
            $ret .= "<script>$(function(){ swal.fire({ title: 'Are you sure?', text: 'You won\'t be able to revert this!', type: 'warning', showCancelButton: true, confirmButtonText: 'Yes, approve file!', cancelButtonText: 'No, cancel!', reverseButtons: true }).then((result) => { if (result.value) { $('.inmodal').modal('show'); } else if (result.dismiss === Swal.DismissReason.cancel) { swal.fire('Cancelled', 'Your action has been cancelled', 'error'); } }); });</script>";
            echo $ret;
        }
    }

    public function mailprocessed($id){
        $user = Utils::getUser();
        $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
        $ret = '<div class="modal fade inmodal"><div class="modal-dialog" role="document"><form action="'.route('mailStatus').'" method="post"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="hidden" name="id" value="'.$id.'"><div class="modal-content"><div class="modal-body mx-3"><div class="md-form mb-5"><div class="form-group"><label>Email to send to</label><input type="text" class="form-control" name="email"></div></div></div><div class="modal-footer d-flex justify-content-center"><button class="btn btn-default">Submit</button></div></div></form></div></div>';
        $ret .= "<script>$(function(){ $('.inmodal').modal('show'); });</script>";
        echo $ret;
    }

    public function offersSender($id){
        $user = Utils::getUser();
        $ret = "<div class='modal fade inmodal' id='modalLoginForm".$id."' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'><div class='modal-dialog' role='document'><form action='".route('mailOffers')."' method='post'><input type='hidden' name='_token' value='".csrf_token()."'><input type='hidden' name='id' value='".$id."'><div class='modal-content'><div class='modal-body mx-3'><div class='md-form mb-5'><div class='form-group'><label>Email to send to</label><input type='text' class='form-control' name='email'></div></div></div><div class='modal-footer d-flex justify-content-center'><button class='btn btn-default'>Submit</button></div></div></form></div></div>";
        $ret .= "<script>$(function(){ $('.inmodal').modal('show'); });</script>";
        echo $ret;
    }

    public function processed(Request $req){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'gender', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'pension_number', 'tid', 'salary'];
            $posts = DB::table('customers')->where('status', 'approved')->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('processed', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::table('customers')->where('status', 'approved')->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('processed', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function declined(Request $req){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'gender', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'pension_number', 'tid', 'salary'];
            $posts = DB::table('customers')->where('status', 'declined')->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('declined', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::table('customers')->where('status', 'declined')->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('declined', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function previewCustomer($id){
        $user = Utils::getUser();
        Utils::createActivity($user->email, 'You previewed a loan form', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You previewed a loan form.');
        $customer = Utils::getForm($id);
        $saveName = 'generated/loan_form/'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
        $pdf = PDF::loadView('previewCustomer', compact('customer'))->save($saveName);
        $header = Utils::previewer($saveName);
        return view('previewer', ['file'=>$header]);
    }

    public function editFile($id)
    {
        $customer = Utils::getForm($id);
        if($customer->status != 'approved'){
            $user = Utils::getUser();
            return view('addCustomers', ['user'=>$user, 'edit'=>true, 'customer'=>$customer, 'id'=>$id]);
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> You cannot edit a file that has been approved by Head of Sales!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function approveFileCompliance(Request $req)
    {
        $user = Utils::getUser();
        $id = $req->id;
        $customer = Utils::getForm($id);
        if($customer->folder == null){
            if(!isset($req->folder)){
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> Please specify a folder for this !</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                DB::table('folders')->where('id', $req->folder)->update(['file'=>$id, 'updated_at'=>Carbon::now()]);
                DB::table('customers')->where('id', $id)->update(['folder'=>$req->folder, 'updated_at'=>Carbon::now()]);
                if($customer->compliance == 'pending'){
                    if($user->role == 'monitoring and compliance'){
                        $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                        foreach($checklists as $checklist){
                            $name = 'checklist_'.$checklist->id;
                            DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                            DB::table('filled_checklists')->insert([
                                'checklist'=>$checklist->id,
                                'value'=>$req->$name,
                                'form'=>$req->id,
                                'staff'=>$user->email,
                                'created_at'=>Carbon::now(),
                                'updated_at'=>Carbon::now(),
                            ]);
                        }
                        DB::table('customer2s')->where('customer_id', $id)->update(['compliance'=>'approved','updated_at'=>Carbon::now(),]);
                        $uniqid = uniqid().mt_rand(000000,999999);
                        $underwriters = DB::table('users')->where([['role','underwriter'],['status', '!=', 'blocked']])->get();
                        $internal_controls = DB::table('users')->where([['role','internal control'],['status', '!=', 'blocked']])->get();
                        $finances = DB::table('users')->where([['role','internal control'],['status', '!=', 'blocked']])->get();
                        $dfold = DB::table('folders')->where('id', $customer->folder)->first();
                        foreach ($underwriters as $dunderwriter) {
                            $name = $dunderwriter->name;
                            $message = 'Monitoring & Compliance just downloaded a file withe the following details <p><b>File Name:</b>'.$folder->name.'<br><b>File Link:</b>'.url('/file-manager/view/').$folder->slug.'</p>';
                            $subject = 'SWISS MONITORING & COMPLIANCE';
                            $email = $dunderwriter->email;
                            Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                        }
                        foreach ($internal_controls as $internal) {
                            $name = $internal->name;
                            $message = 'Monitoring & Compliance just downloaded a file withe the following details <p><b>File Name:</b>'.$folder->name.'<br><b>File Link:</b>'.url('/file-manager/view/').$folder->slug.'</p>';
                            $subject = 'SWISS MONITORING & COMPLIANCE';
                            $email = $internal->email;
                            Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                        }
                        foreach ($finances as $finan) {
                            $name = $finan->name;
                            $message = 'Monitoring & Compliance just downloaded a file withe the following details <p><b>File Name:</b>'.$folder->name.'<br><b>File Link:</b>'.url('/file-manager/view/').$folder->slug.'</p>';
                            $subject = 'SWISS MONITORING & COMPLIANCE';
                            $email = $finan->email;
                            Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                        }
                        Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Uploaded', 'Congratulations!!! You just approved a file for uploading.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                    }else {
                        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                    }
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-warning b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> No action needed for this file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }
        }else {
            if($customer->compliance == 'pending'){
                if($user->role == 'monitoring and compliance'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    DB::table('filled_checklists')->where('customer_id', $id)->update(['compliance'=>'approved', 'updated_at'=>Carbon::now()]);
                    $uniqid = uniqid().mt_rand(000000,999999);
                    $underwriters = DB::table('users')->where([['role','underwriter'],['status', '!=', 'blocked']])->get();
                    $internal_controls = DB::table('users')->where([['role','internal control'],['status', '!=', 'blocked']])->get();
                    $finances = DB::table('users')->where([['role','internal control'],['status', '!=', 'blocked']])->get();
                    $dfold = DB::table('folders')->where('id', $customer->folder)->first();
                    foreach ($underwriters as $dunderwriter) {
                        $name = $dunderwriter->name;
                        $message = 'Monitoring & Compliance just downloaded a file withe the following details <p><b>File Name:</b>'.$folder->name.'<br><b>File Link:</b>'.url('/file-manager/view/').$folder->slug.'</p>';
                        $subject = 'SWISS MONITORING & COMPLIANCE';
                        $email = $dunderwriter->email;
                        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                    }
                    foreach ($internal_controls as $internal) {
                        $name = $internal->name;
                        $message = 'Monitoring & Compliance just downloaded a file withe the following details <p><b>File Name:</b>'.$folder->name.'<br><b>File Link:</b>'.url('/file-manager/view/').$folder->slug.'</p>';
                        $subject = 'SWISS MONITORING & COMPLIANCE';
                        $email = $internal->email;
                        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                    }
                    foreach ($finances as $finan) {
                        $name = $finan->name;
                        $message = 'Monitoring & Compliance just downloaded a file withe the following details <p><b>File Name:</b>'.$folder->name.'<br><b>File Link:</b>'.url('/file-manager/view/').$folder->slug.'</p>';
                        $subject = 'SWISS MONITORING & COMPLIANCE';
                        $email = $finan->email;
                        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                    }
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Uploaded', 'Congratulations!!! You just approved a file for uploading.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-warning b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> No action needed for this file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }
    }

    public function approveFile(Request $req)
    {
        $user = Utils::getUser();
        $id = $req->id;
        $customer = Utils::getForm($id);
        if($customer->folder == null){
            if(!isset($req->folder)){
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> Please specify a folder for this !</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                DB::table('folders')->where('id', $req->folder)->update(['file'=>$id, 'updated_at'=>Carbon::now()]);
                DB::table('customers')->where('id', $id)->update(['folder'=>$req->folder, 'updated_at'=>Carbon::now()]);
            }
            if($customer->credit_risk == 'pending' || $customer->credit_risk == 'declined'){
                if($user->role == 'credit risk'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 7){
                        DB::table('customer2s')->where('customer_id', $id)->update(['credit_risk'=>'approved', 'updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'created_at'=>Carbon::now(),'updated_at'=>Carbon::now(),]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! HOS just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');

                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }else if($customer->underwriters == 'pending' || $customer->underwriters == 'declined'){
                if($user->role == 'underwriter'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 18){
                        DB::table('customer2s')->where('customer_id', $id)->update(['underwriters'=>'approved','updated_at'=>Carbon::now(),]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null,'updated_at'=>Carbon::now(),]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role,'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! HOS just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');

                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->risk == 'pending' || $customer->risk == 'declined'){
                if($user->role == 'risk'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 28){
                        DB::table('customer2s')->where('customer_id', $id)->update(['risk'=>'approved','updated_at'=>Carbon::now(),]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now(),]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role,'updated_at'=>Carbon::now(),]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! RISK just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }

            }elseif($customer->customer == 'pending' || $customer->customer == 'declined'){
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> This file has not yet being signed by the customer!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }elseif($customer->operations == 'pending' || $customer->operations == 'declined'){
                if($user->role == 'operations'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 33){
                        DB::table('customer2s')->where('customer_id', $id)->update(['operations'=>'approved','updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null,'updated_at'=>Carbon::now(),]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role,'updated_at'=>Carbon::now(),]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Operations just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->finance == 'pending' || $customer->finance == 'declined'){
                if($user->role == 'finance'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 38){
                        DB::table('customer2s')->where('customer_id', $id)->update(['finance'=>'approved','updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null,'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Finance just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->internal_control == 'pending' || $customer->internal_control == 'declined'){
                if($user->role == 'internal control'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 42){
                        DB::table('customer2s')->where('customer_id', $id)->update(['internal_control'=>'approved','updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null,'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Internal Control just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-danger', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->status == 'pending' || $customer->status == 'declined' || $customer->status == null){
                if($user->role == 'director'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    DB::table('customer2s')->where('customer_id', $id)->update(['status'=>'approved','updated_at'=>Carbon::now()]);
                    DB::table('customers')->where('id', $id)->update(['status'=>'approved','updated_at'=>Carbon::now()]);
                    $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                    if($acounts < 1){
                        DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role,'updated_at'=>Carbon::now()]);
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                    $message = 'kindly note that your loan application is currently being processed. Please reach out to risk@swisscredit.ng for further enquiry.';
                    $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                    $email = $customer->official_email ?? $customer->email;
                    Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Director just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-warning b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> No action needed for this file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }else {
            if($customer->credit_risk == 'pending' || $customer->credit_risk == 'declined'){
                if($user->role == 'credit risk'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 7){
                        DB::table('customer2s')->where('customer_id', $id)->update(['credit_risk'=>'approved', 'updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! HOS just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');

                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }else if($customer->underwriters == 'pending' || $customer->underwriters == 'declined'){
                if($user->role == 'underwriter'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 18){
                        DB::table('customer2s')->where('customer_id', $id)->update(['underwriters'=>'approved', 'updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! HOS just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');

                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->risk == 'pending' || $customer->risk == 'declined'){
                if($user->role == 'risk'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 28){
                        DB::table('customer2s')->where('customer_id', $id)->update(['risk'=>'approved', 'updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! RISK just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }

            }elseif($customer->customer == 'pending' || $customer->customer == 'declined'){
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> This file has not yet being signed by the customer!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }elseif($customer->operations == 'pending' || $customer->operations == 'declined'){
                if($user->role == 'operations'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 33){
                        DB::table('customer2s')->where('customer_id', $id)->update(['operations'=>'approved', 'updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role,'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Operations just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->finance == 'pending' || $customer->finance == 'declined'){
                if($user->role == 'finance'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 38){
                        DB::table('customer2s')->where('customer_id', $id)->update(['finance'=>'approved', 'updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Finance just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->internal_control == 'pending' || $customer->internal_control == 'declined'){
                if($user->role == 'internal control'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    $counts = DB::table('filled_checklists')->where(['form'=>$req->id, 'value'=>'yes'])->orWhere(['form'=>$req->id, 'value'=>'no'])->count();
                    if($counts > 42){
                        DB::table('customer2s')->where('customer_id', $id)->update(['internal_control'=>'approved', 'updated_at'=>Carbon::now()]);
                        DB::table('customers')->where('id', $id)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
                        $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                        if($acounts < 1){
                            DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                        }
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Internal Control just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-danger', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }elseif($customer->status == 'pending' || $customer->status == 'declined' || $customer->status == null){
                if($user->role == 'director'){
                    $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
                    foreach($checklists as $checklist){
                        $name = 'checklist_'.$checklist->id;
                        DB::table('filled_checklists')->where(['checklist'=>$checklist->id, 'form'=>$req->id])->delete();
                        DB::table('filled_checklists')->insert([
                            'checklist'=>$checklist->id,
                            'value'=>$req->$name,
                            'form'=>$req->id,
                            'staff'=>$user->email,
                            'created_at'=>Carbon::now(),
                            'updated_at'=>Carbon::now(),
                        ]);
                    }
                    DB::table('customer2s')->where('customer_id', $id)->update(['status'=>'approved','updated_at'=>Carbon::now()]);
                    DB::table('customers')->where('id', $id)->update(['status'=>'approved','updated_at'=>Carbon::now()]);
                    $acounts = DB::table('approvals')->where(['staff'=>$user->email, 'file'=>$id])->count();
                    if($acounts < 1){
                        DB::table('approvals')->insert(['staff'=>$user->email,'file'=>$id, 'role'=>$user->role, 'updated_at'=>Carbon::now()]);
                    }
                    $uniqid = uniqid().mt_rand(000000,999999);
                    $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                    $message = 'kindly note that your loan application is currently being processed. Please reach out to risk@swisscredit.ng for further enquiry.';
                    $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                    $email = $customer->official_email ?? $customer->email;
                    Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                    // agents mail
                    $swissCForm = DB::table('customers')->where('id', $id)->first();
                    $swissagent = DB::connection('mysql2')->table('agents')->where('referral_code', $swissCForm->swiss_club_agent)->first();
                    if($swissagent != null){
                        $message = 'Your customer with the name <b>'.strtoupper($swissCForm->firstname.' '.$swissCForm->middle_name.' '.$swissCForm->surname).'</b>  has just been approved for disbursement.';
                        Mail::to($swissagent->email)->send(new SendMailerCustomer($swissagent->name, $message, $subject));
                    }
                    Utils::createNotification('<i class="dripicons dripicons-document-edit text-white"></i>', 'Document Approved', 'Congratulations!!! Director just approved a file for processing.', $user->email, '/notifications/'.$uniqid.'/'.Utils::encrypt_decrypt('encrypt', '/customers'), 'bg-primary', $uniqid);
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have approved this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }else {
                    return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to approve the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
                }
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-warning b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> No action needed for this file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }
    }

    public function declineFile(Request $req)
    {
        $id = $req->id;
        $user = Utils::getUser();
        $customer = Utils::getForm($id);
        if($customer->credit_risk != 'approved'){
            if($user->role == 'credit risk'){
                DB::table('customer2s')->where('customer_id', $id)->update(['credit_risk'=>'declined', 'reason'=>$req->reason, 'declined_by'=>$user->email, 'updated_at'=>Carbon::now()]);
                DB::table('customers')->where('id', $id)->update(['status'=>'declined', 'updated_at'=>Carbon::now()]);
                $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
                $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                $email = $customer->official_email ?? $customer->email;
                Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have declined this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to decline the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }elseif($customer->underwriters != 'approved'){
            if($user->role == 'underwriter'){
                DB::table('customer2s')->where('customer_id', $id)->update(['underwriters'=>'declined', 'reason'=>$req->reason, 'declined_by'=>$user->email, 'updated_at'=>Carbon::now()]);
                $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
                $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                $email = $customer->official_email ?? $customer->email;
                Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                DB::table('customers')->where('id', $id)->update(['status'=>'declined', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have declined this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to decline the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }elseif($customer->risk != 'approved'){
            if($user->role == 'risk'){
                DB::table('customer2s')->where('customer_id', $id)->update(['risk'=>'declined', 'reason'=>$req->reason, 'declined_by'=>$user->email, 'updated_at'=>Carbon::now()]);
                $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
                $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                $email = $customer->official_email ?? $customer->email;
                Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                DB::table('customers')->where('id', $id)->update(['status'=>'declined', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have declined this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to decline the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }

        }elseif($customer->customer != 'approved'){
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> This file has not yet being signed by the customer!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }elseif($customer->operations != 'approved'){
            if($user->role == 'operations'){
                DB::table('customer2s')->where('customer_id', $id)->update(['operations'=>'declined', 'reason'=>$req->reason, 'declined_by'=>$user->email, 'updated_at'=>Carbon::now()]);
                $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
                $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                $email = $customer->official_email ?? $customer->email;
                Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                DB::table('customers')->where('id', $id)->update(['status'=>'declined', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have declined this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to decline the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }elseif($customer->finance != 'approved'){
            if($user->role == 'finance'){
                DB::table('customer2s')->where('customer_id', $id)->update(['finance'=>'declined', 'reason'=>$req->reason, 'declined_by'=>$user->email, 'updated_at'=>Carbon::now()]);
                $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
                $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                $email = $customer->official_email ?? $customer->email;
                Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                DB::table('customers')->where('id', $id)->update(['status'=>'declined', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have declined this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to decline the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }elseif($customer->internal_control != 'approved'){
            if($user->role == 'internal control'){
                DB::table('customer2s')->where('customer_id', $id)->update(['internal_control'=>'declined', 'reason'=>$req->reason, 'declined_by'=>$user->email, 'updated_at'=>Carbon::now()]);
                $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
                $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                $email = $customer->official_email ?? $customer->email;
                Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                DB::table('customers')->where('id', $id)->update(['status'=>'declined', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have declined this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to decline the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }elseif($customer->status != 'approved'){
            if($user->role == 'director'){
                DB::table('customer2s')->where('customer_id', $id)->update(['status'=>'declined', 'reason'=>$req->reason, 'declined_by'=>$user->email, 'updated_at'=>Carbon::now()]);
                $name = $customer->firstname.' '.$customer->middle_name.' '.$customer->surname;
                $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
                $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
                $email = $customer->official_email ?? $customer->email;
                Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
                DB::table('customers')->where('id', $id)->update(['status'=>'declined', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have declined this file.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-danger b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> It is either this file has passed your level, not yet reached your level or you don\'t have the right to decline the file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-warning b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Error!</strong> No action needed for this file!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function staffs(Request $req){
        $user = Utils::getUser();
        if($user->role != 'director' && $user->role != 'internal control' && $user->role != 'it' && $user->role != 'super admin'){
            return view('errors.401');
        }else{
            if($req->search != null && $req->search != ''){
                $search = $req->search;
                $requestData = ['name', 'email', 'password', 'role', 'passport', 'staff_id', 'team', 'lead', 'status'];
                $posts = DB::table('users')->where(function($q) use($requestData, $search) {
                            foreach ($requestData as $field)
                               $q->orWhere($field, 'like', "%{$search}%");
                    })->limit($req->num ?? 50)->orderBy('id','desc')->get();
                return view('staffs', ['user'=>$user, 'posts'=>$posts]);
            }else{
                $posts = DB::table('users')->limit($req->num ?? 50)->orderBy('id','desc')->get();
                return view('staffs', ['user'=>$user, 'posts'=>$posts]);
            }
        }
    }

    public function updateStaff($id){
        $user = Utils::getUser();
        $staff = DB::table('users')->where('id', $id)->first();
        if($staff->status == null){
            Utils::createActivity($user->email, 'You blocked a staff', '<i class="lni-users bg-soft-pink"></i>', 'You restricted <b><q>'.$staff->name.'</q></b> from the system successfully.');
            User::where('id', $id)->update(['status'=>'blocked']);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You just blocked a staff from the system.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            Utils::createActivity($user->email, 'You unblocked a staff', '<i class="lni-users bg-soft-success"></i>', 'You unblocked <b><q>'.$staff->name.'</q></b> from the system successfully.');
            User::where('id', $id)->update(['status'=>null]);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You just unblocked a staff to the system.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function generateOffers(){
        $user = Utils::getUser();
        if($user->role != 'risk' && $user->role != 'super admin' && $user->role != 'underwriter'){
            return view('errors.401');
        }else{
            $posts = DB::table('customers')->where('status', null)->whereNotExists(function ($query) { $query->select(DB::raw(1))->from('offers')->whereRaw('offers.d_form = customers.id'); })->orderBy('id', 'desc')->get();
            return view('generateOffers', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function addOffers($id=null){
        $user = Utils::getUser();
        if($user->role != 'risk' && $user->role != 'super admin'  && $user->role != 'underwriter'){
            return view('errors.401');
        }else
        return view('addOffer', ['user'=>$user, 'id'=>$id]);
    }

    public function addOfferPost(Request $req){
        $user = Utils::getUser();
        DB::table('offers')->insert([
            'name'=>$req->name,
            'address'=>$req->address,
            'employer'=>$req->employer,
            'date_employed'=>$req->date_employed,
            'position'=>$req->position,
            'empStatus'=>$req->empStatus,
            'purpose'=>$req->purpose,
            'amount_requested'=>$req->amount_requested,
            'amount_recommended'=>$req->amount_recommended,
            'tenor'=>$req->loan_tenor,
            'request_status'=>$req->request_status,
            'last_request'=>$req->last_request,
            'performance_last_request'=>$req->last_performance,
            'repayment_structure'=>$req->repayment_structure,
            'repayment_source'=>$req->repayment_source,
            'interest'=>$req->interest,
            'prorated'=>$req->prorated,
            'processing_fee'=>$req->processing_fee,
            'management_fee'=>$req->management_fee,
            'salary'=>$req->salary,
            'other_obligations'=>$req->other_loans,
            'credit_check'=>$req->credit_check,
            'collateral'=>$req->collateral,
            'sourced_by'=>$req->sourced_by,
            'account_officer'=>$req->account_officer,
            'office_address'=>$req->office_address,
            'statement_of_ac'=>$req->account_statement,
            'staff'=>$user->email,
            'disbursement_account'=>$req->disbursement,
            'file_code'=>$req->file_code,
            'pay_date'=>$req->pay_date,
            'bvn'=>$req->bvn,
            'phone_number'=>$req->phone_number,
            'customers_email'=>$req->customers_email,
            'pay_starts'=>$req->pay_starts,
            'default_charge'=>$req->default_charge,
            'outstanding_bal'=>$req->outstanding_bal,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        Utils::createActivity($user->email, 'You generetad an offer', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You created a loan offer for <b><q>'.$req->name.'</q></b> successfully.');
        return redirect('/offers')->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your offer has been created.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');

    }

    public function addOfferPost2(Request $req){
        $user = Utils::getUser();
        DB::table('offers')->insert([
            'name'=>$req->name,
            'address'=>$req->address,
            'employer'=>$req->employer,
            'date_employed'=>$req->date_employed,
            'position'=>$req->position,
            'empStatus'=>$req->empStatus,
            'purpose'=>$req->purpose,
            'amount_requested'=>$req->amount_requested,
            'amount_recommended'=>$req->amount_recommended,
            'tenor'=>$req->loan_tenor,
            'request_status'=>$req->request_status,
            'last_request'=>$req->last_request,
            'performance_last_request'=>$req->last_performance,
            'repayment_structure'=>$req->repayment_structure,
            'repayment_source'=>$req->repayment_source,
            'interest'=>$req->interest,
            'prorated'=>$req->prorated,
            'processing_fee'=>$req->processing_fee,
            'management_fee'=>$req->management_fee,
            'salary'=>$req->salary,
            'other_obligations'=>$req->other_loans,
            'credit_check'=>$req->credit_check,
            'collateral'=>$req->collateral,
            'sourced_by'=>$req->sourced_by,
            'account_officer'=>$req->account_officer,
            'office_address'=>$req->office_address,
            'statement_of_ac'=>$req->account_statement,
            'staff'=>$user->email,
            'disbursement_account'=>$req->disbursement,
            'd_form'=>$req->id,
            'file_code'=>$req->file_code,
            'pay_date'=>$req->pay_date,
            'pay_starts'=>$req->pay_starts,
            'default_charge'=>$req->default_charge,
            'outstanding_bal'=>$req->outstanding_bal,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        Utils::createActivity($user->email, 'You created an offer', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You created a loan offer for a loan form');
        return redirect('/offers')->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your offer has been created.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');

    }

    public function offers(Request $req){
        $user = Utils::getUser();
        if($user->role != 'risk' && $user->role != 'super admin'  && $user->role != 'underwriter'){
            return view('errors.401');
        }else{
            if($req->search != null && $req->search != ''){
                $search = $req->search;
                $requestData = ['name', 'address', 'employer', 'date_employed', 'position', 'empStatus', 'category', 'purpose', 'amount_requested', 'amount_recommended', 'tenor', 'request_status', 'last_request', 'performance_last_request', 'repayment_structure', 'repayment_source', 'interest', 'prorated', 'processing_fee', 'management_fee', 'salary', 'other_obligations', 'credit_check', 'collateral', 'sourced_by', 'account_officer', 'office_address', 'statement_of_ac', 'disbursement_account', 'file_code', 'pay_date', 'bvn', 'phone_number', 'customers_email', 'pay_starts', 'default_charge'];
                $posts = DB::table('offers')->where(function($q) use($requestData, $search) {
                            foreach ($requestData as $field)
                               $q->orWhere($field, 'like', "%{$search}%");
                    })->limit($req->num ?? 50)->orderBy('id','desc')->get();
                return view('offers', ['user'=>$user, 'posts'=>$posts]);
            }else{
                $posts = DB::table('offers')->limit($req->num ?? 50)->orderBy('id','desc')->get();
                return view('offers', ['user'=>$user, 'posts'=>$posts]);
            }
        }
    }

    public function viewOffer($id){
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $id)->first();
        Utils::createActivity($user->email, 'You previewed an offer', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You previewed a loan offer.');
        if($offer->d_form != null){
            $customer = Utils::getForm($offer->d_form);
            $signature = DB::table('signatures')->where('offer', $offer->id)->first();
            $saveName = 'generated/loan_form/offer_letter_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
            $pdf = PDF::loadView('offer_letter_form', ['offer'=>$offer, 'customer'=>$customer, 'signature'=>$signature])->save($saveName);
            $header = Utils::previewer($saveName);
            return view('previewer', ['file'=>$header]);
        }else {
            $saveName = 'generated/loan_form/offer_letter_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
            $pdf = PDF::loadView('offer_letter', compact('offer'))->save($saveName);
            $header = Utils::previewer($saveName);
            return view('previewer', ['file'=>$header]);
        }

    }

    public function recommendations($id){
        $user = Utils::getUser();
        Utils::createActivity($user->email, 'You previewed a recommendations.', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You previewed a recommendations.');
        $customer = Utils::getForm($id);
        $creditChecks = DB::table('checklists')->where('role','credit risk')->orderBy('id', 'asc')->get();
        $underwriterChecks = DB::table('checklists')->where('role','underwriter')->orderBy('id', 'asc')->get();
        $riskChecks = DB::table('checklists')->where('role','risk')->orderBy('id', 'asc')->get();
        $operationChecks = DB::table('checklists')->where('role','operations')->orderBy('id', 'asc')->get();
        $financeChecks = DB::table('checklists')->where('role','finance')->orderBy('id', 'asc')->get();
        $internalcontrolChecks = DB::table('checklists')->where('role','internal control')->orderBy('id', 'asc')->get();
        $directorChecks = DB::table('checklists')->where('role','director')->orderBy('id', 'asc')->get();
        $saveName = 'generated/recommendations/recommendations_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
        $pdf = PDF::loadView('recommendations', ['creditChecks'=>$creditChecks, 'underwriterChecks'=>$underwriterChecks, 'riskChecks'=>$riskChecks, 'operationChecks'=>$operationChecks, 'financeChecks'=>$financeChecks, 'internalcontrolChecks'=>$internalcontrolChecks, 'directorChecks'=>$directorChecks, 'customer'=>$customer])->save($saveName);
        $header = Utils::previewer($saveName);
        return view('previewer', ['file'=>$header]);

    }

    public function viewSummary($id){
        DB::disableQueryLog();
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $id)->first();
        Utils::createActivity($user->email, 'You previewed a summary', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You previewed a loan summary.');
        if($offer->d_form != null){
            $customer = Utils::getForm($offer->d_form);
            $saveName = 'generated/loan_form/summary_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
            $dguar = DB::table('guarantors')->where('d_offer', $offer->id)->first();
            $pdf = PDF::loadView('summary_form', ['offer'=>$offer, 'customer'=>$customer, 'dguar'=>$dguar,])->save($saveName);
            $header = Utils::previewer($saveName);
            return view('previewer', ['file'=>$header]);
        }else {
            $saveName = 'generated/loan_form/summary_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
            $pdf = PDF::loadView('summary', compact('offer'))->save($saveName);
            $header = Utils::previewer($saveName);
            return view('previewer', ['file'=>$header]);
        }

    }

    public function editOffers($id){
        DB::disableQueryLog();
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $id)->first();
        return view('addOffer', ['user'=>$user, 'edit'=>true, 'offer'=>$offer]);
    }

    public function editOffersTwoPost(Request $req){
        DB::disableQueryLog();
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $req->id)->first();
        DB::table('offers')->where('id', $req->id)->update([
            'name'=>$req->name,
            'address'=>$req->address,
            'employer'=>$req->employer,
            'date_employed'=>$req->date_employed,
            'position'=>$req->position,
            'empStatus'=>$req->empStatus,
            'purpose'=>$req->purpose,
            'amount_recommended'=>$req->amount_recommended,
            'tenor'=>$req->loan_tenor,
            'request_status'=>$req->request_status,
            'last_request'=>$req->last_request,
            'performance_last_request'=>$req->last_performance,
            'repayment_structure'=>$req->repayment_structure,
            'repayment_source'=>$req->repayment_source,
            'interest'=>$req->interest,
            'prorated'=>$req->prorated,
            'processing_fee'=>$req->processing_fee,
            'management_fee'=>$req->management_fee,
            'salary'=>$req->salary,
            'other_obligations'=>$req->other_loans,
            'credit_check'=>$req->credit_check,
            'collateral'=>$req->collateral,
            'sourced_by'=>$req->sourced_by,
            'statement_of_ac'=>$req->account_statement,
            'account_officer'=>$req->account_officer,
            'office_address'=>$req->office_address,
            'disbursement_account'=>$req->disbursement,
            'last_edited'=>$user->email,
            'file_code'=>$req->file_code,
            'pay_date'=>$req->pay_date,
            'pay_starts'=>$req->pay_starts,
            'default_charge'=>$req->default_charge,
            'status'=>'pending',
            'outstanding_bal'=>$req->outstanding_bal,
            'updated_at'=>Carbon::now()
        ]);
        Utils::createActivity($user->email, 'You edited an offer', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You edited a loan offer ');
        DB::table('customer2s')->where('customer_id', $offer->d_form)->update(['customer'=>'pending', 'updated_at'=>Carbon::now()]);
        DB::table('customer2s')->where('id', $offer->d_form)->update(['status'=>null, 'updated_at'=>Carbon::now()]);
        DB::table('signatures')->where('offer', $offer->id)->delete();
        return redirect('/offers')->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your offer has been edited.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function editOffersPost(Request $req){
        $user = Utils::getUser();
        Utils::createActivity($user->email, 'You edited an offer', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You edited a loan offer with the name '.$req->name.' .');
        DB::table('offers')->where('id', $req->id)->update([
            'name'=>$req->name,
            'address'=>$req->address,
            'employer'=>$req->employer,
            'date_employed'=>$req->date_employed,
            'position'=>$req->position,
            'empStatus'=>$req->empStatus,
            'purpose'=>$req->purpose,
            'amount_requested'=>$req->amount_requested,
            'amount_recommended'=>$req->amount_recommended,
            'tenor'=>$req->loan_tenor,
            'request_status'=>$req->request_status,
            'last_request'=>$req->last_request,
            'performance_last_request'=>$req->last_performance,
            'repayment_structure'=>$req->repayment_structure,
            'repayment_source'=>$req->repayment_source,
            'interest'=>$req->interest,
            'prorated'=>$req->prorated,
            'processing_fee'=>$req->processing_fee,
            'management_fee'=>$req->management_fee,
            'salary'=>$req->salary,
            'other_obligations'=>$req->other_loans,
            'credit_check'=>$req->credit_check,
            'collateral'=>$req->collateral,
            'sourced_by'=>$req->sourced_by,
            'account_officer'=>$req->account_officer,
            'office_address'=>$req->office_address,
            'statement_of_ac'=>$req->account_statement,
            'last_edited'=>$user->email,
            'disbursement_account'=>$req->disbursement,
            'file_code'=>$req->file_code,
            'pay_date'=>$req->pay_date,
            'bvn'=>$req->bvn,
            'phone_number'=>$req->phone_number,
            'customers_email'=>$req->customers_email,
            'pay_starts'=>$req->pay_starts,
            'default_charge'=>$req->default_charge,
            'outstanding_bal'=>$req->outstanding_bal,
            'updated_at'=>Carbon::now()
        ]);
        return redirect('/offers')->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your offer has been edited.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function mailOffers(Request $req){
        $id = $req->id;
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $id)->first();
        $dform = Utils::getForm($offer->d_form);
        $name = $dform->firstname.' '.$dform->middle_name.' '.$dform->surname;
        $dlink = env('APP_URL').'/customer/offer/'.Utils::encrypt_decrypt('encrypt', $offer->id);
        $message = 'Your offer letter has been created, please click on th link below to view and sign your offer letter.<br><br>If you have any problem with this offer, please contact your account officer.<br><br><br>'.$dlink;
        $subject = 'OFFER LETTER';
        $email = $req->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        Utils::createActivity($user->email, 'You mailed out an offer', '<i class="dripicons dripicons-mail bg-soft-secondary"></i>', 'You sent an offer mail out to a customer.');
        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Offer sent to customer.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function signatures($id){
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $id)->first();
        $signatures = DB::table('signatures')->where('offer', $id)->first();
        return view('signatures', ['user'=>$user, 'offer'=>$offer, 'signatures'=>$signatures,]);
    }

    public function setSignature($id, $duse){
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $id)->first();
        $signature = DB::table('signatures')->where('offer', $id)->first();
        if($duse == 'use-1'){
            DB::table('signatures')->where('id', $signature->id)->update(['inuse'=>'1', 'updated_at'=>Carbon::now()]);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have set the first signature as the default signature.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            DB::table('signatures')->where('id', $signature->id)->update(['inuse'=>'2', 'updated_at'=>Carbon::now()]);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have set the second signature as the default signature.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function transfers(){
        $user = Utils::getUser();
        $offers = DB::table('offers')->orderBy('id', 'desc')->get();
        if($user->role != 'finance' && $user->role != 'super admin'){
            return view('errors.401');
        }else
        return view('instructions', ['user'=>$user, 'offers'=>$offers]);
    }

    public function generateTrans(Request $req){
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $req->offer)->first();
        Utils::createActivity($user->email, 'You generated an transfer', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You generated a transfer instruction.');
        if($offer->d_form != null){
            $customer = Utils::getForm($offer->d_form);
            $saveName = 'generated/loan_form/transfer_instruction_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
            $pdf = PDF::loadView('transfer_instructions_form', ['offer'=>$offer, 'customer'=>$customer, 'req'=>$req])->save($saveName);
            $header = Utils::previewer($saveName);
            return view('previewer', ['file'=>$header]);
        }else {
            $saveName = 'generated/loan_form/transfer_instruction_'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
            $pdf = PDF::loadView('transfer_instructions', ['offer'=>$offer, 'req'=>$req])->save($saveName);
            $header = Utils::previewer($saveName);
            return view('previewer', ['file'=>$header]);
        }
    }

    public function profile(){
        $user = Utils::getUser();
        return view('profile', ['user'=>$user]);
    }

    public function logout () {
        auth()->logout();
        session()->flush();
        return redirect('/login');
    }

    public function profilePost(Request $req){
        $user = Utils::getUser();
        if(file_exists($user->passport))
            unlink($user->passport);
        if ($files = $req->file('passport')) {
            $destinationPath = 'uploads/staff/passports';
            $dimagename = $files->getClientOriginalName();
            $filename = pathinfo($dimagename, PATHINFO_FILENAME);
            $extension = pathinfo($dimagename, PATHINFO_EXTENSION);
            $dfile = uniqid().'_'.time().'_'.mt_rand(000000,999999).'_'.Utils::removeSpaces($filename).'.'.$extension;
            $files->move($destinationPath, $dfile);
            User::where('email', $req->email)->update([
                'passport'=>$destinationPath.'/'.$dfile,
            ]);
        }
        DB::table('users')->where('email', $req->email)->update([
            'name'=>$req->name,
            'staff_id'=>$req->staff_id,
            'updated_at'=>Carbon::now()
        ]);
        Utils::createActivity($user->email, 'Profile Update', '<i class="lni-user bg-soft-pink"></i>', 'You successfully updated your profike details.');
        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Profile Update successful.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function viewNotifications($uniqid, $code){
        $notification = DB::table('notifications')->where('uniqid', $uniqid)->update(['status'=>'read', 'updated_at'=>Carbon::now()]);
        return redirect(Utils::encrypt_decrypt('decrypt', $code));
    }

    public function requestGuarantors($id){
        $user = Utils::getUser();
        $offer = DB::table('offers')->where('id', $id)->first();
        $dform = Utils::getForm($offer->d_form);
        $name = $dform->firstname.' '.$dform->middle_name.' '.$dform->surname;
        $codee = uniqid().mt_rand(000000,999999).time();
        DB::table('guarantors')->insert([
            'code'=>$codee,
            'd_offer'=>$offer->id,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        $dlink = env('APP_URL').'/customer/guarantors/'.$codee;
        $message = 'To proceed with your loan processing, Please kindly forward this mail to your guarantor to fill the guarantor\'s form. Click the link below to access the form.<br><br><br>'.$dlink;
        $subject = 'GURANTOR REQUIRED';
        $email = $dform->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        if($dform->official_email != null){
            Mail::to($dform->official_email)->send(new SendMailerCustomer($name, $message, $subject));
        }
        Utils::createActivity($user->email, 'You mailed out a guarantor\'s form', '<i class="dripicons dripicons-mail bg-soft-secondary"></i>', 'You sent an offer mail out to a customer.');
        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Guaranto form sent to customer.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function viewGuarantor($id)
    {
        $user = Utils::getUser();
        $dguar = DB::table('guarantors')->where('id', $id)->first();
        $offer = DB::table('offers')->where('id', $dguar->d_offer)->first();
        $dform = Utils::getForm($offer->d_form);
        $sign = DB::table('guarantor_signs')->where('form_id', $dguar->id)->first();
        return view('viewGuarantor', ['user'=>$user, 'dguar'=>$dguar, 'offer'=>$offer, 'dform'=>$dform, 'sign'=>$sign]);
    }

    public function guarantorSignature($id){
        $user = Utils::getUser();
        $signatures = DB::table('guarantor_signs')->where('id', $id)->first();
        return view('guarantorSignatures', ['user'=>$user, 'signatures'=>$signatures,]);
    }

    public function setGuarantorSignature($duse){
        $user = Utils::getUser();
        $signature = DB::table('guarantor_signs')->where('id', $duse)->first();
        if($duse == 'use-1'){
            DB::table('guarantor_signs')->where('id', $signature->id)->update(['default'=>'1', 'updated_at'=>Carbon::now()]);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have set the first signature as the default signature.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            DB::table('guarantor_signs')->where('id', $signature->id)->update(['default'=>'2', 'updated_at'=>Carbon::now()]);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have set the second signature as the default signature.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function downloadGuarantors($id){
        $user = Utils::getUser();
        Utils::createActivity($user->email, 'You downloaded a guarantors form', '<i class="fas fa-file-pdf bg-soft-danger"></i>', 'You downloaded a guarantor form.');
        $dguar = DB::table('guarantors')->where('id', $id)->first();
        $sign = DB::table('guarantor_signs')->where('form_id', $dguar->id)->first();
        $saveName = 'generated/loan_form/'.uniqid().'_'.time().'_'.mt_rand(000000,999999).'.pdf';
        $pdf = PDF::loadView('previewGuarantor', ['dguar'=>$dguar, 'sign'=>$sign])->save($saveName);
        $header = Utils::previewer($saveName);
        return view('previewer', ['file'=>$header]);
    }

    public function deleteFile($id){
        $user = Utils::getUser();
        $dupload = DB::table('uploads')->where('id', $id)->first();
        $permission = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'approved'],['internal_control', 'approved'],['folder', $dupload->folder],['status', 'not used']])->first();
        if($permission != null){
            $counts = DB::table('trashes')->where(['type'=>'file', 'the_id'=>$dupload->id])->count();
            if($counts > 0){
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> This file already exists in the Recycle bin!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                DB::table('trashes')->insert([
                    'type'=>'file',
                    'user'=>$user->email,
                    'folder'=>$dupload->folder,
                    'the_id'=>$id,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                DB::table('uploads')->where('id', $id)->update(['status'=>'deleted', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have delete a file successfully.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> You don\'t have access to delete from this folder!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }

    }

    public function deleteFolder($id){
        $user = Utils::getUser();
        $permission = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'approved'],['internal_control', 'approved'],['folder', $id],['status', 'not used']])->first();
        if($permission != null){
            $counts = DB::table('trashes')->where(['type'=>'folder', 'the_id'=>$id])->count();
            if($counts > 0){
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> This folder already exists in the Recycle bin!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                DB::table('trashes')->insert([
                    'type'=>'folder',
                    'user'=>$user->email,
                    'folder'=>$id,
                    'the_id'=>$id,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                DB::table('folders')->where('id', $id)->update(['status'=>'deleted', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have delete a folder successfully.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> You don\'t have access to delete this folder!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function deleteLink($id){
        $user = Utils::getUser();
        $link = DB::table('links')->where('id', $id)->first();
        $folder = DB::table('folders')->where('id', $link->folder)->first();
        $permission = DB::table('folder_permissions')->where([['staff', $user->email],['director', 'approved'],['internal_control', 'approved'],['folder', $folder->id],['status', 'not used']])->first();
        if($permission != null){
            $counts = DB::table('trashes')->where(['type'=>'link', 'the_id'=>$link->id])->count();
            if($counts > 0){
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> This link already exists in the Recycle bin!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }else {
                DB::table('trashes')->insert([
                    'type'=>'link',
                    'user'=>$user->email,
                    'folder'=>$folder->id,
                    'the_id'=>$id,
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ]);
                DB::table('links')->where('id', $id)->update(['status'=>'deleted', 'updated_at'=>Carbon::now()]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> You have delete a link successfully.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> You don\'t have access to delete this link!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function requestPerm($slug){
        $folder = DB::table('folders')->where('slug', $slug)->first();
        $user = Utils::getUser();
        $permission = DB::table('folder_permissions')->where([['staff', $user->email],['folder', $folder->id],['status', 'not used']])->first();
        if($permission != null){
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> You already have been given the permission to write and edit in this folder!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            if($user->role != 'credit risk' && $user->role != 'underwriter' && $user->role != 'risk' && $user->role != 'monitoring and compliance'){
                return view('errors.401');
            }else {
                DB::table('folder_permissions')->insert([
                    'staff'=>$user->email,
                    'folder'=>$folder->id,
                    'updated_at'=>Carbon::now()
                ]);
                return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your permission request has been submitted successfully!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
            }
        }
    }

    public function revokeAccess($id){
        DB::disableQueryLog();
        $user = Utils::getUser();
        DB::table('folder_permissions')->where('id', $id)->update(['status'=>'used','updated_at'=>Carbon::now()]);
        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> This permission has been revoked!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function grantPermission($id){
        $user = Utils::getUser();
        if($user->role == 'director'){
            DB::table('folder_permissions')->where('id', $id)->update(['director'=>'approved','updated_at'=>Carbon::now()]);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> This permission has been approved!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else if($user->role == 'internal control'){
            DB::table('folder_permissions')->where('id', $id)->update(['internal_control'=>'approved','updated_at'=>Carbon::now()]);
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> This permission has been approved!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> You don\'t have permission to perform this action!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function deletePermission($id){
        $user = Utils::getUser();
        if($user->role == 'director' || $user->role == 'internal control'){
            DB::table('folder_permissions')->where('id', $id)->delete();
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> This permission has been deleted!</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }else {
            return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-pink b-round fade show" role="alert"><i class="dripicons dripicons-warning alert-icon"></i><div class="alert-text"><strong>Oh Snap!</strong> You don\'t have permission to perform this action!</div><div class="alert-close"><button type="button" class="close" datade-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
        }
    }

    public function report()
    {
        $user = Utils::getUser();
        return view('addReport', ['user'=>$user]);
    }

    public function reportPost(Request $req){
        $saveName = uniqid().'_'.time().'_'.mt_rand(000000,999999).'.xlsx';
        return Excel::download(new CustomerExport, $saveName);

    }

    public function mailStatus(Request $req){
        $id = $req->id;
        $user = Utils::getUser();
        $dform = Utils::getForm($id);
        $name = $dform->firstname.' '.$dform->middle_name.' '.$dform->surname;
        $message = 'kindly note that your loan application is currently being processed. Please reach out to risk@swisscredit.ng for further enquiry.';
        $second = DB::table('customer2s')->where('customer_id', $dform->id)->first();
        $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
        $email = $req->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        DB::table('customer2s')->where('id', $second->id)->update(['status_notice'=>'sent','updated_at'=>Carbon::now()]);
        Utils::createActivity($user->email, 'You mailed out a loan status', '<i class="dripicons dripicons-mail bg-soft-secondary"></i>', 'You mailed out a loan status');
        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Status sent to customer.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function mailStatusDec(Request $req){
        $id = $req->id;
        $user = Utils::getUser();
        $dform = Utils::getForm($id);
        $name = $dform->firstname.' '.$dform->middle_name.' '.$dform->surname;
        $message = 'We acknowledge the receipt of your application for a credit facility.<br><br>At the moment, we are unable to proceed with your request, kindly click on this link => https://loan.swisscredit.ng for our other loan product.';
        $second = DB::table('customer2s')->where('customer_id', $dform->id)->first();
        $subject = 'SWISS CREDIT LOAN APPLICATION FEEDBACK';
        $email = $req->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        DB::table('customer2s')->where('id', $second->id)->update(['status_notice'=>'sent','updated_at'=>Carbon::now()]);
        Utils::createActivity($user->email, 'You mailed out a loan status', '<i class="dripicons dripicons-mail bg-soft-secondary"></i>', 'You mailed out a loan status');
        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Status sent to customer.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function myAccess(){
        $user = Utils::getUser();
        if($user->role == 'internal control' || $user->role == 'director'){
            $permissions = DB::table('folder_permissions')->where(['director'=>'approved', 'internal_control'=>'approved', 'status'=>'not used'])->orderBy('id', 'asc')->get();
            return view('myAccess', ['user'=>$user, 'permissions'=>$permissions]);
        }else if($user->role == 'credit risk' || $user->role == 'underwriter' || $user->role == 'risk' || $user->role == 'monitoring and compliance'){
            $permissions = DB::table('folder_permissions')->where('staff', $user->email)->where('status', 'not used')->orderBy('id', 'asc')->get();
            return view('myAccess', ['user'=>$user, 'permissions'=>$permissions]);
        }else {
            return view('errors.401');
        }
    }

    public function myAccessPending(){
        $user = Utils::getUser();
        if($user->role != 'internal control' && $user->role != 'director'){
            return view('errors.401');
        }else {
            $permissions = DB::table('folder_permissions')->where('director','pending')->orWhere('internal_control', 'pending')->orderBy('id', 'asc')->get();
            return view('myAccessPending', ['user'=>$user, 'permissions'=>$permissions]);
        }
    }

    public function referrals(){
        $user = Utils::getUser();
        $posts = DB::table('referrals')->limit(400)->orderBy('id','desc')->get();
        return view('referrals', ['user'=>$user, 'posts'=>$posts]);
    }

    public function addReferral(){
        $user = Utils::getUser();
        if($user->role != 'it'){
            return view('errors.401');
        }else {
            return view('addReferral', ['user'=>$user]);
        }
    }

    public function addReferralPost(Request $req){
        $user = Utils::getUser();
        $req->validate([
            'email' => 'required|unique:referrals',
            'phone' => 'required|unique:referrals',
        ]);
        $dref = DB::table('referrals')->insertGetId([
            'name'=>$req->name,
            'email'=>$req->email,
            'phone'=>$req->phone,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        $ref = DB::table('referrals')->where('id', $dref)->first();
        $code = "SC".str_pad($ref->id, 3, '0', STR_PAD_LEFT);
        DB::table('referrals')->where('id', $ref->id)->update([
            'code'=>$code,
            'updated_at'=>Carbon::now(),
        ]);
        Utils::createActivity($user->email, 'You added a referral', '<i class="lni-users bg-soft-success"></i>', 'You added a referral with the code <b><q>'.$code.'</q></b> to the system.');
        $name = $ref->name;
        $message = 'Hello '.ucwords($name).', <br><br>Your referral code is <b>'.$code.'</b>';
        $subject = 'SWISS STAFF REFERRAL SYSTEM';
        $email = $ref->email;
        Mail::to($email)->send(new SendMailerCustomer($name, $message, $subject));
        return redirect('/referrals')->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Your referral has been created successfully.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }

    public function viewReferral($code){
        $user = Utils::getUser();
        $ref = DB::table('referrals')->where('code', $code)->first();
        $posts = DB::table('customers')->where('referral_code', $ref->code)->limit(400)->orderBy('id','desc')->get();
        return view('viewReferral', ['user'=>$user, 'ref'=>$ref, 'posts'=>$posts]);
    }

    public function myTasks(){
        $user = Utils::getUser();
        return view('myTasks', compact('user'));
    }

    public function allTasks(){
        $dtasks = Utils::getSpecificTask();
        echo count($dtasks);
    }

    public function swissAgents(Request $req){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['name', 'email', 'phone', 'bank', 'account_number', 'referral_code', 'username'];
            $posts = DB::connection('mysql2')->table('agents')->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('swissAgents', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::connection('mysql2')->table('agents')->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('swissAgents', ['user'=>$user, 'posts'=>$posts]);
        }
    }

    public function swissPayments(Request $req){
        $user = Utils::getUser();
        $posts = DB::table('customers')->where('customers.swiss_club_agent', '!=', null)->where(['customers.status'=>'approved', 'commission'=>null])->rightJoin('offers','customers.id', '=', 'offers.d_form')->orderBy('customers.id', 'asc')->get(['customers.email', 'offers.id', 'offers.amount_recommended', 'offers.d_form', 'customers.swiss_club_agent']);
        return view('scpayments', ['user'=>$user, 'posts'=>$posts]);
    }

    public function payAgents($id){
        $customer = DB::table('customers')->where('id', $id)->first();
        $offer = DB::table('offers')->where('d_form', $id)->first();
        $agent = DB::connection('mysql2')->table('agents')->where('referral_code', $customer->swiss_club_agent)->first();
        DB::connection('mysql2')->table('commission_paid')->insert([
            'paid_to'=>$agent->email,
            'loan_paid_for'=>$id,
            'amount'=>$offer->amount_recommended / 100,2,
            'created_at'=>Carbon::now(),
            'updated_at'=>Carbon::now(),
        ]);
        DB::table('customers')->update(['commission'=>'paid']);
        $message = 'You just received your commission of <b>N'.number_format($offer->amount_recommended / 100,2).'</b> for referring <b>'.strtoupper($customer->firstname.' '.$customer->middle_name.' '.$customer->surname.'</b> with the loan offer of <b>N'.number_format($offer->amount_recommended,2).'</b>');
        $subject = 'COMMISSION PAYMENT';
        Mail::to($agent->email)->send(new SendMailerCustomer($agent->name, $message, $subject));
        return redirect()->back()->with('message', '<div class="alert icon-custom-alert alert-outline-success b-round fade show" role="alert"><i class="dripicons dripicons-checkmark alert-icon"></i><div class="alert-text"><strong>Great!</strong> Agent commission paid successfully.</div><div class="alert-close"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true"><i class="lni-close text-danger"></i></span></button></div></div>');
    }
    
    public function viewAgent(Request $req, $code){
        $user = Utils::getUser();
        if($req->search != null && $req->search != ''){
            $search = $req->search;
            $requestData = ['firstname', 'middle_name', 'surname', 'maiden_name', 'bvn', 'gender', 'phone_no', 'mobile_no', 'office_no', 'email', 'official_email', 'address', 'landmark', 'lga_of_residence', 'state', 'emp_status', 'current_employer', 'current_employer_address', 'landmark_office', 'lga_office', 'state_office', 'employer_number', 'work_email', 'pension_number', 'tid', 'salary'];
            $posts = DB::table('customers')->where('referral_code', $code)->where(function($q) use($requestData, $search) {
                        foreach ($requestData as $field)
                           $q->orWhere($field, 'like', "%{$search}%");
                })->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('swissAgent', ['user'=>$user, 'posts'=>$posts]);
        }else{
            $posts = DB::table('customers')->where('referral_code', $code)->limit($req->num ?? 50)->orderBy('id','desc')->get();
            return view('swissAgent', ['user'=>$user, 'posts'=>$posts]);
        }
    }

}


