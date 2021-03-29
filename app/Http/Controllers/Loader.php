<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Task;
use App\Http\Controllers\Globals as Utils;
use App\Notifications\PushDemo;
use App\Folder;
use App\Upload;
use File;
use Response;
use App\Customer;
use App\Customer2;
use Hash;
use App\TaskDone;
use App\Signature;
use App\Comment;
use PDF;
use App\Offer;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailerCustomer;
use Notification;
use App\Notifications;
use App\Guarantor;
use App\GuarantorSign;
use App\Checklist;
use App\FilledChecklist;
use Auth;
use App\Link;
use App\Approval;
use Excel;
use App\Exports\CustomerExport;
use Gravatar;
use App\Trash;
use App\FolderPermission;
use App\Referral;
use DateTime;
use Illuminate\Support\Facades\DB;

class Loader extends Controller
{

    public static function getStatus($param){
        $user = Utils::getUser();
        $second = Utils::getCustomer2nd($param->id);
        $status = '';
        if($second->credit_risk == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-warning">Credit Risk</span>';
        elseif($second->credit_risk == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Credit Risk Declined</span><br/> <b>Reason:</b> '.$second->reason;
        elseif($second->underwriters == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-warning">Underwriter</span>';
        elseif($second->underwriters == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Underwriter Declined</span><br/> <b>Reason:</b> '.$second->reason;
        elseif($second->risk == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-warning">Risk</span>';
        elseif($second->risk == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Risk Declined</span><br/> <b>Reason:</b> '.$second->reason;
        elseif($second->customer == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-pink">Customer</span>';
        elseif($second->customer == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Customer Declined</span><br/> <b>Reason:</b>'. $second->reason;
        elseif($second->operations == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-purple">Operations</span>';
        elseif($second->operations == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Operations Declined</span><br/> <b>Reason:</b>'. $second->reason;
        elseif($second->finance == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-black">Finance</span>';
        elseif($second->finance == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Finance Declined</span>
        <br/> <b>Reason:</b>'. $second->reason;
        elseif($second->internal_control == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-info">Internal Control</span>';
        elseif($second->internal_control == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Internal Control Declined</span><br/> <b>Reason:</b>'. $second->reason;
        elseif($second->status == 'pending' || $second->status == null)
            $status .= '<span class="badge badge-md badge-boxed badge-secondary">Director</span>';
        elseif($second->status == 'declined')
            $status .= '<span class="badge badge-md badge-boxed badge-danger">Director Declined</span><br/> <b>Reason:</b>'. $second->reason;
        else
            $status .= '<span class="badge badge-md badge-boxed badge-success">Approved</span>';
        return $status;
    }

    public static function getAction($param){
        $user = Utils::getUser();
        $checklists = DB::table('checklists')->where('role', $user->role)->orderBy('id', 'desc')->get();
        $second = Utils::getCustomer2nd($param->id);
        $action = '<div class="d-inline-block border-right border-gray p-2"><a class="text-danger p-2" target="_blank" href="/customers/'.$param->id.'/preview"><i class="fas fa-file-pdf"></i></a>';
        if($second->risk == 'approved'){
            $offer = Utils::getOffer($param->id);
            if($offer != null){
                $action .= "<div class='d-inline-block border-left border-gray p-2'><a class='btn btn-danger btn-small' href='/offers/".$offer->id."/offer' target='_blank'><i class='fas fa-file-pdf'></i> Offer Letter</a></div><div class='d-inline-block border-left border-gray p-2'><a class='btn btn-primary btn-small' href='/offers/".$offer->id."/summary' target='_blank'><i class='fas fa-file-word'></i> Transaction Summary</a></div>";
                if(Utils::getGuarantor($offer->id) != null){
                    foreach(Utils::getGuarantor($offer->id) as $guarantor){
                        if($guarantor->firstname != null){
                            $action .= '<div class="d-inline-block border-left border-gray p-2"><a class="btn btn-success btn-small" href="offers/view/guarantors/'.$guarantor->id.'" target="_blank"><i class="dripicons dripicons-flag"></i> Guarantor: '.strtoupper($guarantor->firstname.' '.$guarantor->middle_name.' '.$guarantor->surname).'</a></div>';
                        }
                    }

                }
            }
        }
        $action .= "</div><div class='d-inline-block p-2'>";
        if($second->credit_risk == 'pending' && $user->role == 'credit risk'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }else if($second->credit_risk == 'declined' && $user->role == 'credit risk'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }else if($second->credit_risk == 'approved' && $second->underwriters == 'pending' && $user->role == 'underwriter'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }elseif($second->credit_risk == 'approved' && $second->underwriters == 'declined' && $user->role == 'underwriter'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }else if($second->underwriters == 'approved' && $second->risk == 'pending' && $user->role == 'risk'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }elseif($second->underwriters == 'approved' && $second->risk == 'declined' && $user->role == 'risk'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }elseif($second->customer == 'pending'){
        }elseif($second->customer == 'declined') {
        }elseif($second->customer == 'approved' && $second->operations == 'pending' && $user->role == 'operations'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }elseif($second->customer == 'approved' && $second->operations == 'declined' && $user->role == 'operations'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }elseif($second->operations == 'approved' && $second->finance == 'pending' && $user->role == 'finance'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }elseif($second->finance == 'approved' && $second->internal_control == 'pending' && $user->role == 'internal control'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }elseif($second->finance == 'approved' && $second->internal_control == 'declined' && $user->role == 'internal control'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }elseif($second->internal_control == 'approved' && $second->status == 'pending' && $user->role == 'director'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }elseif($second->internal_control == 'approved' && $second->status == null && $user->role == 'director'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$param->id)."><i class='lni-close'></i></a>";
        }elseif($second->internal_control == 'approved' && $second->status == 'declined' && $user->role == 'director'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$param->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }
        $action .= "<a class='btn btn-success btn-small' href='/customers/comments/".$param->id."' target='_blank'>COMMENTS</a>";
        if(DB::table('filled_checklists')->where('form', $param->id)->count() > 0){
            $action .= "<a class='btn btn-secondary btn-small ml-2' href='/customers/recommendation/".$param->id."' target='_blank'>Recommendation Sheet</a>";
        }
        if($param->folder != null){
            $folder = Utils::getFolder($param->folder);
            $action .= "<a class='btn btn-primary btn-small ml-2' href='/file-manager/view/".$folder->slug."' target='_blank'>View Folder</a>";
        }
        $action .= "</div>";
        return $action;
    }

    public static function getDocs($param){
        $user = Utils::getUser();
        $second = Utils::getCustomer2nd($param->id);
        $documents = '<div class="d-inline-block border-right border-gray p-2"><a class="text-danger p-2" target="_blank" href="/customers/'.$param->id.'/preview"><i class="fas fa-file-pdf"></i></a>';
        if($second->risk == 'approved'){
            $offer = Utils::getOffer($param->id);
            if($offer != null){
                $documents .= '<div class="d-inline-block border-left border-gray p-2"><a class="btn btn-danger btn-small" href="/offers/'.$offer->id.'/offer" target="_blank"><i class="fas fa-file-pdf"></i> Offer Letter</a></div><div class="d-inline-block border-left border-gray p-2"><a class="btn btn-primary btn-small" href="/offers/'.$offer->id.'/summary" target="_blank"><i class="fas fa-file-word"></i> Transaction Summary</a></div>';
                if($second->status_notice == null){
                    $documents .= "<a class='btn btn-warning btn-small performing text-white' data-target=".url('/mailprocessed/'.$param->id)."><i class='dripicons dripicons-forward'></i> Mail to Customer</a>";
                }
                if(Utils::getGuarantor($offer->id) != null){
                    foreach(Utils::getGuarantor($offer->id) as $guarantor){
                        if($guarantor->firstname != null){
                            $documents .= '<div class="d-inline-block border-left border-gray p-2"><a class="btn btn-success btn-small" href="offers/view/guarantors/'.$guarantor->id.'" target="_blank"><i class="dripicons dripicons-flag"></i> Guarantor: '.strtoupper($guarantor->firstname.' '.$guarantor->middle_name.' '.$guarantor->surname).'</a></div>';
                        }
                    }
                }
            }
        }
        $documents .= '</div><div class="d-inline-block p-2"><a class="btn btn-success btn-small" href="/customers/comments/'.$param->id.'" target="_blank">COMMENTS</a>';
        if($user->role == "monitoring and compliance" && $second->compliance == "pending")
            $documents .= "<a class='btn btn-primary btn-small ml-2 mr-2 text-white p-2 performing' data-target=".url('/approve/compliance/'.$param->id)."><i class='dripicons dripicons-checkmark'></i> Approve</a>";
        if($second->credit_risk == 'approved'){
            $documents .= '<a class="btn btn-secondary btn-small ml-2" href="/customers/recommendation/'.$param->id.'" target="_blank">Recommendation Sheet</a>';
        }
        $action ='';
        if($param->folder != null){
            $folder = Utils::getFolder($param->folder);
            $action .= "<a class='btn btn-primary btn-small ml-2' href='/file-manager/view/".$folder->slug."' target='_blank'>View Folder</a>";
        }
        if($param->folder != null){
            $folder = Utils::getFolder($param->folder);
            $documents .= "<a class='btn btn-primary btn-small ml-2' href='/file-manager/view/".$folder->slug."' target='_blank'>View Folder</a>";
        }
        $documents .= '</div>';
        return $documents;
    }

    public static function getTurn($param){
        $second = Utils::getCustomer2nd($param->id);
        $datetime1 = new DateTime(date('Y-m-d h:m:i A', strtotime($second->created_at)));
        $datetime2 = new DateTime(date('Y-m-d h:m:i A', strtotime($second->updated_at)));
        $interval = $datetime1->diff($datetime2);
        $turn = $interval->format('%h')." Hours ".$interval->format('%m')." Minutes";
        return $turn;
    }

    public static function getStaffName($param){
        $name = "<img src='";
        if($param->passport != null)
            $name .= asset($param->passport);
        else
            $name .= Gravatar::get($param->email);
        $name .= "' alt='' class='thumb-sm rounded-circle mr-2'>".ucwords($param->name);
        return $name;
    }

    public static function getStaffStatus($param){
        $status = '';
        if($param->status == null)
            $status .= "<span class='badge badge-md badge-boxed badge-soft-success'>Active</span>";
        else
            $status .= "<span class='badge badge-md badge-boxed badge-soft-danger'>Restricted</span>";
        return $status;
    }

    public static function getStaffAction($param){
        $action = '';
        if($param->status == null)
            $action .= "<a class='text-danger p-2' href='/staffs/".$param->id."'><i class='lni-close'></i></a>";
        else
            $action .= "<a class='text-success p-2' href='/staffs/".$param->id."'><i class='dripicons dripicons-checkmark'></i></a>";
        return $action;
    }   

    public static function getOfferAction($param){
        $form = Utils::getForm($param->d_form);
        $action = "<div class='d-inline-block border-right border-gray p-2'><a class='btn btn-danger btn-small' href='/offers/".$param->id."/offer' target='_blank'><i class='fas fa-file-pdf'></i> Offer Letter</a></div><div class='d-inline-block border-right border-gray p-2'><a class='btn btn-primary btn-small' href='/offers/".$param->id."/summary' target='_blank'><i class='fas fa-file-word'></i> Transaction Summary</a></div><div class='d-inline-block p-2 border-right border-gray p-2'><a class='btn btn-warning btn-small performing text-white' data-target=".url('/offers/sender/'.$param->id)."><i class='dripicons dripicons-forward'></i> Mail to Customer</a></div><div class='d-inline-block p-2'><a class='btn btn-pink btn-small' href='/offers/".$param->id."/guarantor'><i class='dripicons dripicons-forward'></i> Request Guarantor </a></div>";
        if($form->operations != 'approved'){
            $action .= "<div class='d-inline-block border-left border-gray p-2'><a class='btn btn-info btn-small' href='/offers/".$param->id."/edit'><i class='dripicons dripicons-document-edit'></i> Edit</a></div>";
        }
        if(Utils::getSignature($param->id) !== FALSE){
            $action .= "<div class='d-inline-block border-left border-gray p-2'><a class='btn btn-secondary btn-small' href='/offers/".$param->id."/signatures'><i class='dripicons dripicons-anchor'></i> Customer Signatures</a></div>";
        }
        if(Utils::getGuarantor($param->id) != null){
            foreach(Utils::getGuarantor($param->id) as $guarantor){
                if($guarantor->firstname != null){
                    $action .= "<div class='d-inline-block border-left border-gray p-2'><a class='btn btn-success btn-small' href='offers/view/guarantors/".$guarantor->id."' target='_blank'><i class='dripicons dripicons-flag'></i> Guarantor: ".strtoupper($guarantor->firstname.' '.$guarantor->middle_name.' '.$guarantor->surname) ."</a></div>";
                }
            }
        }
        return $action;
    }

    public static function getOfferStatus($param){
        $status = '';
        if($param->status == 'pending')
            $status .= '<span class="badge badge-md badge-boxed badge-soft-warning">Pending Customer Approval</span>';
        else
            $status .= '<span class="badge badge-md badge-boxed badge-soft-success">Customer Approved</span>';
        return $status;
    }
}
