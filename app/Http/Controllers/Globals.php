<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Gravatar;
use Illuminate\Pagination\LengthAwarePaginator;
use DateTime;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Globals extends Controller
{
    public static function getUser(){
        return Auth::user();
    }

    public static function getNotifications($user){
    	return DB::table('notifications')->where(['staff'=>$user->email, 'status'=>'not read'])->orderBy('id','desc')->limit(3)->get();
    }

    public static function getAllNotifications($user){
    	return DB::table('notifications')->where(['staff'=>$user->email, 'status'=>'not read'])->orderBy('id','desc')->limit(100)->get();
    }

    public static function convertTime($datetime, $full = false) {
	    $now = new DateTime;
	    $ago = new DateTime($datetime);
	    $diff = $now->diff($ago);

	    $diff->w = floor($diff->d / 7);
	    $diff->d -= $diff->w * 7;

	    $string = array(
	        'y' => 'year',
	        'm' => 'month',
	        'w' => 'week',
	        'd' => 'day',
	        'h' => 'hour',
	        'i' => 'minute',
	        's' => 'second',
	    );
	    foreach ($string as $k => &$v) {
	        if ($diff->$k) {
	            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
	        } else {
	            unset($string[$k]);
	        }
	    }

	    if (!$full) $string = array_slice($string, 0, 1);
	    return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	public static function getActivities($user){
		return DB::table('activities')->where('staff', $user->email)->orderBy('id', 'desc')->limit(100)->get();
	}

	public static function getTasks($user){
		return DB::table('tasks')->whereJsonContains('users', $user->email)->orderBy('id', 'desc')->limit(100)->get();
		
	}

	public static function userPartTask($user, $task){
		return DB::table('tasks')->whereJsonContains('users', $user->email)->where('id', $task)->exists();
	}

	public static function getTask($id){
		return DB::table('tasks')->find($id);
	}

	public static function getCreatedTasks($user){
		return DB::table('tasks')->where('staff', $user->email)->limit(100)->get();
	}

	public static function getColor($status){
		$return = '';
		if($status < 10){
			$return .= 'bg-danger';
		}else if($status > 9 && $status < 30){
			$return .= 'bg-warning';
		}else if($status > 29 && $status < 50){
			$return .= 'bg-secondary';
		}else if($status > 29 && $status < 75){
			$return .= 'bg-primary';
		}else if($status > 74 && $status < 90){
			$return .= 'bg-info';
		}else if($status > 89 && $status < 100){
			$return .= 'bg-light';
		}else if($status > 99){
			$return .= 'bg-success';
		}
		return $return;
	}

	public static function getColor2($status){
		$return = '';
		if($status < 10){
			$return .= '-danger';
		}else if($status > 9 && $status < 30){
			$return .= '-warning';
		}else if($status > 29 && $status < 50){
			$return .= '-secondary';
		}else if($status > 29 && $status < 75){
			$return .= '-primary';
		}else if($status > 74 && $status < 90){
			$return .= '-info';
		}else if($status > 89 && $status < 100){
			$return .= '-light';
		}else if($status > 99){
			$return .= '-success';
		}
		return $return;
	}

	public static function getColorReverse($status){
		$return = '';
		if($status < 10){
			$return .= 'bg-success';
		}else if($status > 9 && $status < 30){
			$return .= 'bg-light';
		}else if($status > 29 && $status < 50){
			$return .= 'bg-info';
		}else if($status > 29 && $status < 75){
			$return .= 'bg-primary';
		}else if($status > 74 && $status < 90){
			$return .= 'bg-secondary';
		}else if($status > 89 && $status < 100){
			$return .= 'bg-warning';
		}else if($status > 99){
			$return .= 'bg-danger';
		}
		return $return;
	}

	public static function getTaskPercent($user){
		$mine = DB::table('tasks')->whereJsonContains('users', $user->email)->count();
		$all = DB::table('tasks')->count();
		$return = 0;
		if($mine > 0 && $all > 0){
			$return += ($mine / $all) * 100;
		}
		return $return;
	}

	public static function getActivityPercent($user){
		$mine = DB::table('activities')->where('staff', $user->email)->count();
		$all = DB::table('activities')->count();
		$return = 0;
		if($mine > 0 && $all > 0){
			$return += ($mine / $all) * 100;
		}
		return $return;
	}

	public static function getComments($user){
		return DB::table('comments')->where('staff', $user->email)->orderBy('id', 'desc')->get();
	}

	public static function getCommentPercent($user){
		$mine = DB::table('comments')->where('staff', $user->email)->count();
		$all = DB::table('comments')->count();
		$return = 0;
		if($mine > 0 && $all > 0){
			$return += ($mine / $all) * 100;
		}
		return $return;
	}

	public static function getCustomers($user, $limit = null){
		return 
			$limit == null ? 
				DB::table('customers')->rightJoin('customer2s','customers.id', '=', 'customer2s.customer_id')->where('account_officer', $user->email)->orderBy('customers.id', 'desc')->get() 
					: 
						DB::table('customers')->rightJoin('customer2s','customers.id', '=', 'customer2s.customer_id')->where('account_officer', $user->email)->limit($limit)->orderBy('customers.id', 'desc')->get();
	}

	public static function getAllCustomers($limit = null){
		return 
			$limit == null ? 
				DB::table('customers')->rightJoin('customer2s','customers.id', '=', 'customer2s.customer_id')->orderBy('customers.id', 'desc')->get() 
					: 
						DB::table('customers')->rightJoin('customer2s','customers.id', '=', 'customer2s.customer_id')->limit($limit)->orderBy('customers.id', 'desc')->get();
	}

	public static function getCustomer2nd($id){
		return DB::table('customer2s')->where('customer_id', $id)->first();
	}

	public static function getCustomerPercent($user){
		$mine = DB::table('customers')->where('account_officer', $user->email)->orderBy('customers.id', 'desc')->count();
		$all = DB::table('customers')->count();
		$return = 0;
		if($mine > 0 && $all > 0){
			$return += ($mine / $all) * 100;
		}
		return $return;
	}

	public static function getFolders($user){
		return DB::table('folders')->where('staff', $user->email)->orderBy('id', 'desc')->get();
	}

	public static function getAllFolders(){
		return DB::table('folders')->orderBy('id', 'desc')->get();
	}

	public static function getFolderPercent($user){
		$mine = DB::table('folders')->where('staff', $user->email)->orderBy('id', 'desc')->count();
		$all = DB::table('folders')->count();
		$return = 0;
		if($mine > 0 && $all > 0){
			$return += ($mine / $all) * 100;
		}
		return $return;
	}

	public static function getTeam($user){
		return DB::table('users')->where('team', $user->team)->orderBy('id', 'desc')->get();
	}

	public static function getUserByEmail($email){
		return DB::table('users')->where('email', $email)->first();
	}

	public static function getTaskDone($user, $task){
		return DB::table('task_dones')->where(['user'=>$user->email, 'task'=>$task])->exists();
	}

	public static function getFileIcons($file){
		$dfile = explode('.', $file);
		$key = count($dfile)-1;
		$ext = $dfile[$key];
		$return = '';
		if($ext == 'doc' || $ext == 'docx'){
			$return .= '<i class="fas fa-file-word text-primary"></i>';
		}else if($ext == 'pdf'){
			$return .= '<i class="fas fa-file-pdf text-danger"></i>';
		}else if($ext == 'ar' || $ext == 'a' || $ext == 'cpio' || $ext == 'shar' || $ext == 'lbr' || $ext == 'iso' || $ext == 'mar' || $ext == 'sbx' || $ext == 'tar' || $ext == '7z' || $ext == 'zip' || $ext == 'zipx'){
			$return .= '<i class="fas fa-file-archive text-muted"></i>';
		}else if($ext == 'xlsx' || $ext == 'xlsm' || $ext == 'xltx' || $ext == 'xltm' || $ext == 'xls'){
			$return .= '<i class="fas fa-file-excel text-success"></i>';
		}else if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'tiff' || $ext == 'ico'){
			$return .= '<i class="fas fa-file-image text-warning"></i>';
		}else if($ext == 'ppt' || $ext == 'pptx'){
			$return .= '<i class="fas fa-file-powerpoint text-danger"></i>';
		}else{
			$return .= '<i class="fas fa-file"></i>';
		}
		return $return;
	}

	public static function getFileName($file){
		$dfile = explode('.', $file);
		$name = $dfile[0];
		return $name;
	}

	public static function dpaginate($param1,$param2){
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$itemCollection =  collect($param1);
		$perPage = $param2;
		$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
		$paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
		$paginatedItems->setPath(url()->full());
		return $paginatedItems;
	}

	public static function convertBytes($bytes) {
		$convert1 = $bytes / 1073741824;
		$convert2 = $bytes / 1048576;
		$convert3 = $bytes / 1024;
		$return = 0;
		if(round($convert1) < 1){
			if(round($convert2) < 1){
				$return += $convert3;
			}else {
				$return += $convert2;
			}
		}else {
			$return += $convert1;
		}
		return $return;
	}

	public static function getStorageSymbol($bytes) {
		$convert1 = $bytes / 1073741824;
		$convert2 = $bytes / 1048576;
		$convert3 = $bytes / 1024;
		$return = '';
		if(round($convert1) < 1){
			if(round($convert2) < 1){
				$return .= 'KB';
			}else {
				$return .= 'MB';
			}
		}else {
			$return .= 'GB';
		}
		return $return;
	}

	public static function removeSpaces($name){
		return str_replace(' ', '-', $name);
	}

	public static function previewer($file){
		$dfile = explode('.', $file);
		$key = count($dfile)-1;
		$ext = $dfile[$key];
		$return = '';
		if($ext == 'txt'){
			$return .= asset($file);
		}else if($ext == 'doc'){
			$return .= "https://view.officeapps.live.com/op/view.aspx?src=".env('APP_URL').'/'.$file;
		}else if($ext == 'docx'){
			$return .= "https://view.officeapps.live.com/op/view.aspx?src=".env('APP_URL').'/'.$file;
		}else if($ext == 'xls'){
			$return .= "https://view.officeapps.live.com/op/view.aspx?src=".env('APP_URL').'/'.$file;
		}else if($ext == 'xlsx'){
			$return .= "https://view.officeapps.live.com/op/view.aspx?src=".env('APP_URL').'/'.$file;
		}else if($ext == 'pdf'){
			$return .= env('APP_URL').'/'.$file;
		}else{
			$return .= 'nil';
		}
		return $return;
	}

	public static function checkBvn($number){
	    $curl = curl_init();
	    curl_setopt_array($curl, array(
	      CURLOPT_URL => "https://api.paystack.co/bank/resolve_bvn/".$number,
	      CURLOPT_RETURNTRANSFER => true,
	      CURLOPT_HTTPHEADER => [
	        "authorization: Bearer sk_live_002b1f4498f024b029e17c3b04c4e1017efc96b5",
	        "content-type: application/json",
	        "cache-control: no-cache",
	      ],
	    ));
	    $response = curl_exec($curl);
	    return json_decode($response);

  	}

  	public static function getForm($id){
  		return DB::table('customers')->rightJoin('customer2s','customers.id', '=', 'customer2s.customer_id')->where('customers.id', $id)->first();
  	}

  	public static function getRepayDate($day, $timestamp){
  		$totalDays = date('t', strtotime($timestamp));
  		$return = $day;
  		if($day > $totalDays){
  			$return = $totalDays;
  		}
  		return $return;
  	}

  	public static function getTenorLastDay($tenor, $time){
  		$dten = $tenor*1;
  		return date('Y-m-d h:i:s', strtotime("+ ".$dten." months", strtotime($time)));
  	}

  	public static function encrypt_decrypt($action, $string)
	{
		$output = false;
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'Pintop Technologies';
		$secret_iv = 'Pintop Technologies';
		$key = hash('sha256', $secret_key);
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ($action == 'encrypt'){
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = base64_encode($output);
		}else{
			if ($action == 'decrypt'){
			  $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
			}
		}
		return $output;
	}

	public static function removeSpacesNTabs($string){
		return preg_replace( "/\r|\n/", "", preg_replace( "/\t/", "",$string));
	}

	public static function removeHeader($string){
		return str_replace('<?xml version="1.0"?><!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">', '', $string);
	}

	public static function getSignature($offer){
		$signature = DB::table('signatures')->where('offer', $offer)->first();
		if(isset($signature) && $signature->id != null){
			return $signature;
		}else {
			return false;
		}
	}

	public static function createActivity($user, $title, $icon, $message){
		DB::table('activities')->insert([
			'icon'=>$icon,
			'title'=>$title,
			'message'=>$message,
			'staff'=>$user,
			'created_at'=>Carbon::now(),
			'updated_at'=>Carbon::now(),
		]);
	}


	public static function createNotification($icon, $title, $message, $user, $link, $bg, $uniqid){
		DB::table('notifications')->insert([
			'icon'=>$icon,
			'title'=>$title,
			'message'=>$message,
			'staff'=>$user,
			'link'=>$link,
			'bg'=>$bg,
			'uniqid'=>$uniqid,
			'created_at'=>Carbon::now(),
			'updated_at'=>Carbon::now(),
		]);
	}

	public static function getGuarantor($offer){
		return DB::table('guarantors')->where('d_offer', $offer)->orderBy('id', 'asc')->get();
	}

	public static function getOffer($d_form){
		return DB::table('offers')->where('d_form', $d_form)->first();
	}

	public static function numtowords($num){
        $decones = array('01' => "One",'02' => "Two",'03' => "Three",'04' => "Four",'05' => "Five",'06' => "Six",'07' => "Seven",'08' => "Eight",'09' => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $ones = array(
            0 => " ",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen"
        );
        $tens = array(
            0 => "",
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety"
        );
        $hundreds = array(
            "Hundred",
            "Thousand",
            "Million",
            "Billion",
            "Trillion",
            "Quadrillion"
        );
        $num = number_format($num,2,".",",");
        $num_arr = explode(".",$num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",",$wholenum));
        krsort($whole_arr);
        $rettxt = "";
        foreach($whole_arr as $key => $i){
            if($i < 20){
                $rettxt .= $ones[$i];
            }
            elseif($i < 100){
                $rettxt .= $tens[substr($i,0,1)];
                $rettxt .= " ".$ones[substr($i,1,1)];
            }
            else{
                $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0];
                $rettxt .= " ".$tens[substr($i,1,1)];
                $rettxt .= " ".$ones[substr($i,2,1)];
            }
            if($key > 0){
                $rettxt .= " ".$hundreds[$key]." ";
            }

        }
        $rettxt = $rettxt." Naira";

        if($decnum > 0){
            $rettxt .= " and ";
            if($decnum < 20){
                $rettxt .= $decones[$decnum];
            }
            elseif($decnum < 100){
                $rettxt .= $tens[substr($decnum,0,1)];
                $rettxt .= " ".$ones[substr($decnum,1,1)];
            }
            $rettxt = $rettxt." Kobo";
        }
        return $rettxt;

	}

	public static function countOffers($id){
	    return DB::table('offers')->where('d_form', $id)->count();
	}

	public static function getChecklist($checklist,$form){
		return DB::table('filled_checklists')->where(['checklist'=>$checklist,'form'=>$form, 'value'=>'yes'])->exists();
	}

	public static function getChecklistNo($checklist,$form){
		return DB::table('filled_checklists')->where(['checklist'=>$checklist,'form'=>$form, 'value'=>'no'])->exists();
	}

	public static function getExist($bvn){
		return DB::table('customers')->whereDate('created_at', date('Y-m-d'))->where('bvn', $bvn)->count();
	}

	public static function getFirstLetterCode($name){
	    $words = explode(" ", $name);
        $acronym = "";
        for ($i=0;$i<=count($words)-1;$i++) {
          $acronym .= substr($words[$i],0,1);
        }
        return $acronym;
	}

	public static function timeAgo($time1, $time2) {
	    $datetime1 = new DateTime($time1);
        $datetime2 = new DateTime($time2);
        $interval = $datetime1->diff($datetime2);
        return $interval->format('%h')." Hours ".$interval->format('%i')." Minutes".$interval->format('%s')." Seconds";
	}

	public static function getRequests($url){
		$cURLConnection = curl_init();
		curl_setopt($cURLConnection, CURLOPT_URL, $url);
		curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
		$dataList = curl_exec($cURLConnection);
		curl_close($cURLConnection);
		$jsonArrayResponse = json_decode($dataList);
		return $jsonArrayResponse;
	}

	public static function getSerial($param){
		$counts = DB::table('customers')->where('bvn', $param->bvn)->where('status', 'approved')->count();
		if($counts > 0){
			$add = ($counts*1) + 1;
			return "SC/".strtoupper(self::getFirstLetterCode(ucwords($param->firstname." ".$param->middle_name." ".$param->surname)))."/".str_pad($param->id, 5, '0', STR_PAD_LEFT)."/VOL ".$add;
		}else {
			return "SC/".strtoupper(self::getFirstLetterCode(ucwords($param->firstname." ".$param->middle_name." ".$param->surname)))."/".str_pad($param->id, 5, '0', STR_PAD_LEFT);
		}
	}

	public static function getFolder($param){
		return DB::table('folders')->where('id', $param)->orWhere('slug', $param)->first();
	}

	public static function getDeletedBy($param){
		$trash = DB::table('trashes')->where('the_id', $param)->first();
		$user = self::getUserByEmail($trash->user);
		return $user;
	}

	public static function getDeletedDate($param){
        DB::disableQueryLog();
		$trash = Trash::where('the_id', $param)->first();
		return $trash;
	}

	public static function getAction2($dform,$second,$user){
		$documents = '<div class="d-inline-block border-right border-gray p-2"><a class="text-danger p-2" target="_blank" href="/customers/'.$dform->id.'/preview"><i class="fas fa-file-pdf"></i></a>';
        if($second->risk == 'approved'){
            $offer = self::getOffer($dform->id);
            if($offer != null){
                $documents .= '<div class="d-inline-block border-left border-gray p-2"><a class="btn btn-danger btn-small" href="/offers/'.$offer->id.'/offer" target="_blank"><i class="fas fa-file-pdf"></i> Offer Letter</a></div><div class="d-inline-block border-left border-gray p-2"><a class="btn btn-primary btn-small" href="/offers/'.$offer->id.'/summary" target="_blank"><i class="fas fa-file-word"></i> Transaction Summary</a></div>';
                if($second->status_notice == null){
                    $documents .= "<a class='btn btn-warning btn-small performing text-white' data-target=".url('/mailprocessed/'.$dform->id)."><i class='dripicons dripicons-forward'></i> Mail to Customer</a>";
                }
                if(self::getGuarantor($offer->id) != null){
                    foreach(self::getGuarantor($offer->id) as $guarantor){
                        if($guarantor->firstname != null){
                            $documents .= '<div class="d-inline-block border-left border-gray p-2"><a class="btn btn-success btn-small" href="offers/view/guarantors/'.$guarantor->id.'" target="_blank"><i class="dripicons dripicons-flag"></i> Guarantor: '.strtoupper($guarantor->firstname.' '.$guarantor->middle_name.' '.$guarantor->surname).'</a></div>';
                        }
                    }
                }
            }
        }
        $documents .= '</div><div class="d-inline-block p-2"><a class="btn btn-success btn-small" href="/customers/comments/'.$dform->id.'" target="_blank">COMMENTS</a>';
        if($user->role == "monitoring and compliance" && $second->compliance == "pending")
            $documents .= "<a class='btn btn-primary btn-small ml-2 mr-2 text-white p-2 performing' data-target=".url('/approve/compliance/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i> Approve</a>";
        if($second->credit_risk == 'approved'){
            $documents .= '<a class="btn btn-secondary btn-small ml-2" href="/customers/recommendation/'.$dform->id.'" target="_blank">Recommendation Sheet</a>';
        }
        $action ='';
        if($dform->folder != null){
            $folder = self::getFolder($dform->folder);
            $action .= "<a class='btn btn-primary btn-small ml-2' href='/file-manager/view/".$folder->slug."' target='_blank'>View Folder</a>";
        }
        if($dform->folder != null){
            $folder = self::getFolder($dform->folder);
            $documents .= "<a class='btn btn-primary btn-small ml-2' href='/file-manager/view/".$folder->slug."' target='_blank'>View Folder</a>";
        }
        $documents .= '</div>';
        return $documents;
	}

	public static function getAction($dform,$second,$user){
		$action = '<div class="d-inline-block border-right border-gray p-2"><a class="text-danger p-2" target="_blank" href="/customers/'.$dform->id.'/preview"><i class="fas fa-file-pdf"></i></a>';
        if($second->risk == 'approved'){
            $offer = self::getOffer($dform->id);
            if($offer != null){
                $action .= "<div class='d-inline-block border-left border-gray p-2'><a class='btn btn-danger btn-small' href='/offers/".$offer->id."/offer' target='_blank'><i class='fas fa-file-pdf'></i> Offer Letter</a></div><div class='d-inline-block border-left border-gray p-2'><a class='btn btn-primary btn-small' href='/offers/".$offer->id."/summary' target='_blank'><i class='fas fa-file-word'></i> Transaction Summary</a></div>";
                if(self::getGuarantor($offer->id) != null){
                    foreach(self::getGuarantor($offer->id) as $guarantor){
                        if($guarantor->firstname != null){
                            $action .= '<div class="d-inline-block border-left border-gray p-2"><a class="btn btn-success btn-small" href="offers/view/guarantors/'.$guarantor->id.'" target="_blank"><i class="dripicons dripicons-flag"></i> Guarantor: '.strtoupper($guarantor->firstname.' '.$guarantor->middle_name.' '.$guarantor->surname).'</a></div>';
                        }
                    }

                }
            }
        }
        $action .= "</div><div class='d-inline-block p-2'>";
        if($second->credit_risk == 'pending' && $user->role == 'credit risk'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }else if($second->credit_risk == 'declined' && $user->role == 'credit risk'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }else if($second->credit_risk == 'approved' && $second->underwriters == 'pending' && $user->role == 'underwriter'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }elseif($second->credit_risk == 'approved' && $second->underwriters == 'declined' && $user->role == 'underwriter'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }else if($second->underwriters == 'approved' && $second->risk == 'pending' && $user->role == 'risk'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }elseif($second->underwriters == 'approved' && $second->risk == 'declined' && $user->role == 'risk'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }elseif($second->customer == 'pending'){
        }elseif($second->customer == 'declined') {
        }elseif($second->customer == 'approved' && $second->operations == 'pending' && $user->role == 'operations'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }elseif($second->customer == 'approved' && $second->operations == 'declined' && $user->role == 'operations'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }elseif($second->operations == 'approved' && $second->finance == 'pending' && $user->role == 'finance'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }elseif($second->operations == 'approved' && $second->finance == 'declined' && $user->role == 'finance'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }elseif($second->finance == 'approved' && $second->internal_control == 'pending' && $user->role == 'internal control'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }elseif($second->finance == 'approved' && $second->internal_control == 'declined' && $user->role == 'internal control'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }elseif($second->internal_control == 'approved' && $second->status == 'pending' && $user->role == 'director'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }elseif($second->internal_control == 'approved' && $second->status == null && $user->role == 'director'){
            $action .= "<a class='text-primary p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a><a class='text-danger p-2 performing' data-target=".url('/decline/'.$dform->id)."><i class='lni-close'></i></a>";
        }elseif($second->internal_control == 'approved' && $second->status == 'declined' && $user->role == 'director'){
            $action .= "<a class='text-purple p-2 performing' data-target=".url('/approve/'.$dform->id)."><i class='dripicons dripicons-checkmark'></i></a>";
        }
        $action .= "<a class='btn btn-success btn-small' href='/customers/comments/".$dform->id."' target='_blank'>COMMENTS</a>";
        if(count(DB::table('filled_checklists')->where('form', $dform->id)->get()) > 0){
            $action .= "<a class='btn btn-secondary btn-small ml-2' href='/customers/recommendation/".$dform->id."' target='_blank'>Recommendation Sheet</a>";
        }
        if($dform->folder != null){
            $folder = self::getFolder($dform->folder);
            $action .= "<a class='btn btn-primary btn-small ml-2' href='/file-manager/view/".$folder->slug."' target='_blank'>View Folder</a>";
        }
        $action .= "</div>";
        return $action;
	}

	public static function getSpecificTask(){
		$user = self::getUser();
		$json = array();
		$customers = DB::table('customers')->where('status', null)->orderBy('id', 'asc')->get();
		if($user->role == 'credit risk'){
			foreach($customers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->credit_risk == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['action'] = self::getAction($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
		}else if($user->role == 'underwriter'){
			foreach($customers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->credit_risk == 'approved' && $dform->underwriters == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['action'] = self::getAction($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
		}else if($user->role == 'risk'){
			foreach($customers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->credit_risk == 'approved' && $dform->underwriters == 'approved' && $dform->risk == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['action'] = self::getAction($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
		}else if($user->role == 'operations'){
			foreach($customers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->credit_risk == 'approved' && $dform->underwriters == 'approved' && $dform->risk == 'approved' && $dform->customer == 'approved' && $dform->operations == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['action'] = self::getAction($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
		}else if($user->role == 'finance'){
			foreach($customers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->credit_risk == 'approved' && $dform->underwriters == 'approved' && $dform->risk == 'approved' && $dform->customer == 'approved' && $dform->operations == 'approved' && $dform->finance == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['action'] = self::getAction($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
		}else if($user->role == 'internal control'){
			foreach($customers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->credit_risk == 'approved' && $dform->underwriters == 'approved' && $dform->risk == 'approved' && $dform->customer == 'approved' && $dform->operations == 'approved' && $dform->finance == 'approved' && $dform->internal_control == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['action'] = self::getAction($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
			$perms = DB::table('folder_permissions')->where(['internal_control'=>'pending', 'status'=>'not used'])->orderBy('id', 'asc')->get();
			foreach($perms as $perm){
				$folder = self::getFolder($perm->folder);
				$staff = self::getUserByEmail($perm->staff);
				$action = "<a class='btn btn-warning btn-small' href='/file-manager-permission-grant/".$perm->id."'><i class='dripicons dripicons-anchor'></i> Grant Permission</a><a class='btn btn-danger btn-small ml-2' href='/file-manager-permission-delete/".$perm->id."'><i class='dripicons dripicons-anchor'></i> Delete Permission</a>";
				$nestedData['msg'] = '<b>'.ucwords($staff->name).'</b> has requested for permission to a folder with the title <b>'.$folder->name.'</b>';
                $nestedData['time'] = date('d M, Y', strtotime($perm->created_at));
                $nestedData['title'] = 'Pending Folder Permission Request';
                $nestedData['action'] = $action;
                $json[] = $nestedData;
			}
		}else if($user->role == 'director'){
			foreach($customers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->credit_risk == 'approved' && $dform->underwriters == 'approved' && $dform->risk == 'approved' && $dform->customer == 'approved' && $dform->operations == 'approved' && $dform->finance == 'approved' && $dform->internal_control == 'approved' && $second->status == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['action'] = self::getAction($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
			$perms = DB::table('folder_permissions')->where(['director'=>'pending', 'status'=>'not used'])->orderBy('id', 'asc')->get();
			foreach($perms as $perm){
				$folder = self::getFolder($perm->folder);
				$staff = self::getUserByEmail($perm->staff);
				$action = "<a class='btn btn-warning btn-small' href='/file-manager-permission-grant/".$perm->id."'><i class='dripicons dripicons-anchor'></i> Grant Permission</a><a class='btn btn-danger btn-small ml-2' href='/file-manager-permission-delete/".$perm->id."'><i class='dripicons dripicons-anchor'></i> Delete Permission</a>";
				$nestedData['msg'] = '<b>'.ucwords($staff->name).'</b> has requested for permission to a folder with the title <b>'.$folder->name.'</b>';
                $nestedData['time'] = date('d M, Y', strtotime($perm->created_at));
                $nestedData['title'] = 'Pending Folder Permission Request';
                $nestedData['action'] = $action;
                $json[] = $nestedData;
			}
		}else if($user->role == 'monitoring and compliance'){
			$ncustomers = DB::table('customers')->where('status', 'approved')->orderBy('id', 'asc')->get();
			foreach($ncustomers as $customer){
				$dform = self::getForm($customer->id);
				$second = self::getCustomer2nd($customer->id);
				if($dform->compliance == 'pending'){
	                $nestedData['msg'] = 'There is a pending loan file with the name <b>'.ucwords($dform->firstname." ".$dform->middle_name." ".$dform->surname).'</b> and reference number '.self::getSerial($customer).'. Please find the action button below';
	                $nestedData['title'] = 'Pending Customer Approval';
	                $nestedData['time'] = date('d M, Y', strtotime($customer->created_at));
	                $nestedData['action'] = self::getAction2($customer,$second,$user);
	                $json[] = $nestedData;
				}
			}
		}
		return $json;
	}

	public static function dpaginatee($param1,$param2){
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$itemCollection =  collect($param1);
		$perPage = $param2;
		$currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();
		$paginatedItems= new LengthAwarePaginator($currentPageItems , count($itemCollection), $perPage);
		$paginatedItems->setPath(url()->full());
		return $paginatedItems;
	}

	public static function getRef($param){
		return DB::table('referrals')->where('code', $param)->orWhere('email', $param)->first();
	}

	public static function search_states($search, $array) { 
		$ret = 0;
	    foreach($array as $key => $arr) {
	    	if($arr->state->name == ucwords($search)){
	    		$ret += $key;
	    	}
	    }
	    return $ret;
	}

	public static function getCommissionPaid($user){
		$commission = DB::connection('mysql2')->table('commission_paid')->where('paid_to', $user)->sum('amount');
		return $commission;
	}

	public static function getCommission($duser){
		$user = DB::connection('mysql2')->table('agents')->where('email', $duser)->first();
		$rev = DB::table('customers')->where(['customers.swiss_club_agent'=>$user->referral_code, 'customers.status'=>'approved'])->rightJoin('offers','customers.id', '=', 'offers.d_form')->sum('offers.amount_recommended');
		$revenue = $rev > 0 ? $rev / 100 : 0;
		return $revenue;
	}

	public static function getAgents($referral){
		return DB::connection('mysql2')->table('agents')->where('referral_code', $referral)->first();
	}

    public static function valid_email($str) {
        return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
    }
}
